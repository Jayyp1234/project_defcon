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
    // $myloc=1;
    // $sysgetdata =  $connect->prepare("SELECT * FROM apidatatable WHERE id=?");
    // $sysgetdata->bind_param("s", $myloc);
    // $sysgetdata->execute();
    // $dsysresult7 = $sysgetdata->get_result();
    // $getsys = $dsysresult7->fetch_assoc();
    // $companyprivateKey=$getsys['privatekey'];
    // $minutetoend=$getsys['tokenexpiremin'];
    // $serverName=$getsys['servername'];
    // $sysgetdata->close();

    // $datasentin=ValidateAPITokenSentIN($serverName, $companyprivateKey, $method, $endpoint);
  
    // $user_pubkey = $datasentin->usertoken;

    //  // send error if ur is not in the database
    // if (!getUserWithPubKey($connect, $user_pubkey)){
    //     $errordesc="Bad request";
    //     $linktosolve="htps://";
    //     $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
    //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
    //     $text="User is not in the database ensure the user is in the database";
    //     $method=getenv('REQUEST_METHOD');
    //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
    //     respondBadRequest($data);
    //  }

    // $user_id = getUserWithPubKey($connect, $user_pubkey);
    // check if the current password field was passed 
    if (isset($_POST['orderid'])) {//1 user,2 ADMIN
        $orderid = cleanme($_POST['orderid']);
    } else {
        $orderid = '';
    }
    
    if (isset($_POST['trastype'])) {//1 user,2 ADMIN
        $trastype = cleanme($_POST['trastype']);
    } else {
        $trastype= 0;
    }

    // $fail="";

    // $query = 'SELECT * FROM users WHERE id = ?';
    // $stmt = $connect->prepare($query);
    // $stmt->bind_param("i", $user_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $num_row = $result->num_rows;

    // if ( $num_row < 1){
    //     $errordesc="User not found";
    //     $linktosolve="htps://";
    //     $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
    //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
    //     $text="User is not in the database ensure the user is in the database";
    //     $method=getenv('REQUEST_METHOD');
    //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
    //     respondBadRequest($data);
    // }else{
            $seen=1;
            $pending=2;
            if($trastype==1){
                $checkdata =  $connect->prepare("SELECT id FROM userwallettrans  WHERE orderid=? AND status=?");
                $checkdata->bind_param("ss", $orderid , $seen);
            }else{
                $checkdata =  $connect->prepare("SELECT id FROM userwallettrans  WHERE (orderid=? || addresssentto=?) AND (status=?||status=?)");
                $checkdata->bind_param("ssss", $orderid ,$orderid , $seen,$pending);  
            }
            
            
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            $dnewnum=$dresult->num_rows;
            if ($dnewnum > 0) {
                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = "Transaction successful";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            } else {
                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = "Transaction not yet confirmed";
                $method = getenv('REQUEST_METHOD');
                $status = false;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
    // }
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