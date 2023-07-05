<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/systems/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if ($method == 'POST') {
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
         // send error if ur is not in the database
        if (!getUserWithPubKey($connect, $user_pubkey)){
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User is not in the database ensure the user is in the database";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
            
        }
        else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
        }
        
        if (isset($_POST['pg'])) {
            $pagem = cleanme($_POST['pg']);
        } else {
            $pagem = 1;
        }
        if (isset($_POST['perpage'])) {
            $per_page = cleanme($_POST['perpage']);
        } else {
            $per_page = 15;
        }
        if (isset($_POST['search'])) {
            $search = cleanme($_POST['search']);
        } else {
            $search = "";
        }
                
        $pages=0;
        $startm = ($pagem - 1) * $per_page;



        if (!empty($search) && $search!=" ") {
                        $s = "%{$search}%";
                        $sqlQuery = "SELECT * FROM userbanks WHERE user_id  = ? AND (account_name LIKE ? || account_no LIKE ? || bank_name  LIKE ? || bankcode  LIKE ?) ORDER BY id DESC";
                        $stmt= $connect->prepare($sqlQuery);
                        $stmt->bind_param("sssss",$userid,$s,$s,$s,$s);
                        $stmt->execute();
                        $result= $stmt->get_result();
                        $allnum = $result->num_rows;
                        $num = $allnum;
                        $pages = ceil($num / $per_page);
                        
                        $sqlQuery = "SELECT * FROM userbanks WHERE user_id  = ? AND (account_name LIKE ? || account_no  LIKE ? || bank_name  LIKE ? || bankcode  LIKE ?) ORDER BY id DESC LIMIT ?,? ";
                        $stmt= $connect->prepare($sqlQuery);
                        $stmt->bind_param("sssssss",$userid,$s,$s,$s,$s,$startm,$per_page);
                        $stmt->execute();
                        $result= $stmt->get_result();
                        $numRow = $result->num_rows;
        }
        else{
            $sqlQuery = "SELECT * FROM userbanks WHERE user_id = '$userid' ";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            $pages = ceil($numRow / $per_page);
            
            $sqlQuery = "SELECT * FROM userbanks WHERE user_id = '$userid' LIMIT ?,?";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("ss", $startm,$per_page);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            
        }
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                    array_push($allResponse,json_decode(json_encode($users), true));
                }
                $maindata['userdata']= $allResponse;
                $maindata['total'] = $numRow;
                $maindata['currentpage'] = $pagem;
                $maindata['totalpage'] = $pages;
                $errordesc = "";
                $linktosolve = "https://";
                $hint = [];
                $errordata = [];
                $text = "Data found";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
            else{
                $allResponse = [];
                $maindata['userdata']= $allResponse;
                $maindata['total'] = $numRow;
                $maindata['currentpage'] = $pagem;
                $maindata['totalpage'] = $pages;
                $errordesc = "";
                $linktosolve = "https://";
                $hint = [];
                $errordata = [];
                $text = "Data found";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
        
    }
else{
    $errordesc = "Method not allowed";
    $linktosolve = "htps://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
    
?>
?>