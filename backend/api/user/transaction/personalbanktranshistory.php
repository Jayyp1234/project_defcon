<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
error_reporting(E_ALL);
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
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
    $user_pubkey = $decodedToken->usertoken;
    $bvn="";
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
            
        }    else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
            
        
        # This is Check from the database to see if user has created any bank account 
        $success=1;
        $trs=2;
        $systype=3;
        $sqlQuery = "SELECT bankaccsentto,SUM(amttopay) AS totalin FROM userwallettrans WHERE userid = ? AND status=? AND transtype=? AND systempaidwith=? GROUP BY bankaccsentto";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("ssss",$userid,$success,$trs,$systype);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            //  put this out because in future admin may want to activate both 1 app and monify, if activated, just remove the if(active ) code from all code below
            $allResponse = [];
                        while($users = $result->fetch_assoc()){
                                    $bankaccsentto = $users['bankaccsentto'];
                                    $totalin=$users['totalin'];
                         
                                    array_push($allResponse,array("bankaccount"=>$bankaccsentto,"totaldeposit"=>strval(round($totalin,2))));
                                }
             //  put this out because in future admin may want to activate both 1 app and monify, if activated, just remove the if(active ) code from all code above
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
        }else{
            $maindata=[];
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
}else {
    $errordesc = "Method not allowed";
    $linktosolve = "https://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>