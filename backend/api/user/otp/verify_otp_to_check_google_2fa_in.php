<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    require_once '../../../config/GoogleAuthenticator/vendor/autoload.php';
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/otp/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') === 'POST'){
        //collect input and validate it
        if(!isset($_POST['code']) ){
            $errordesc="Pin required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input OTP Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
      
        else{
            $code = cleanme($_POST['code']);
            $query = 'SELECT * FROM apidatatable where id = 1';
            $stmt = $connect->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row =  mysqli_fetch_assoc($result);
            $companykey = $row['privatekey'];
            
            $servername="2FA_verification";
            // $servername = $row['servername'];
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
                    
            }else{
                    $userid = getUserWithPubKey($connect, $user_pubkey);
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
                        $user_id = $row['id'];
                        $user_identity = $row['email'];
                        $firstname = $row['fname'];
                        $dashunmae = $row['username'];
                        $phone = $row['phoneno'];
                        $secret = $row['google_secret_key'];
                        $checkdata->close();
                        
                        $query = 'SELECT name FROM systemsettings where id = 1';
                        $stmt = $connect->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row =  mysqli_fetch_assoc($result);
                        $companyname = $row['name'];
                        
                        $ga = new PHPGangsta_GoogleAuthenticator();
                        $qrCodeUrl = $ga->getQRCodeGoogleUrl($companyname, $secret);
                        $oneCode = $ga->getCode($secret);
                        $checkResult = $ga->verifyCode($secret, $oneCode, 2); 
                        if ($oneCode == $code){
                            
                            
                            $seescode = str_shuffle(time().(mt_rand(43, 615)));
                            $ipaddress= getIp();
                            $location = getLoc($ipaddress);
                            $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
                            //Put sessioncode inside database
                            $dateloggedin= time();
                            
                            $sysgetdata =  $connect->prepare("SELECT Email FROM usersessionlog WHERE Ipaddress=?");
                            $sysgetdata->bind_param("s",$ipaddress);
                            $sysgetdata->execute();
                            $dsysresult7 = $sysgetdata->get_result();
                            $getcount = $dsysresult7->num_rows;
                            
                            
                            $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Username,Sessioncode,Date,Ipaddress,Browser, Location) VALUES (?,?,?,?,?,?,?)");
                            $insert_data->bind_param("sssssss", $user_identity,$dashunmae, $seescode, $dateloggedin, $ipaddress, $browser, $location);
                            $insert_data->execute();
                            $insert_data->close();
                        
                            
                            if($getcount==0){
                            $subject = loginmailSubject($user_id); 
                            $to = $user_identity;
                            $messageText = loginMailText($user_id, $seescode);
                            $messageHTML = loginMailHTML($user_id, $seescode);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($phone,$messageText);
                            }
                            
                            // GENERATE ACCESS TOKEN
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
                            $accesstoken=getTokenToSendAPI($user_pubkey,$companyprivateKey,$minutetoend,$serverName);
                            $maindata['access_token']=$accesstoken;

                            # code...
                            $maindata=[$maindata];
                    
                            $errordesc="";
                            $linktosolve="https://";
                            $hint=[];
                            $errordata=[];
                            $text="You Have Successfully Verified 2FA.";
                            $method=getenv('REQUEST_METHOD');
                            $status=true;
                            $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);
                        }
                        else{
                            // send internal error response
                            $errordesc =  "Invalid token";
                             $errordata=[];
                            $linktosolve = 'https://';
                            $hint = "500 code internal error, check ur database connections";
                            $errorData = returnError7003($errordesc, $linktosolve, $hint);
                            $text="Incorrect Token, Try Again.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondInternalError($data);
                        }
                    } 
                }
        }
}else {
        //method not allowed
        $errordesc="Method not allowed";
        $linktosolve="htps://";
        $hint=["Ensure to use the method stated in the documentation."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Method used not allowed";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondMethodNotAlowed($data);
    }




