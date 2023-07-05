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
   $method=getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') == 'POST') {
    $verifytype= isset($_POST['verifytype']) ? cleanme($_POST['verifytype']) : '';// 1 is email 2 is phone number
      $medthodis= isset($_POST['method']) ? cleanme($_POST['method']) : 0;// 1 whatsapp none is sms
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
    if (!getUserWithPubKey($connect, $user_pubkey)){
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User is not in the database ensure the user is in the database";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
            
    }else if(empty($verifytype)){
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Verification type is needed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
    } else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
    // checking if sms is sent is not older than 3 min
            $checkdata =  $connect->prepare("SELECT id,timeinserted FROM token WHERE user_id=? AND verifytype=? ORDER BY id DESC LIMIT 1");
            $checkdata->bind_param("ss", $userid,$verifytype);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            if ($dresult->num_rows > 0) {
                $row = $dresult->fetch_assoc();
                $time = strtotime($row['timeinserted']);
                $differenceis= time() - $time;
                $minute = round($differenceis/60);
                $left=60-$differenceis;
                if($minute<1){
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="You need to wait for at least 1 minute before you can resend ($left seconds left)";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                } 
            }
            
            
            
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
                $row = $dresult->fetch_assoc();
                $useridentity="";
                $userName = $row['username'];
                $user_id = $row['id'];
                $user_identity = $row['email'];
                $firstname = $row['fname'];
                $phone = $row['phoneno'];
                $email_verified = $row['emailverified'];
                $phone_verified = $row['phoneverified'];
                $checkdata->close();
                
                if($verifytype==1){
                    $useridentity=$user_identity;
                }else if($verifytype==2){
                     $useridentity=$phone;
                }
                // set expireTime of the token to 5 minutes
                $expiresin = 10;
                
                // generate token and insert it into the token table
                // generating  OTP
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $otp = createUniqueToken(5," token","otp","",true,false,false);
              
                $expiretime = time() + ($expiresin*60);
                $verifyType = 1;
                // generating  token
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $keyup = createUniqueToken(18," token","token","",true,true,true);

                $tokenQuery = 'INSERT INTO  token (user_id,useridentity,token,time,verifytype,otp) Values (?, ?, ?, ?,?,?)';
                $tokenStmt = $connect->prepare($tokenQuery);
                $tokenStmt->bind_param("isssss", $user_id,$useridentity,$keyup,$expiretime, $verifytype,$otp);
                
                // check if statement executes 
                if ($tokenStmt->execute()){
                    $tokenStmt->close();
                    
                    if($verifytype==1){
                        if($email_verified == 0){
                            $subject = sendVerifySubject($user_id,$user_id,$keyup,$otp);
                            $to = $user_identity;
                            $messageText = sendVerifyEmailotpText($user_id,$keyup,$otp);
                            $messageHTML = sendVerifyEmailotpHTML($user_id,$keyup,$otp);
                            if (sendUserMail($subject,$to,$messageText, $messageHTML)) {
                                # code...
                                    $maindata=$to;
                                    $errordesc=" ";
                                    $linktosolve="https://";
                                    $hint=[];
                                    $errordata=[];
                                    $text="Check your email for an OTP and input below.";
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
                        }
                        else{
                            $errordesc="Bad request";
                                $linktosolve="https://";
                                $hint=["problem encountered while trying to send email"];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Your Email is Already Verified.";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                        }
                         
                    }else if($verifytype==2){
                        if($phone_verified == 0){
                            $smstosend = sendVerifyEmailotpText($user_id,$keyup,$otp);
                            $sendto= $useridentity;
                            
                              $smssentni=false;
                                   if($medthodis!=3){
                        // if($medthodis==1){
                           $smssentni1=  sendWithSimpuWhatsApp($sendto,$smstosend);
                        // }else{
                            $smssentni2=  sendUserSMS($sendto,$smstosend);
                        // }
                        }else{
                            // TG OTP
                            $smssentni1=true;
                            $smssentni2=true;
                            send_call_otp($otp,$sendto,"$expiresin Minutes",$keyup,$user_id);
                        }
                        
                        if ($smssentni1||$smssentni2) {
                            
                            // if (sendUserSMS($sendto,$smstosend)) {
                                # code...
                                    $maindata=$sendto;
                                    $errordesc=" ";
                                    $linktosolve="https://";
                                    $hint=[];
                                    $errordata=[];
                                    $text="Check your sms for an OTP and input below.";
                                    $method=getenv('REQUEST_METHOD');
                                    $status=true;
                                    $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                    respondOK($data);
            
                            }else {
                                $errordesc="Bad request";
                                $linktosolve="https://";
                                $hint=["problem encountered while trying to send SMS"];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Error sending sms. Try again!";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                            } 
                        }
                        else{
                            $errordesc="Bad request";
                            $linktosolve="https://";
                            $hint=["problem encountered while trying to send email"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Your phone number is already verified.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
                    }else{
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Invalid Verification type";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }
                } else{
                    // send internal error response
                    $errordesc = "Error setting up token";// $tokenStmt->error;
                    $linktosolve = 'https://';
                    $hint = "500 code internal error, check ur database connections";
                    $errorData = returnError7003($errordesc, $linktosolve, $hint);
                    $text="Error Fetching Reset Token";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errorData);
                    respondInternalError($data);
                }
            }  
            
            
            
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