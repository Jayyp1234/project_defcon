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
   
    // $user_id = getUserWithPubKey($connect, $user_pubkey);
    // check if the current password field was passed 
    if (isset($_POST['orderid'])) {//1 user,2 ADMIN
        $orderid = cleanme($_POST['orderid']);
    } else {
        $orderid = '';
    }
    
    if (isset($_POST['merchant_trackid'])) {//1 user,2 ADMIN
        $merchant_trackid = cleanme($_POST['merchant_trackid']);
    } else {
        $merchant_trackid= 0;
    }

    $fail="";
    $user_id = getUserWithPubKey($connect, $user_pubkey);

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
                // merchant_trackid
                $seen=2;
                $pend=0;
                $paidtype=5;
                $inpending=0;
                // get trans details
                $transid="";
                $getexactdata =  $connect->prepare("SELECT amountsentin FROM userwallettrans WHERE systempaidwith=? AND status=? AND peerstack_agent=? AND orderid=? AND userid=?");
                $getexactdata->bind_param("sssss",$paidtype,$pend,$merchant_trackid,$orderid,$user_id);
                $getexactdata->execute();
                $rresult2 = $getexactdata->get_result();
                $num = $rresult2->num_rows ;
                if ($num>0) {
                    $ddatasent=$rresult2->fetch_assoc();
                    $amountsentin=$ddatasent['amountsentin'];
                    $inpending=1;
                }

        
               
                $checkdata =  $connect->prepare("UPDATE userwallettrans  SET status=? WHERE systempaidwith=? AND status=? AND peerstack_agent=? AND orderid=? AND userid=?");
                $checkdata->bind_param("isssss",$seen,$paidtype,$pend,$merchant_trackid,$orderid,$user_id);
            if ($checkdata->execute()) {

                $checkdata->close();
                
                if($inpending==1){
                    // notifiy telegram
                    $pttype=2;
                    $bankid=0;
                    sendAdminPeersatckTeleNoti($pttype,$orderid,$amountsentin,$user_id,$merchant_trackid,$bankid);
                }

                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = "Kindly wait while transaction is confimred";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            } else {
                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = "Kindly wait while transaction is confimred";
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