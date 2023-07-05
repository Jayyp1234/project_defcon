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
    $username = isset($_POST['username']) ? cleanme($_POST['username']) : '';
    $phone = isset($_POST['phone']) ? cleanme($_POST['phone']) : '';
    $password = isset($_POST['password']) ? cleanme($_POST['password']) : '';
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

    $phoneNumber = $phone;
    $pattern = '/^(\+?234|0)?([789]\d{9})$/';
    $replacement = '0$2';
    $newPhoneNumber = preg_replace($pattern, $replacement, $phoneNumber);
    $replacement = '$2';
    $newPhoneNumber2 = preg_replace($pattern, $replacement, $phoneNumber);
    
    $checkdata =  $connect->prepare("SELECT * FROM users WHERE id=? ");
    $checkdata->bind_param("s", $user_id );
    $checkdata->execute();
    $dresult = $checkdata->get_result();
    
    $checkdata =  $connect->prepare("SELECT id FROM users WHERE username=? ");
    $checkdata->bind_param("s", $username);
    $checkdata->execute();
    $dresult2 = $checkdata->get_result();
    
    $checkdata =  $connect->prepare("SELECT id FROM users WHERE (phoneno=? || phoneno=?|| phoneno=? ) AND id<>?");
    $checkdata->bind_param("sssi", $phone,$newPhoneNumber,$newPhoneNumber2,$user_id);
    $checkdata->execute();
    $dresult3 = $checkdata->get_result();


    if ((empty($username)) || $username == null  || (empty($phone)) || $phone == null || (empty($password))) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);

    } 
      elseif ($dresult2->num_rows >0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Your username already exists.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
    elseif ($dresult3->num_rows >0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Your phone number already exists.";
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
    elseif (strlen($username)<5) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Username can not be less than 5 characters";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
    elseif (strlen($username)>25) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Username can not be more than 25";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } elseif (preg_match('/\s/',$username)||preg_match('/\./',$username)) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Username must not have whitespace or dot.!!!";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } elseif (preg_match('/[^a-z0-9 ]+/i',$username)) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Username must not have special character.!!!";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } 
    if (!preg_match("([0-9]+)", $phone)) { 
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Invalid phone number format";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
    if (isStringHasEmojis($username)) {
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Emoji is not allowed in username, first name and last name";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
    }
    if (strlen($password)<8) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Password can not be less than 8 characters";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }

    $found= $dresult->fetch_assoc();
    $statusis=$found['status'];
    $email = $found['email'];
    $myloc=1;
        $sysgetdata =  $connect->prepare("SELECT privatekey,tokenexpiremin,servername FROM apidatatable WHERE id=?");
        $sysgetdata->bind_param("s", $myloc);
        $sysgetdata->execute();
        $dsysresult7 = $sysgetdata->get_result();
        $getsys = $dsysresult7->fetch_assoc();
        $companyprivateKey=$getsys['privatekey'];
        $minutetoend=$getsys['tokenexpiremin'];
        $serverName=$getsys['servername'];
        $sysgetdata->close();
        
        // generating user access token
        $accesstoken=getTokenToSendAPI($user_pubkey,$companyprivateKey,$minutetoend,$serverName);
        $maindata['access_token']=$accesstoken;
                $password=Password_encrypt($password);
                $sql = "UPDATE users SET username = ?,phoneno=?,password=? WHERE email =?";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param('ssss', $username,$phone,$password,$email);
                $stmt->execute();
                    if ($stmt->affected_rows > 0){
                        $maindata['verification']=0;
                        
                        $maindata=[$maindata];
                        $errordesc="";
                        $linktosolve="https://";
                        $hint=[];
                        $errordata=[];
                        $text="User account created successfully, kindly verify your account...";
                        $method=getenv('REQUEST_METHOD');
                        $status=true;
                        $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                    else{
                        $errordesc="Method not allowed";
                        $linktosolve="htps://";
                        $hint=["Ensure to use the method stated in the documentation."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Error Updating User";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondMethodNotAlowed($data);
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









