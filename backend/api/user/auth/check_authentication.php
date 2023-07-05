<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)


include "../../../config/utilities.php";

$endpoint="../../api/".basename($_SERVER['PHP_SELF']);
$method=getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') == 'POST') {
    $query = 'SELECT * FROM apidatatable where id = 1';
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
    $userid = getUserWithPubKey($connect, $user_pubkey);
    $checkdata =  $connect->prepare("SELECT * FROM users WHERE id=? ");
    $checkdata->bind_param("s", $userid);
    $checkdata->execute();
    $dresult = $checkdata->get_result();
    if ($dresult->num_rows == 0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Data not registered in the database.", "Use registered email to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="This User does not exists.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }else {
           $found= $dresult->fetch_assoc();
            //save fetcheced data inside session and proceed to dashboard
            $id = $found['id'];
            $dash_mail = $found['email'];
            $emailverified =$found['emailverified'];
            $pass = $found['password'];
            $userPubkey= $found['userpubkey'];
            $banreason = 'You have been Banned';
            $checkdata->close();
            $maindata['verification']=$emailverified;
            $maindata=[$maindata];
            $errordesc=" ";
            $linktosolve="https://";
            $hint=[];
            $errordata=[];
            $text="Login Successful";
            $method=getenv('REQUEST_METHOD');
            $status=true;
            $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
    } 
    
}

else {
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