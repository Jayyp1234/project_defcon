<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $maindata=[];

    if($method =='GET'){
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
        $user_pubkey = cleanme($decodedToken->usertoken);
        $user_id = getUserWithPubKey($connect, $user_pubkey);
        $admin_id = checkIfIsAdmin($connect, $user_pubkey);

        // send error if user is not in the database
        if (!$user_id && !$admin_id) {
            // user not found response
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User is not in the database ensure the user is in the database";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }

        $params = [];
        $strings = "";
        $extraquery=" ";
        if ($admin_id  ) {
            if ( isset($_GET['user_id']) ) {
                $user = cleanme($_GET['user_id']); //sort if > 0
                $extraquery .= " AND userid = ?";
                $strings .= "s";
                $params[] = $user; 
            }
        }else{
            $extraquery .= " AND userid = ?";
            $strings .= "s";
            $params[] = $user_id;
        }

        //sort by transaction type 1=swap, 2=bills 3=referal
        if (isset($_GET['trans_type']) && is_numeric($_GET['trans_type'])) {
            $trans_type = cleanme($_GET['trans_type']); //sort if > 0
            $extraquery .= " AND trans_type = ?";
            $strings .= "s";
            $params[] = $trans_type; 
        }

        //sort by cashback id
        if (isset($_GET['cashback_id'])) {
            $cashback_id = cleanme($_GET['cashback_id']); //sort if > 0
            $extraquery .= " AND transorderid = ?";
            $strings .= "s";
            $params[] = $cashback_id; 
        }
    
        if (isset ($_GET['page']) ) { 
            if(!empty($_GET['page']) && is_numeric($_GET['page']) ){
                $page_no = $_GET['page']; 
            }else{
                $page_no = 1;
            }
        } else {  
            $page_no = 1;  
        }

        if (isset ($_GET['noPerPage']) ) {  
            if(!empty($_GET['noPerPage']) && is_numeric($_GET['noPerPage']) ){
                $noPerPage = $_GET['noPerPage']; 
            }else{
                $noPerPage =805;
            }
        } else {  
            $noPerPage =805;  
        } 
        $offset = ($page_no - 1) * $noPerPage;

        //get 
        $sqlQuery = "SELECT * FROM `cashback_history` WHERE id > 0 $extraquery";
        
        $stmt= $connect->prepare($sqlQuery);
        if(count($params)> 0){
            $stmt->bind_param("$strings", ...$params);
        }
        $stmt->execute();
        $result= $stmt->get_result();
        $total_numRow = $result->num_rows;
        $pages = ceil($total_numRow / $noPerPage);

        $params[] = $offset;
        $params[] = $noPerPage;
        $strings .= "ss";
        
        //query for pagination
        $sqlQuery = "$sqlQuery ORDER BY id DESC LIMIT ?,?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$strings", ...$params);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
    

        //check for database connection 
        if(!$stmt->execute()){
            //DB error || invalid input
            $errordesc=$stmt->error;
            $linktosolve="htps://";
            $hint=["Ensure database connection is on","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Database comection error";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondInternalError($data);
        }
        //return fetched data as array
        
        if($numRow > 0){
            $stmt->close();
            $allResponse = [];
            while($row = $result->fetch_assoc()){
                
                unset($row['updated_at']);    
                //get referal details              
                $referalDetails = ($row['trans_type'] == 3)? getMultiColumnFromField('users', "username,userlevel,referalredeem",'id', $row['referaluserid']) : '';
                $row['referalUsername'] = ($referalDetails) ? $referalDetails['username'] : '';
                $row['referalLevel'] = ($referalDetails) ? $referalDetails['userlevel'] : '';
                $row['referalRedeem'] = ($referalDetails) ? $referalDetails['referalredeem'] : '';
                $row['cashbackby_text'] ="";
                if($row['trans_type']==1){
                    $row['cashbackby_text'] ="Cashback for swap";
                }else if($row['trans_type']==2){
                     $row['cashbackby_text'] ="Cashback for bill";
                }else if($row['trans_type']==3){
                     $row['cashbackby_text'] ="Cashback for referral";
                }
                
                $row['created'] = gettheTimeAndDate(strtotime($row['created_at']));
                $data = json_decode(json_encode($row), true);
                array_push($allResponse, $data);

            }
            $maindata = [
                'page' => $page_no,
                'per_page' => $noPerPage,
                'total_data' => $total_numRow,
                'totalPage' => $pages,
                'cashBacks'=> $allResponse
            ];
            $linktosolve = "htps://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }else{
            //not found
            $errordesc = " ";
            $linktosolve = "htps://";
            $hint = [];
            $errordata = [];
            $text = "Record Not Found";
            $status = false;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }


    }else {
        // method not allowed
        $errordesc="Method not allowed";
        $linktosolve="htps://";
        $hint=["Ensure to use the method stated in the documentation."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Method used not allowed";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondMethodNotAlowed($data);
    }
?>