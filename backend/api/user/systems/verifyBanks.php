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
if ($method  == 'POST') {
    // Get company private key
    // $query = 'SELECT * FROM apidatatable';
    // $stmt = $connect->prepare($query);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $row =  mysqli_fetch_assoc($result);
    // $companykey = $row['privatekey'];
    // $servername = $row['servername'];
    // $expiresIn = $row['tokenexpiremin'];
    // $decodedToken = ValidateAPITokenSentIN($servername, $companykey, $method, $endpoint);
    // $user_pubkey = $decodedToken->usertoken;
         // send error if ur is not in the database
        // if (!getUserWithPubKey($connect, $user_pubkey)){
        //     $errordesc="Bad request";
        //     $linktosolve="htps://";
        //     $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
        //     $text="User is not in the database ensure the user is in the database";
        //     $method=getenv('REQUEST_METHOD');
        //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
        //     respondBadRequest($data);
            
        // }
        // else{
        //     $userid = getUserWithPubKey($connect, $user_pubkey);
        // }
         $userid ="9oiiio";
        if ( !isset($_POST['accountnumber']) || !isset($_POST['bankcode'])){
            $errordesc="All fields must be passed";
            $linktosolve="https://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required accountnumber field in this endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $bankcode = cleanme($_POST['bankcode']);
            $accountnumber = cleanme($_POST['accountnumber']);
        }

        if (empty($userid) || empty($accountnumber) || empty($bankcode)){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the account name, account number field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $account = getUserAccountName($bankcode,$accountnumber);
            
            if ($account == 'Invalid account number'||empty($account)){
                $errordesc="Invalid";
                $linktosolve="https://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid account number";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            else{
                $allResponse = $account;
                $maindata['userdata']= $allResponse;
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
        
}else{
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