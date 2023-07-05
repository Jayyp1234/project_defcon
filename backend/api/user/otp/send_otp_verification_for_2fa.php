<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
     
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/otp/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') == 'POST') {
    $verifytype= isset($_POST['verifytype']) ? cleanme($_POST['verifytype']) : '';// 1 is email 2 is phone number
    $isit2fasetup= isset($_POST['set2fa']) ? cleanme($_POST['set2fa']) : 0;// 1 is email 2 is phone number
    $query = 'SELECT * FROM apidatatable where id = 1';
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row =  mysqli_fetch_assoc($result);
    $companykey = $row['privatekey'];
    // $servername = $row['servername'];
    $servername="2FA_verification";
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
            $checkdata =  $connect->prepare("SELECT id,timeinserted FROM token WHERE user_id=? ORDER BY id DESC LIMIT 1");
            $checkdata->bind_param("s", $userid);
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
                $checkdata->close();
                
                if($verifytype==2){
                    $useridentity=$user_identity;
                }else if($verifytype==3){
                     $useridentity=$phone;
                }
                // set expireTime of the token to 5 minutes
                $expiresin = 10;
                
                // generate token and insert it into the token table
                // generating  OTP
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $otp = createUniqueToken(7," token","otp","",true,false,false);
              
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
                    
                    if($verifytype==2){
                        
                        if($isit2fasetup==1){
                            $subject = sendVerifySubject($user_id,$user_id,$keyup,$otp);
                            $to = $user_identity;
                            $messageText = sendVerify2FAEmailotpText($user_id,$keyup,$otp);
                            $messageHTML = sendVerify2FAEmailotpHTML($user_id,$keyup,$otp);
                        }else{
                            $subject = send2faVerifySubject($user_id,$user_id,$keyup,$otp);
                            $to = $user_identity;
                            $messageText = send2faVerifyText($user_id,$keyup,$otp);
                            $messageHTML = send2faVerifyHTML($user_id,$keyup,$otp);
                        }
                   
                        if (sendUserMail($subject,$to,$messageText, $messageHTML)) {
                                // $updatePassQuery = "UPDATE users SET 2fa = 2 WHERE id = $user_id";
                                // $updateStmt = $connect->prepare($updatePassQuery);
                                // $updateStmt->execute();
                            # code...
                                $maindata=$useridentity;
                                $errordesc=" ";
                                $linktosolve="https://";
                                $hint=[];
                                $errordata=[];
                                $text="Check your email and click and type in your OTP";
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
                    }else if($verifytype==3){
                        $smstosend = sendVerify2FAEmailotpText($user_id,$keyup,$otp);
                        $sendto= $useridentity;
                        if (sendUserSMS($sendto,$smstosend)) {
                            # code...
                                
                                
                                $maindata=$useridentity;
                                $errordesc=" ";
                                $linktosolve="https://";
                                $hint=[];
                                $errordata=[];
                                $text="Check your sms and type in your OTP";
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