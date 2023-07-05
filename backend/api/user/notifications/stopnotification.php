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

$endpoint="../../api/user/notifications/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
$maindata=[];
if ($method == 'GET') {
    $fail="";
    $myloc=1;
    $sysgetdata =  $connect->prepare("SELECT * FROM apidatatable WHERE id=?");
    $sysgetdata->bind_param("s", $myloc);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    $getsys = $dsysresult7->fetch_assoc();
    $companyprivateKey=$getsys['privatekey'];
    $minutetoend=$getsys['tokenexpiremin'];
    $serverName=$getsys['servername'];
    $sysgetdata->close();

    $datasentin=ValidateAPITokenSentIN($serverName, $companyprivateKey, $method, $endpoint);
    $user_pubkey = $datasentin->usertoken;
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
     
    $user_id = getUserWithPubKey($connect, $user_pubkey);

    if ($fail=="") {
        
            $sid =$user_id;
            $notseen=0;
            $seen=1;
            $checkdata =  $connect->prepare("UPDATE usernotification  SET seenbyuser=? WHERE userid=?");
            $checkdata->bind_param("ii",$seen,$sid);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            $checkdata->close();

            $maindata = [];
            //convert resonse to JSON starts
            $errordesc = " ";
            $linktosolve = "htps://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        //convert resonse to JSON ends
    } else {
        $errordesc = "Bad request";
        $linktosolve = "htps://";
        $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
        $errordata = returnError7003($errordesc, $linktosolve, $hint);
        $text = $fail;
        $data = returnErrorArray($text, $method, $endpoint, $errordata);
        respondBadRequest($data);
    }
} else {
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