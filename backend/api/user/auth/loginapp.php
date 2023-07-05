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
if (getenv('REQUEST_METHOD') == 'POST') {
    $maindata['frozedate']="";

    #Get Post Data
    $email = isset($_POST['email']) ? cleanme($_POST['email']) : '';
    $password = isset($_POST['password']) ? cleanme($_POST['password'],1) : '';
    $googlecode =isset($_POST['googlecode']) ? cleanme($_POST['googlecode']) :  '';
       $fcm = isset($_POST['fcm']) ? cleanme($_POST['fcm'],1) : '';
    
    $fail=""; 

    $checkdata =  $connect->prepare("SELECT id,email,emailverified,password,2fa,username,login_2fa,userpubkey,phoneno,phoneverified,status FROM users WHERE email=? || username =? ");
    $checkdata->bind_param("ss", $email, $email);
    $checkdata->execute();
    $dresult = $checkdata->get_result();


    if (empty($email)  || (empty($password))) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);

    }   

//  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errordesc="Bad request";
//         $linktosolve="https://";
//         $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
//         $errordata=returnError7003($errordesc,$linktosolve,$hint);
//         $text="Invalid email format";
//         $method=getenv('REQUEST_METHOD');
//         $data=returnErrorArray($text,$method,$endpoint,$errordata);
//         respondBadRequest($data);
//     }  
    elseif ($dresult->num_rows == 0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Your email and/or password are invalid.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }

    $found= $dresult->fetch_assoc();
    //save fetcheced data inside session and proceed to dashboard
    $id=$user_id = $found['id'];
    $dash_mail = $found['email'];
    $emailverified =$found['emailverified'];
    $phoneverified=$found['phoneverified'];
    $pass = $found['password'];
    $phone = $found['phoneno'];
    $dashunmae= $found['username'];
     $fa= $found['login_2fa'];
    $jjj = $found['2fa'];
    
    $phone = $found['phoneno'];
    $userPubkey= $found['userpubkey'];
    $banreason = 'You have been Banned';
    
    $secret = '6LdXo4YiAAAAAETHal4ANulB3J50cxNM1UnD-UrR';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$googlecode);
    $responseData = json_decode($verifyResponse);
    // if($responseData->success) {
        //verify the new password with the db pass
        $verifypass =check_pass($password, $pass);
        if ($verifypass) {
            $statusis=$found['status'];
            if ($statusis==1) {
                $maindata=[];
                
        // saving user firebase notification token
        if(strlen($fcm)>=3){
        $update_data = $connect->prepare("UPDATE users SET fcm=? WHERE id=?");
        $update_data->bind_param("si", $fcm, $user_id);
        $update_data->execute();
        $update_data->close();
        }

        
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
            
    
                $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                $maindata['access_token']=$accesstoken;
                if($phoneverified==1 && $emailverified==1){
                    $maindata['verification']=3;
                }else if($phoneverified==1 && $emailverified==0){
                     $maindata['verification']=1;
                }else if($phoneverified==0 && $emailverified==1){
                     $maindata['verification']=2;
                }else{
                    $maindata['verification']=0;
                }
                    $maindata['email']=$dash_mail;
                      $maindata['phoneno']=$phone;
                // $maindata['verification']=$emailverified;
                        
                    #To Check For 2FA Authentication
                       #To Check For 2FA Authentication
                    if ($fa == 1){
                        $serverName="2FA_verification";
                        $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                        $maindata['access_token']=$accesstoken;
                        // $maindata['verification']=$emailverified;
                        
                        # code...
                        $maindata['auth_factor'] = true;
                        
                        if($jjj == "2"){
                            $maindata['token'] = "TYGJOHFUIIH";
                        }
                        else if ($jjj == "3"){
                             $maindata['token'] = "TYGJOHFHYUFUJ";
                        }
                        else if ($jjj == "1"){
                            $maindata['token'] = "google";
                        }
                        $maindata=[$maindata];
                        $errordesc="";
                        $linktosolve="https://";
                        $hint=[];
                        $errordata=[];
                        $text="Redirecting to Two Factor Authentication...";
                        $method=getenv('REQUEST_METHOD');
                        $status=true;
                        $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                    else{
                        $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                        $maindata['access_token']=$accesstoken;
                        // $maindata['verification']=$emailverified;

                        $seescode = str_shuffle(time().(mt_rand(43, 615)));
                        $ipaddress= getIp();
                        $breakip=explode(",",$ipaddress);
                        $location = getLoc($breakip[0]);
                        $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
                        //Put sessioncode inside database
                        $dateloggedin= time();
                        
                        $sysgetdata =  $connect->prepare("SELECT Email FROM usersessionlog WHERE Ipaddress=?");
                        $sysgetdata->bind_param("s",$ipaddress);
                        $sysgetdata->execute();
                        $dsysresult7 = $sysgetdata->get_result();
                        $getcount = $dsysresult7->num_rows;
                      
                        
                        $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Username,Sessioncode,Date,Ipaddress,Browser, Location) VALUES (?,?,?,?,?,?,?)");
                        $insert_data->bind_param("sssssss", $dash_mail,$dashunmae, $seescode, $dateloggedin, $ipaddress, $browser, $location);
                        $insert_data->execute();
                        $insert_data->close();
                        
                        $maindata['auth_factor'] = false;
                        
                         if($getcount==0){
                            $subject = loginmailSubject($id); 
                            $to = $dash_mail;
                            $messageText = loginMailText($id, $seescode);
                            $messageHTML = loginMailHTML($id, $seescode);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($phone,$messageText);
                            // $userid,$message,$type,$ref,$status
                            login_user_noti($id,$seescode);

                        }
                 
                        
                        # code...
                        $maindata=[$maindata];
                        $errordesc=" ";
                        $linktosolve="htps://";
                        $hint=[];
                        $errordata=[];
                        $text="Login Successful";
                        $method=getenv('REQUEST_METHOD');
                        $status=true;
                        $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                    
    
        
    
                
            } elseif ($statusis==2) {//suspended
                $maindata['status']=$statusis;
                $maindata=[$maindata];
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure acount is not suspended on the app", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text=$banreason;
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata,$maindata);
                respondBadRequest($data);
            } elseif ($statusis==3) {//frozen
                $maindata['status']=$statusis;
                $maindata['frozedate']=$frotime;
                $maindata=[$maindata];
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure acount is not fozen on the app", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text=$banreason;
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata,$maindata);
                respondBadRequest($data);
            } elseif ($statusis==0) {//banned
                $maindata['status']=$statusis;
                $maindata=[$maindata];
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure acount is not banned on the app", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text=$banreason;
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata,$maindata);
                respondBadRequest($data);
            } else {
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure acount is not banned on the app", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="You Have Been Permanently Banned From this platform with the name associated to your bank account details flagged<br>Contact Support with your user details if you think this was done in error.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
    }
        else {
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Your email and/or password are invalid.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
    }
    // }else{
    //      $errordesc="Verification failed";
    //         $linktosolve="htps://";
    //         $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
    //         $errordata=returnError7003($errordesc,$linktosolve,$hint);
    //         $text="reCAPTCHA challenge Verification failed";
    //         $method=getenv('REQUEST_METHOD');
    //         $data=returnErrorArray($text,$method,$endpoint,$errordata);
    //         respondBadRequest($data);
    // }
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