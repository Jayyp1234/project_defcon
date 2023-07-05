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

$endpoint="../../api/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') == 'POST') {
    $email = isset($_POST['email']) ? cleanme($_POST['email']) :"";
    if (!empty($email)) {
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            # code...
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["The email sent must be a valid email Email Address"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Invalid Email Addresss";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else {
            $checkdata =  $connect->prepare("SELECT id,email,username,fname FROM users WHERE email=? ");
            $checkdata->bind_param("s", $email);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            if ($dresult->num_rows == 0) {// checking if data is valid
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Data not registered in the database.", "Use registered email to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Your email does not exists.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else {
                $row = $dresult->fetch_assoc();
                $userName = $row['username'];
                $user_id = $row['id'];
                $user_identity = $row['email'];
                $firstname = $row['fname'];
                $checkdata->close();
                
                // getting system settings
                $myloc=1;
                $sysgetdata =  $connect->prepare("SELECT name,baseurl FROM systemsettings WHERE id=?");
                $sysgetdata->bind_param("s", $myloc);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->fetch_assoc();
                $systemname=$getsys['name'];
                $sysbaseurl=$getsys['baseurl'];
                $sysgetdata->close();

                
                // set expireTime of the token to 5 minutes
                $expiresin = 5;

                // generate token and insert it into the token table
                 // generating  token
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $token= createUniqueToken(18," token","token","",true,true,true);
                 // generating  OTP
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $otp = createUniqueToken(5," token","otp","",true,false,false);
                
                $created_time = new DateTimeImmutable();
                $expiretime = time() + ($expiresin*60);
                $verifyType = 1;
                $resetToken = strtolower("$systemname".$token);

                $tokenQuery = 'INSERT INTO token (user_id, useridentity, token, time, verifytype,otp) Values (?, ?, ?, ?, ?,?)';
                $tokenStmt = $connect->prepare($tokenQuery);
                $tokenStmt->bind_param("issiis", $user_id, $user_identity, $resetToken, $expiretime, $verifyType,$otp);
                
                // check if statement executes 
                if ($tokenStmt->execute()){
                    $tokenStmt->close();
                    $tokenlink = "?token=".$resetToken;
                    // Subject
                    $subject = forgotpassSubject($user_id,$resetToken,$otp);
                    $to = $email;
                    $messageText = forgotPasswordText($user_id,$resetToken,$otp);
                    $messageHTML = forgotPasswordHTML($user_id,$resetToken,$otp);
                    if (sendUserMail($subject,$to,$messageText, $messageHTML)) {
                        # code...
                            $maindata=['Success'];
                            $errordesc=" ";
                            $linktosolve="https://";
                            $hint=[];
                            $errordata=[];
                            $text="Check your email and click on the link to reset your password!";
                            $method=getenv('REQUEST_METHOD');
                            $status=true;
                            $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);
    
                    }else {
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["problem encountered while trying to send email"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Error sending email. Try again!";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    } 
                
                } else{
                    // send internal error response
                    $errordesc =  $tokenStmt->error;
                    $linktosolve = 'https://';
                    $hint = "500 code internal error, check ur database connections";
                    $errorData = returnError7003($errordesc, $linktosolve, $hint);
                    $text="Error Fetching Reset Token";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondInternalError($data);
                }
            }
        }
        # code...
    }else {
        # code...
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure EMAIL is specified in the API sent"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Email Address is required";
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