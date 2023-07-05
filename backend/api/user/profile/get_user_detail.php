<?php
    // send some CORS headers so the API can be called from anywhere
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    // header("Access-Control-Max-Age: 3600");//3600 seconds
    // 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)

    
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);
    $maindata = []; 

    if (getenv('REQUEST_METHOD')== 'GET') {
        $detailsID =1;
        $getCompanyDetails = $connect->prepare("SELECT * FROM apidatatable WHERE id=?");
        $getCompanyDetails->bind_param('i', $detailsID);
        $getCompanyDetails->execute();
        $result = $getCompanyDetails->get_result();
        $companyDetails = $result->fetch_assoc();
        $companyprivateKey = $companyDetails['privatekey'];
        $minutetoend = $companyDetails['tokenexpiremin'];
        $serverName = $companyDetails['servername'];

        $decodeToken = ValidateAPITokenSentIN($serverName,$companyprivateKey,$method,$endpoint);
        $userpubkey = $decodeToken->usertoken;

        //get records from user and delivery address table
        //get user details
        $getUser = $connect->prepare("SELECT * FROM users WHERE userpubkey = ?");
        $getUser->bind_param("s",$userpubkey);
        $getUser->execute();
        $result = $getUser->get_result();

        if($result->num_rows > 0){
            //user exist
            $row = $result->fetch_assoc();
            $user_id = $row['id'];
            $email =$row['email'];
            $firstName = $row['fname'];
            $lastName = $row['lname'];
            $username = strtolower($row['username']);
            $fullname = $row['fname']." ".$row['lname'];
            $country = $row['country'];
            $phoneno = $row['phoneno'];
            $dob = $row['dob'];
            $gender = $row['sex'];
            $regmethod = $row['reg_method'];
            $state = $row['state'];
            $address1 = $row['address1'];
            $address2 = $row['address2'];
            $exchangebalance =round($row['exchangebalance'],2);
            $exchangependbal =round(empty($row['exchangependbal'])?0:$row['exchangependbal'],2);
            $nextkinfname = $row['nextkinfname'];
            $nextkinemail = $row['nextkinemail'];
            $nextkinphonenumber = $row['nextkinpno'];
            $nextkinaddress = $row['nextkinaddress'];
            $depositnotification = $row['depositnotification'];
            $securitynotification = $row['securitynotification'];
            $transfernotification = $row['transfernotification'];
            $userlevel = $row['userlevel'];
            $lastpasswordupdate = $row['lastpassupdate'];
            $emailverified =$row['emailverified'];
            $phoneverified =$row['phoneverified'];
            $refcode=$row['refcode'];
            $postalcode=$row['postalcode'];
            $city=$row['city'];
            $billngn=$row['billngnbal'];
            $billusd=$row['billusdbal'];
            $referby=$row['referby'];
            $activate_biometric=$row['activate_biometric'];
            $allow_deposit=$row['allow_deposit'];
            $all_withdrawal=$row['all_withdrawal'];
            $allow_swap=$row['allow_swap'];
            $display_cards=$row['display_cards'];
            $display_swap_options=$row['display_swap_options'];
            $display_bill_options=$row['display_bill_options'];
            $email_noti=$row['email_noti'];
            $sms_noti=$row['sms_noti'];
            $push_noti=$row['push_noti'];
            //  ``, ``, ``, ``, ``, ``
            
            
            $fa = $row['2fa'];
            if($fa==null||$fa=='0'||$fa==0){
               $fa=100; 
            }
            $googlefa = $row['login_2fa'];
            $pinadded=$row['pinadded'];
            $lastpinupdate =$row['lastpinupdate'];
            $kycLevel1=$row['kyclevel'];//0 not added yet, 1 basic added, 2 fulladded, 3 approved
            $created = $row['created_at'];
            $vc_card_verified= $row['vc_card_verified'];
            $vc_card_token =$row['vc_card_token'];
             
             $pinadded2=strlen($row['pin']);
             
            $count_profile = 0;
             $count_profile1 = 0;
            if (empty(trim($row['country']))){
                $count_profile++;
            }
            if (empty(trim($phoneno))){
                $count_profile++;
            }
            // if (empty(trim($dob))){
            //     $count_profile++;
            // }
            // if (empty(trim($gender))){
            //     $count_profile++;
            // }
            if (empty(trim($state))){
                $count_profile++;
            }
            if (empty(trim($address1))){
                $count_profile++;
                $count_profile1++;
            }
            if (empty(trim($city))){
                $count_profile++;
            }
            if (empty(trim($postalcode))){
                $count_profile++;
                $count_profile1++;
            }
            // profile update is now needed to get user postal code and city details
            
            //To Update User Level is User profile is completed and not updated, update user to level 
            // $phoneverified=1;
            // $count_profile=0;
            if ( $phoneverified != 0 && $pinadded!=0 && $pinadded!="0" && !empty($pinadded) && $username != null && $phoneno != null &&  $userlevel == 0 && $count_profile1 == 0 && $pinadded2>4){ 
            // if profile detail is cumulsory activate this
            // if ($count_profile == 0 && $pinadded!=0 &&  $userlevel == 0){
                $updatePassQuery = "UPDATE users SET  userlevel = 1 WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('i',$user_id);
                $updateStmt->execute();
                $userlevel=1;
                
                $subject = levelUpdatedSubject($user_id); 
                $to = $email;
                $messageText = levelUpdatedText($user_id);
                $messageHTML = levelUpdatedHTML($user_id);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($phoneno,$messageText);
                // $userid,$message,$type,$ref,$status
                upgrade_user_level_user_noti($user_id);

            }
            // update user to level 2
            if (($kycLevel1==1||$kycLevel1==3 ) &&  $count_profile == 0 &&  $userlevel == 1 && $fa && $fa!=100){
                $updatePassQuery = "UPDATE users SET  userlevel = 2 WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('i',$user_id);
                $updateStmt->execute();
                $userlevel=2;
                
                $subject = levelUpdatedSubject($user_id); 
                $to = $email;
                $messageText = levelUpdatedText($user_id);
                $messageHTML = levelUpdatedHTML($user_id);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($phoneno,$messageText);
                // $userid,$message,$type,$ref,$status
                upgrade_user_level_user_noti($user_id);
            }
            // update user to level 3
            if ($kycLevel1==3 &&  $userlevel == 2){
                $updatePassQuery = "UPDATE users SET  userlevel = 3 WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('i',$user_id);
                $updateStmt->execute();
                $userlevel=3;
                
                $subject = levelUpdatedSubject($user_id); 
                $to = $email;
                $messageText = levelUpdatedText($user_id);
                $messageHTML = levelUpdatedHTML($user_id);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($phoneno,$messageText);
                // $userid,$message,$type,$ref,$status
                upgrade_user_level_user_noti($user_id);
            }
            
            // getting system settings
            $myloc=1;
            $sysgetdata =  $connect->prepare("SELECT baseurl,intercomecode FROM systemsettings WHERE id=?");
            $sysgetdata->bind_param("s", $myloc);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys = $dsysresult7->fetch_assoc();
            $systembaseurl=$getsys['baseurl'];
            $intercomecode=$getsys['intercomecode'];
            $sysgetdata->close();
            $referallink=$systembaseurl."auth/register.html?ref=$refcode";
            $referallink=$systembaseurl."auth/register?ref=$username";
            $referallink="https://app.cardify.co/referral/index?username=$username";
            // intercom
            // $myuserht=hash_hmac('sha256',$user_id,$intercomecode);
            if(empty($username)){
                 $myuserht=hash_hmac('sha256',$user_id,$intercomecode);
            }else{
             $myuserht=hash_hmac('sha256',$username,$intercomecode);
            }
            
            $getUser->close();
            
            $negativeuser= check_if_user_is_a_scam($user_id);
            giveuserReferralBonus($user_id,$refcode,$username);
            
            if( $negativeuser==0){
                if($emailverified==1){
                        $maindata = [
                                "referallink"=>$referallink,
                                "keytag"=>$myuserht,
                                "referralcode"=>$refcode,
                                "referralcount"=> progress($refcode,$username),
                                "Referedby"=>$referby,
                                "Email"=>$email,
                                "Firstname"=>$firstName,
                                "Lastname"=>$lastName,
                                "Username"=>$username,
                                "Fullname"=>$fullname,
                                "billngn"=>$billngn,
                                "billusd"=>$billusd,
                                "Country"=>$country,
                                "DOB"=> $dob,
                                "phone" => $phoneno,
                                "Gender" => $gender,
                                "State" => $state,
                                "exchangebalance"=>$exchangebalance,
                                "exchangependbalance"=>$exchangependbal,
                                "Address1" => $address1,
                                "Address2" => $address2,
                                "next_of_kin_name" => $nextkinfname,
                                "next_of_kin_email" => $nextkinemail,
                                "next_of_kin_phoneno" => $nextkinphonenumber,
                                "next_of_kin_address" =>$nextkinaddress,
                                "depositnotification" => $depositnotification,
                                "securitynotification" => $securitynotification,
                                "transfernotification" => $transfernotification,
                                "user_level" => $userlevel,
                                "lastpasswordupdate" => $lastpasswordupdate,
                                "lastpinupdate"=>$lastpinupdate,
                                "is_profile_set"=> $count_profile == 0? true : false,
                                "is_phone_verified"=>$phoneverified,
                                "is_pin_added"=>$pinadded,
                                "kyclevel"=>$kycLevel1,
                                "fa"=>$fa,
                                "loginfa"=>$googlefa,
                                "id"=>$user_id,
                                "postalcode"=>$postalcode,
                                "city"=>$city,
                                "created_at"=>$created,
                                "regmethod"=>$regmethod,
                                "regmethod"=>$regmethod,
                                "card_verified"=>$vc_card_verified,
                                "activate_biometric"=>$activate_biometric,
                                "allow_deposit"=>$allow_deposit,
                                "all_withdrawal"=>$all_withdrawal,
                                "allow_swap"=>$allow_swap,
                                "display_cards"=>$display_cards,
                                "display_swap_options"=>$display_swap_options,
                                "display_bill_options"=>$display_bill_options,
                                "email_notification"=>$email_noti,
                                "sms_notification"=>$sms_noti,
                                "push_notification"=>$push_noti,
            
                        ];
                        $errordesc = " ";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $text = "User Details Fetched";
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                }else{
                    //pubkey does not exist
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["Ensure to send valid Userpubkey", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Unverified email";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            }else{
                $errordesc="Uauthorized";
                $linktosolve="htps://";
                $hint=["Check if all header values are sent correctly.","Ensure token has not expired","Regenerate token","Ensure the correct method is used","Token is case sensitve"];
                $errordata=returnError7001($errordesc,$linktosolve,$hint);
                $text="Unauthorized";
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondUnAuthorized($data);
            }
        }else {
            //pubkey does not exist
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid Userpubkey", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User Public Key does not exist";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }           

        
    }else{
        // method not allowed
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
<?php
function progress($data,$username){
    global $connect;
    $donation_count = mysqli_query($connect, "SELECT COUNT(id) AS num FROM users WHERE referby = '$data' OR referby='$username'");
    $donation_count = mysqli_fetch_assoc($donation_count);
    $donation_count = $donation_count['num'];
    return $donation_count;
}
?>