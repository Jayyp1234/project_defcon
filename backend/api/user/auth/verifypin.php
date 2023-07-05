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

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') == 'POST') {
    $maindata['frozedate']="";

    #Get Post Data
    $pin = isset($_POST['pin']) ? cleanme($_POST['pin']) : '';
    $fcm = '';
    
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
        // send user not found response to the user
        $errordesc =  "Not Authorized";
        $linktosolve = 'https://';
        $hint = "Only authorized user allowed";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondBadRequest($data);
    }
    
    $user_id = getUserWithPubKey($connect, $user_pubkey);

    $checkdata =  $connect->prepare("SELECT id,email,emailverified,pin,2fa,username,userpubkey,phoneno,status FROM users WHERE id=? ");
    $checkdata->bind_param("s", $user_id );
    $checkdata->execute();
    $dresult = $checkdata->get_result();


    if ((empty($pin))) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);

    } 
    elseif ($dresult->num_rows == 0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Your email and/or pin are invalid.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }

    $found= $dresult->fetch_assoc();
    $pass = $found['pin'];
    

        //verify the new pin with the db pass
        $verifypass =check_pass($pin, $pass);
        if ($verifypass&&!empty($pass)) {
          
                $maindata=[];
                $errordesc="";
                $linktosolve="https://";
                $hint=[];
                $errordata=[];
                $text="Pin Verified";
                $method=getenv('REQUEST_METHOD');
                $status=true;
                $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
                    
         
    }
        else {
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Invalid pin.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
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