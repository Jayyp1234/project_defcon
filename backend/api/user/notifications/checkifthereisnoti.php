<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    Header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";
?>
<?php
$method = getenv('REQUEST_METHOD');
$endpoint = "/api/user/".basename($_SERVER['PHP_SELF']);
$maindata=[];
if (getenv('REQUEST_METHOD') == 'POST') {
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
    // check if the current password field was passed 
    if (isset($_POST['notitype'])) {//1 user,2 ADMIN
        $notitype = cleanme($_POST['notitype']);
    } else {
        $notitype = '';
    }

    $fail="";

     $query = 'SELECT * FROM users WHERE id = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ( $num_row < 1){
            $errordesc="User not found";
            $linktosolve="htps://";
            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User is not in the database ensure the user is in the database";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        
        }else{
$amin=0;
$nessage="";
if($notitype==1){
        $notseen=0;
        $statid=1;
        $notseen=0;
        $checkdata =  $connect->prepare("SELECT notificationtext FROM usernotification  WHERE userid=? AND 	seenbyuser=?");
        $checkdata->bind_param("ss", $user_id , $notseen);
        $checkdata->execute();
        $dresult = $checkdata->get_result();
        $dnewnum=$dresult->num_rows;
        if ($dnewnum > 0) {
            $gtmsg= $dresult->fetch_assoc();
            $nessage = reduce($gtmsg['notificationtext']);
            $err=$nessage;
            $amin=1;
            //convert resonse to JSON ends
            $checkdata->close(); 
            $seen=1;
            $checkdata =  $connect->prepare("UPDATE usernotification  SET 	seenbyuser=? WHERE userid=?");
            $checkdata->bind_param("ii", $seen,  $user_id);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            $checkdata->close();
        }
}
    
    
            if ($amin>0) {
                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = $nessage;
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            } else {
                   $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = $nessage;
                $method = getenv('REQUEST_METHOD');
                $status = false;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
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