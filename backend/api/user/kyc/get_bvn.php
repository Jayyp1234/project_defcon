<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)



include "../../../config/utilities.php";

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
$maindata=[];
if (getenv('REQUEST_METHOD') == 'POST') {
    $fail="";
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
            $user_id = getUserWithPubKey($connect, $user_pubkey);
        }
    if ($fail=="") {
        if(!isset($_POST['pin']) ){
            $errordesc="Pin required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
      
        if(isset($_POST['pin'])){
            $pin = cleanme($_POST['pin']);
        }
        
    
        $checkdata =  $connect->prepare("SELECT pin FROM users WHERE id=? ");
        $checkdata->bind_param("s", $user_id );
        $checkdata->execute();
        $dresult = $checkdata->get_result();

        $found= $dresult->fetch_assoc();
        $pass = $found['pin'];
    
        //verify the new pin with the db pass
        $verifypass =check_pass($pin, $pass);
        if ($verifypass&&!empty($pass)) {
                $checkdata =  $connect->prepare("SELECT bvn FROM kyc_details WHERE user_id=?");
                $checkdata->bind_param("s",$user_id);
                $checkdata->execute();
                $dresult = $checkdata->get_result();
                if ($dresult->num_rows > 0) {
                    $dta= $dresult->fetch_assoc();
                    $submittedbvn=$dta['bvn'];
                    array_push($maindata, array("bvn"=>$submittedbvn));
                    $errordesc = " ";
                    $linktosolve = "htps://";
                    $hint = [];
                    $errordata = [];
                    $text = "Pin Verified";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
        
                } else {
                        $errordesc = "Bad request";
                        $linktosolve = "htps://";
                        $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
                        $errordata = returnError7003($errordesc, $linktosolve, $hint);
                        $text = "KYC not Found";
                        $data = returnErrorArray($text, $method, $endpoint, $errordata);
                        respondBadRequest($data);
                }
                $checkdata->close();
        }
        else {
            $errordesc="Error";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Incorrect Pin.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
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