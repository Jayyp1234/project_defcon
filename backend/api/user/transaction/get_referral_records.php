<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    Header("Cache-Control: no-cache");

     
    include "../../../config/utilities.php";  
    
  

    $endpoint = basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');

    if ($method == 'GET') {

        // Get company private key
        $query = 'SELECT * FROM apidatatable';
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row =  mysqli_fetch_assoc($result);
        $companykey = $row['privatekey'];
        $servername = $row['servername'];
        $expiresIn = $row['tokenexpiremin'];

        $decodedToken = ValidateAPITokenSentIN($servername, $companykey, $method, $endpoint);
        $pubkey = $decodedToken->usertoken;

        $admin =  checkIfIsAdmin($connect, $pubkey);
        $user = getUserWithPubKey($connect, $pubkey);

        if  (!$admin && !$user){

            // send user not found response to the user
            $errordesc =  "User not found";
            $linktosolve = 'https://';
            $hint = "Only Admin has the ability to add send grid api details";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondUnAuthorized($data);
        }

        if ( $admin  ){
            if ( isset($_GET['user']) ){
                $user = "";
            }else{
                $user = cleanme($_GET['user']);
            }

            if ( empty($user) ){
                $errordesc =  "User not found";
                $linktosolve = 'https://';
                $hint = "User not found";
                $errorData = returnError7003($errordesc, $linktosolve, $hint);
                $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                respondBadRequest($data);
            }
        }
        
         $userReferredDetails = getColumsFromField("users", "username, refcode", "WHERE id = ?" ,[$user ]);
        $userrefcode=$userReferredDetails['username'];
        $username= $userReferredDetails['refcode'];     
        // initial where has been added here
        $params = [];
        $sortQuery = " (users.referby = ? || users.referby = ?) ";
        $params[] = $username;
        $params[] = $userrefcode;
        $strings = "ss";


        if (isset($_GET['search'])) {
            $search = cleanme($_GET['search']);
            if (!empty($search) && $search != "" && $search != " "){
                $searching = "%{$search}%";
                $sortQuery .= " AND ( users.username like ? )";
                $params[] = $searching;
                $strings .= "s";
            }
        } else {
            $search = "";
        }

        if ( isset($_GET['status']) && strlen($_GET['status'])>0) {
            $status = cleanme($_GET['status']);
            $sortQuery .= " AND users.referalredeem = ?";
            $params[] = $status;
            $strings .= "s";
        }
        
    
        if (!isset ($_GET['page']) ) {  
            $page_no = 1;  
        } else {  
            $page_no = $_GET['page'];  
        }
        
        if (isset ($_GET['per_page']) ) {  
            $no_per_page = cleanme($_GET['per_page']);
        } else {  
            $no_per_page = 610;  
        }

        $offset = ($page_no - 1) * $no_per_page;
       
        
        // get the total number of pages
        $query = "SELECT users.referalredeem,users.username,users.userlevel,cashback_history.trans_type,cashback_history.status,cashback_history.cashbackorderid,cashback_history.amount AS cashbackAmount, cashback_history.referaluserid, cashback_history.created_at FROM `users` LEFT JOIN cashback_history ON cashback_history.referaluserid = users.id WHERE $sortQuery";
        $queryStmt = $connect->prepare($query);
        $queryStmt->bind_param($strings, ...$params);
        $execute= $queryStmt->execute();
        $result = $queryStmt->get_result();
        $total_num_row = $result->num_rows;
        $total_pg_found =  ceil($total_num_row / $no_per_page); 

        $params[] = $offset;
        $params[] = $no_per_page;
        $strings .= "ss";

        $query = "$query LIMIT ?, ?";
        $queryStmt = $connect->prepare($query);
        $queryStmt->bind_param($strings, ...$params);
        $execute= $queryStmt->execute();
        $result = $queryStmt->get_result();
        $total_num_row = $result->num_rows;
            
        

        if(!$execute){
            //DB error || invalid input
            $errordesc=$gtTotalPgs->error;
            $linktosolve="htps://";
            $hint=["Ensure database connection is on","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Database comection error";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondInternalError($data);
        }

        if ($total_num_row > 0){
            $history = [];
            
            while($row = $result->fetch_assoc()){
                $row["trans_type"]=$row["trans_type"]==null?3:$row["trans_type"];
                $row['referredUsername'] = $row['username'];
                $row['referredLevel'] = $row['userlevel'];

                $row['created_at'] = gettheTimeAndDate(strtotime($row['created_at']));
                        $row['cashbackby_text'] ="";
                if($row['trans_type']==1){
                    $row['cashbackby_text'] ="Cashback for swap";
                }else if($row['trans_type']==2){
                     $row['cashbackby_text'] ="Cashback for bill";
                }else if($row['trans_type']==3){
                     $row['cashbackby_text'] ="Cashback for referral";
                }
                $row['username']=$username;
                $row["status"]=$row["status"]==null?0:$row["status"];
                $row["cashbackorderid"]=$row["cashbackorderid"]==null?"":$row["cashbackorderid"];
                $row["cashbackAmount"]=$row["cashbackAmount"]==null?"+₦0":"+₦".$row["cashbackAmount"];
                $row["referaluserid"]=$row["referaluserid"]==null?"":$row["referaluserid"];
                
                
                $data = json_decode(json_encode($row), true);

                array_push($history, $data);
                
            }
            
            $data = array(
                'page' => $page_no,
                'per_page' => $no_per_page,
                'total_data' => $total_num_row,
                'totalPage' => $total_pg_found,
                'history' => $history,
            );
            $text= "Fetch Successful";
            $status = true;
            $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
            respondOK($successData);
        }

        $errordesc = "No Records found";
        $linktosolve = 'https://';
        $hint = "Kindly make sure the table has been populated";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondOK($data);

    }else{

        // Send an error response because a wrong method was passed 
        $errordesc = "Method not allowed";
        $linktosolve = 'https://';
        $hint = "This route only accepts GET request, kindly pass a post request";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondMethodNotAlowed($data);

    }
?>