<?php
ob_start();
session_start();

include "google-auth-api.php";

//index.php
//Include Configuration File

$login_button = '';
$endpoint="googleRedirect.php";
//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"])) {
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

     //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
     if(!isset($token['error'])) {
      //Set the access token used for requests
      $google_client->setAccessToken($token['access_token']);
    
      //Store "access_token" value in $_SESSION variable for future use.
      $_SESSION['access_token'] = $token['access_token'];
    
      //Create Object of Google Service OAuth 2 class
      $google_service = new Google_Service_Oauth2($google_client);
    
      //Get user profile data from google
      $data = $google_service->userinfo->get();
        
        $email = $data['email'];
     
        
            
        include "./utilities.php";
        
        if(isset($_SESSION['type']) && $_SESSION['type'] == 'LOGIN'){
            $checkdata =  $connect->prepare("SELECT id,email,emailverified,password,2fa,username,userpubkey,phoneno,login_2fa,status FROM users WHERE email=? || username =? ");
            $checkdata->bind_param("ss", $email, $email);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            
            if ($dresult->num_rows == 0) {// checking if data is valid
                    #Get Post Data
                    $firstname = isset($data['givenName']) ? cleanme($data['givenName']) : '';
                    $lastname = isset($data['familyName']) ? cleanme($data['familyName']) : '';
                    $username = null;
                    $email = isset($data['email']) ? cleanme($data['email']) : '';
                    $phone = null;
                    $referedby = isset($data['referedby']) ? cleanme($data['referedby']) : '';
                    $fcm = isset($data['fcm']) ? cleanme($data['fcm']) : '';
                    $password = isset($data['id']) ? cleanme($data['familyName']."C1X",1) : '';
                    
                    
                    $fail=""; 
                
                    if (empty($email) || empty($firstname) || empty($lastname) || (empty($password)) ) {//checking if data is empty
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Please fill all data";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }   elseif (strlen($firstname)>25) {// checking if data is valid
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="First name can not be more than 25";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }  elseif (strlen($lastname)>25) {// checking if data is valid
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Last name can not be more than 25";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }  
                    // getting system settings
                    $myloc=1;
                    $sysgetdata =  $connect->prepare("SELECT name FROM systemsettings WHERE id=?");
                    $sysgetdata->bind_param("s", $myloc);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    $getsys = $dsysresult7->fetch_assoc();
                    $systemname=$getsys['name'];
                    $sysgetdata->close();
                    $password=Password_encrypt($password);
                    // creating user details
                    $status=1;
                    // generating user pub key
                     // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $public_key = createUniqueToken(29,"userwallet","wallettrackid","$systemname",true,true,true);
                    // generating user referal code
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $refcode=createUniqueToken(5,"users","refcode","",true,true,false);
                    $bal=0;
                    
                    
                       // Assign account officer
        $accountoffier_is=" ";
        $accountoffier_team=" ";

        $getAdmin = $connect->prepare("SELECT account_officer FROM users ORDER BY id DESC LIMIT 1");
        $getAdmin->execute();
        $result = $getAdmin->get_result();
        $getAc = $result->fetch_assoc();
        $last_ac_officer=$getAc['account_officer'];
        if(strlen($last_ac_officer)>2){
            // GET LAST ASSIGN DATA
            $assignnumber=0;
            $maxassignumber=0;
            $getAdmin = $connect->prepare("SELECT account_officer FROM marketers WHERE  track_id=?");
            $getAdmin->bind_param("s",$last_ac_officer);
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows>0){
                $getAc = $result->fetch_assoc();
                $assignnumber=$getAc['account_officer'];
            }
            
            // GET MAX ASSIGN NUMBER
            $getAdmin = $connect->prepare("SELECT account_officer FROM marketers ORDER BY account_officer DESC LIMIT 1");
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows){
                $getAc = $result->fetch_assoc();
                $maxassignumber=$getAc['account_officer'];
            }

            $Nextassignnumber=$assignnumber+1;
            while($Nextassignnumber<=$maxassignumber){
                $itsactive=1;
                $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE  account_officer=? AND status=?");
                $getAdmin->bind_param("si", $Nextassignnumber,$itsactive);
                $getAdmin->execute();
                $result = $getAdmin->get_result();
                if($result->num_rows>0){
                    $getAc = $result->fetch_assoc();
                    $accountoffier_is=$getAc['track_id'];
                    $accountoffier_team=$getAc['team_tag'];
                    break;
                }
                $Nextassignnumber++;
            }
            if(strlen($accountoffier_team)<2){
                $Nextassignnumber=1;
                while($Nextassignnumber<=$maxassignumber){
                    $itsactive=1;
                    $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE  account_officer=? AND status=?");
                    $getAdmin->bind_param("si", $Nextassignnumber,$itsactive);
                    $getAdmin->execute();
                    $result = $getAdmin->get_result();
                    if($result->num_rows>0){
                        $getAc = $result->fetch_assoc();
                        $accountoffier_is=$getAc['track_id'];
                        $accountoffier_team=$getAc['team_tag'];
                        break;
                    }
                    $Nextassignnumber++;
                }
            }

        }else{
            // pick number active
            $itsactive=1;
            $canassign=0;
            $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE status = ? AND account_officer>? ORDER BY id DESC");
            $getAdmin->bind_param("ii",$itsactive,$canassign);
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows>0){
                $getAc = $result->fetch_assoc();
                $accountoffier_is=$getAc['track_id'];
                $accountoffier_team=$getAc['team_tag'];
            }
        }
        
                    $insert_data = $connect->prepare("INSERT INTO users (email,fname,lname,password,username,userpubkey,status,phoneno,referby,refcode,bal,emailverified,account_officer,market_team_tag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data->bind_param("ssssssssssssss", $email, $firstname, $lastname, $password, $username, $public_key,$status,$phone,$referedby,$refcode,$bal,$status,$accountoffier_is,$accountoffier_team);
                    if($insert_data->execute()){
                        $insert_data->close();
                        
                        // getting the user id
                        $sysgetdata =  $connect->prepare("SELECT id FROM users WHERE email=?");
                        $sysgetdata->bind_param("s",$email);
                        $sysgetdata->execute();
                        $dsysresult = $sysgetdata->get_result();
                        $getsys = $dsysresult->fetch_assoc();
                        $last_id = $getsys['id'];
                        
                        // Creating defualt currencies for user
                        // getting the default currencies
                        $active=1;
                        $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencysystem WHERE defaultforusers=?");
                        $sysgetdata->bind_param("s",$active);
                        $sysgetdata->execute();
                        $dsysresult = $sysgetdata->get_result();
                        if($dsysresult->num_rows>0){
                            while($getsys = $dsysresult->fetch_assoc()){
                            $currencytag =	$getsys['currencytag'];
                            // generating wallet track id for user and assigning user the currencies
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $track_id=createUniqueToken(4,"userwallet","wallettrackid",$currencytag,true,true,true);
                            $insert_data = $connect->prepare("INSERT INTO `userwallet` (userid,currencytag,wallettrackid) VALUES (?,?,?)");
                            $insert_data->bind_param("sss", $last_id,$currencytag, $track_id);
                            $insert_data->execute();
                            $insert_data->close();
                            }
                        }
                        // saving user login session
                        $seescode = str_shuffle(time().(mt_rand(43, 615)));
                        $ipaddress= getIp();
                        $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
                            //Put sessioncode inside database
                        $dateloggedin= time();
                        $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Sessioncode,Date,Ipaddress,Browser) VALUES (?,?,?,?,?)");
                        $insert_data->bind_param("sssss", $email, $seescode, $dateloggedin, $ipaddress, $browser);
                        $insert_data->execute();
                        $insert_data->close();
                
                    
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
                        $accesstoken=getTokenToSendAPI($public_key,$companyprivateKey,$minutetoend,$serverName);
                        $maindata['access_token']=$accesstoken;
                        // saving user firebase notification token
                        $update_data = $connect->prepare("UPDATE users SET fcm=? WHERE id=?");
                        $update_data->bind_param("si", $fcm, $last_id);
                        $update_data->execute();
                        $update_data->close();
                
                        $subject =newlyRegisteredSubject($last_id);
                        $to = $email;
                        $messageText = newlyRegisteredText($last_id);
                        $messageHTML = newlyRegisteredHTML($last_id);
                        // send user email
                        sendUserMail($subject,$to,$messageText, $messageHTML);
                        sendUserSMS($phone,$messageText);
                        // $userid,$message,$type,$ref,$status
                        register_user_noti($last_id);
                        echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/pre-validation"; </script>';
                   } else{
                        header('location: ../auth/register.html?res=register-failed');
                    }
                    
                }
            else{
                $found = $dresult->fetch_assoc();
                //save fetcheced data inside session and proceed to dashboard
                $id= $user_id = $found['id'];
                $dash_mail = $found['email'];
                $emailverified =$found['emailverified'];
                $pass = $found['password'];
                $phone = $found['phoneno'];
                $dashunmae= $found['username'];
                $fa= $found['2fa'];
                $login2fa = $found['login_2fa'];
                $phone = $found['phoneno'];
                $userPubkey= $found['userpubkey'];
                $banreason = 'You have been Banned';
            
                $statusis=$found['status'];
                    if ($statusis==1) {
                        $maindata=[];
                    
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
                        
                                #To Check For 2FA Authentication
                            if ($login2fa == null || $login2fa == 0){
                                $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                                $maindata['access_token']=$accesstoken;
                                $maindata['verification']=$emailverified;
        
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
                                echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/dashboard/index"; </script>';
                            }
                            else{
                                $serverName="2FA_verification";
                                $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                                $maindata['access_token']=$accesstoken;
                                $maindata['verification']=$emailverified;
                                
                                # code...
                                $maindata['auth_factor'] = true;
                                
                                if($fa == "2" && $login2fa == 1){
                                    $maindata['token'] = "TYGJOHFUIIH";
                                    echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/otp?token=TYGJOHFUIIH"; </script>';
                                }
                                else if ($fa == "3" && $login2fa == 1){
                                     $maindata['token'] = "TYGJOHFHYUFUJ";
                                     echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/otp?token=TYGJOHFHYUFUJ"; </script>';
                                }
                                else if ($fa == "1" && $login2fa == 1){
                                    $maindata['token'] = "google";
                                    echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/google-otp"; </script>';
                                }
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
            session_destroy();
        }
        else{
    
            if (getenv('REQUEST_METHOD') == 'GET') {
                 $checkdata =  $connect->prepare("SELECT id,email,emailverified,password,2fa,username,userpubkey,phoneno,login_2fa,status FROM users WHERE email=? || username =? ");
            $checkdata->bind_param("ss", $email, $email);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            
            if ($dresult->num_rows == 0) {// checking if data is valid
                    #Get Post Data
                    $firstname = isset($data['givenName']) ? cleanme($data['givenName']) : '';
                    $lastname = isset($data['familyName']) ? cleanme($data['familyName']) : '';
                    $username = null;
                    $email = isset($data['email']) ? cleanme($data['email']) : '';
                    $phone = null;
                    $referedby = isset($data['referedby']) ? cleanme($data['referedby']) : '';
                    $fcm = isset($data['fcm']) ? cleanme($data['fcm']) : '';
                    $password = isset($data['id']) ? cleanme($data['familyName']."C1X",1) : '';
                    
                    
                    $fail=""; 
                
                    if (empty($email) || empty($firstname) || empty($lastname) || (empty($password)) ) {//checking if data is empty
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Please fill all data";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }   elseif (strlen($firstname)>25) {// checking if data is valid
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="First name can not be more than 25";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }  elseif (strlen($lastname)>25) {// checking if data is valid
                        $errordesc="Bad request";
                        $linktosolve="https://";
                        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Last name can not be more than 25";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }  
                    // getting system settings
                    $myloc=1;
                    $sysgetdata =  $connect->prepare("SELECT name FROM systemsettings WHERE id=?");
                    $sysgetdata->bind_param("s", $myloc);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    $getsys = $dsysresult7->fetch_assoc();
                    $systemname=$getsys['name'];
                    $sysgetdata->close();
                    $password=Password_encrypt($password);
                    // creating user details
                    $status=1;
                    // generating user pub key
                     // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $public_key = createUniqueToken(29,"userwallet","wallettrackid","$systemname",true,true,true);
                    // generating user referal code
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $refcode=createUniqueToken(5,"users","refcode","",true,true,false);
                    $bal=0;
                    
                       // Assign account officer
        $accountoffier_is=" ";
        $accountoffier_team=" ";

        $getAdmin = $connect->prepare("SELECT account_officer FROM users ORDER BY id DESC LIMIT 1");
        $getAdmin->execute();
        $result = $getAdmin->get_result();
        $getAc = $result->fetch_assoc();
        $last_ac_officer=$getAc['account_officer'];
        if(strlen($last_ac_officer)>2){
            // GET LAST ASSIGN DATA
            $assignnumber=0;
            $maxassignumber=0;
            $getAdmin = $connect->prepare("SELECT account_officer FROM marketers WHERE  track_id=?");
            $getAdmin->bind_param("s",$last_ac_officer);
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows>0){
                $getAc = $result->fetch_assoc();
                $assignnumber=$getAc['account_officer'];
            }
            
            // GET MAX ASSIGN NUMBER
            $getAdmin = $connect->prepare("SELECT account_officer FROM marketers ORDER BY account_officer DESC LIMIT 1");
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows){
                $getAc = $result->fetch_assoc();
                $maxassignumber=$getAc['account_officer'];
            }

            $Nextassignnumber=$assignnumber+1;
            while($Nextassignnumber<=$maxassignumber){
                $itsactive=1;
                $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE  account_officer=? AND status=?");
                $getAdmin->bind_param("si", $Nextassignnumber,$itsactive);
                $getAdmin->execute();
                $result = $getAdmin->get_result();
                if($result->num_rows>0){
                    $getAc = $result->fetch_assoc();
                    $accountoffier_is=$getAc['track_id'];
                    $accountoffier_team=$getAc['team_tag'];
                    break;
                }
                $Nextassignnumber++;
            }
            if(strlen($accountoffier_team)<2){
                $Nextassignnumber=1;
                while($Nextassignnumber<=$maxassignumber){
                    $itsactive=1;
                    $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE  account_officer=? AND status=?");
                    $getAdmin->bind_param("si", $Nextassignnumber,$itsactive);
                    $getAdmin->execute();
                    $result = $getAdmin->get_result();
                    if($result->num_rows>0){
                        $getAc = $result->fetch_assoc();
                        $accountoffier_is=$getAc['track_id'];
                        $accountoffier_team=$getAc['team_tag'];
                        break;
                    }
                    $Nextassignnumber++;
                }
            }

        }else{
            // pick number active
            $itsactive=1;
            $canassign=0;
            $getAdmin = $connect->prepare("SELECT track_id,team_tag FROM marketers WHERE status = ? AND account_officer>? ORDER BY id DESC");
            $getAdmin->bind_param("ii",$itsactive,$canassign);
            $getAdmin->execute();
            $result = $getAdmin->get_result();
            if($result->num_rows>0){
                $getAc = $result->fetch_assoc();
                $accountoffier_is=$getAc['track_id'];
                $accountoffier_team=$getAc['team_tag'];
            }
        }
        
                    $insert_data = $connect->prepare("INSERT INTO users (email,fname,lname,password,username,userpubkey,status,phoneno,referby,refcode,bal,emailverified,account_officer,market_team_tag) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data->bind_param("ssssssssssssss", $email, $firstname, $lastname, $password, $username, $public_key,$status,$phone,$referedby,$refcode,$bal,$status,$accountoffier_is,$accountoffier_team);
                    if($insert_data->execute()){
                        $insert_data->close();
                        
                        // getting the user id
                        $sysgetdata =  $connect->prepare("SELECT id FROM users WHERE email=?");
                        $sysgetdata->bind_param("s",$email);
                        $sysgetdata->execute();
                        $dsysresult = $sysgetdata->get_result();
                        $getsys = $dsysresult->fetch_assoc();
                        $last_id = $getsys['id'];
                        
                        // Creating defualt currencies for user
                        // getting the default currencies
                        $active=1;
                        $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencysystem WHERE defaultforusers=?");
                        $sysgetdata->bind_param("s",$active);
                        $sysgetdata->execute();
                        $dsysresult = $sysgetdata->get_result();
                        if($dsysresult->num_rows>0){
                            while($getsys = $dsysresult->fetch_assoc()){
                            $currencytag =	$getsys['currencytag'];
                            // generating wallet track id for user and assigning user the currencies
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $track_id=createUniqueToken(4,"userwallet","wallettrackid",$currencytag,true,true,true);
                            $insert_data = $connect->prepare("INSERT INTO `userwallet` (userid,currencytag,wallettrackid) VALUES (?,?,?)");
                            $insert_data->bind_param("sss", $last_id,$currencytag, $track_id);
                            $insert_data->execute();
                            $insert_data->close();
                            }
                        }
                        // saving user login session
                        $seescode = str_shuffle(time().(mt_rand(43, 615)));
                        $ipaddress= getIp();
                        $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
                            //Put sessioncode inside database
                        $dateloggedin= time();
                        $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Sessioncode,Date,Ipaddress,Browser) VALUES (?,?,?,?,?)");
                        $insert_data->bind_param("sssss", $email, $seescode, $dateloggedin, $ipaddress, $browser);
                        $insert_data->execute();
                        $insert_data->close();
                
                    
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
                        $accesstoken=getTokenToSendAPI($public_key,$companyprivateKey,$minutetoend,$serverName);
                        $maindata['access_token']=$accesstoken;
                        // saving user firebase notification token
                        $update_data = $connect->prepare("UPDATE users SET fcm=? WHERE id=?");
                        $update_data->bind_param("si", $fcm, $last_id);
                        $update_data->execute();
                        $update_data->close();
                
                        $subject =newlyRegisteredSubject($last_id);
                        $to = $email;
                        $messageText = newlyRegisteredText($last_id);
                        $messageHTML = newlyRegisteredHTML($last_id);
                        // send user email
                        sendUserMail($subject,$to,$messageText, $messageHTML);
                        sendUserSMS($phone,$messageText);
                        // $userid,$message,$type,$ref,$status
                        register_user_noti($last_id);
                        echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/pre-validation"; </script>';
                   } else{
                        header('location: ../auth/register.html?res=register-failed');
                    }
                    
                }
            else{
                $found = $dresult->fetch_assoc();
                //save fetcheced data inside session and proceed to dashboard
                $id= $user_id = $found['id'];
                $dash_mail = $found['email'];
                $emailverified =$found['emailverified'];
                $pass = $found['password'];
                $phone = $found['phoneno'];
                $dashunmae= $found['username'];
                $fa= $found['2fa'];
                $login2fa = $found['login_2fa'];
                $phone = $found['phoneno'];
                $userPubkey= $found['userpubkey'];
                $banreason = 'You have been Banned';
            
                $statusis=$found['status'];
                    if ($statusis==1) {
                        $maindata=[];
                    
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
                        
                                #To Check For 2FA Authentication
                            if ($login2fa == null || $login2fa == 0){
                                $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                                $maindata['access_token']=$accesstoken;
                                $maindata['verification']=$emailverified;
        
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
                                echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/dashboard/index"; </script>';
                            }
                            else{
                                $serverName="2FA_verification";
                                $accesstoken=getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName);
                                $maindata['access_token']=$accesstoken;
                                $maindata['verification']=$emailverified;
                                
                                # code...
                                $maindata['auth_factor'] = true;
                                
                                
                                if($fa == "2" && $login2fa == 1){
                                    $maindata['token'] = "TYGJOHFUIIH";
                                    echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/otp?token=TYGJOHFUIIH"; </script>';
                                }
                                else if ($fa == "3" && $login2fa == 1){
                                     $maindata['token'] = "TYGJOHFHYUFUJ";
                                     echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/otp?token=TYGJOHFHYUFUJ"; </script>';
                                }
                                else if ($fa == "1" && $login2fa == 1){
                                    $maindata['token'] = "google";
                                    echo '<script> window.localStorage.setItem("token", "'.$accesstoken.'"); window.location.href = "https://app.cardify.co/auth/google-otp"; </script>';
                                }
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
            // session_destroy();
    
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
        }
    }
}