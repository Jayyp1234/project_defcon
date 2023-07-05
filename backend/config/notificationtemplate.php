<?php
// DEPENDS ON MAIL TEMPLATE
// Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
function upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext){
    global $connect,$mainCardify_notificationBot;
    $code="";
    $userid= cleanme($userid,1);
    $message= showpost(cleanme($notificationtext,1));
    $type= cleanme($notificationtype,1);
    $ref= cleanme($trans_orderid,1);
    $status= cleanme($notificationstatus,1);
    $title =cleanme($notificationtitle,1);
    $body=cleanme($fcmtext,1);
    $code= createUniqueToken(6,"usernotification","notificationcode","",true,true,true);
     
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_notification_bot,id FROM admin WHERE 	telegram_notification_bot!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_notification_bot'];
    
    $sysgetdata =  $connect->prepare("SELECT email,username,fcm FROM users WHERE id=?");
    $sysgetdata->bind_param("s",$userid);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    // check if user is sending to himself
    $datais=$dsysresult7->fetch_assoc();
    $ussernamesenttomail=$datais['email'];
    $userusername=$datais['username'];
    $usersentfromfcm=$datais['fcm'];
        
    $botidtouse=$mainCardify_notificationBot;
    $keyboard= [];
    $response="*USER NOTIFICATION*\n\nUser: *$userusername*\nEmail: *$ussernamesenttomail*\n$message";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
    
    if($sendfcm==1&&strlen($usersentfromfcm)>3){
        sendPushNotification($usersentfromfcm, $title, $body);
    }
    
    
    $query = 'INSERT INTO usernotification (userid, notificationtext,notificationtype,orderrefid,notificationstatus,notificationcode,notificationtitle)  Values (?, ?, ?, ?, ?, ?,?)';
    $stmt = $connect->prepare($query);
    $stmt->bind_param("sssssss",$userid, $message,$type,$ref,$status,$code,$title);
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
}
// FUNCTIONS functions related to the users
// this functions gets user data 
function noti_getUserData($userid){
    //input type checks if its from post request or just normal function call
    global $connect;
    $alldata = [];

    $checkdata = $connect->prepare("SELECT  * FROM users  WHERE id=? || email = ?");
    $checkdata->bind_param("ss",$userid,$userid);
    $checkdata->execute();
    $getresultemail = $checkdata->get_result();
    if ($getresultemail->num_rows > 0) {
        $getthedata = $getresultemail->fetch_assoc();
        $alldata = $getthedata;
    }
    return $alldata;
}
//  this function gets user session log data
function noti_getUserSessionLog($username,$sessioncode){
    //input type checks if its from post request or just normal function call
    global $connect;
    $alldata = [];

    $checkdata = $connect->prepare("SELECT  * FROM usersessionlog WHERE username=? AND sessioncode=?");
    $checkdata->bind_param("ss",$username,$sessioncode);
    $checkdata->execute();
    $getresultemail = $checkdata->get_result();
    if ($getresultemail->num_rows > 0) {
        $getthedata = $getresultemail->fetch_assoc();
        $alldata = $getthedata;
    }
    return $alldata;
}
// NORAML USER ACTIVITIES
function  login_user_noti($userid,$sessioncode){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $usersseslog=noti_getUserSessionLog($usernameis,$sessioncode);
    //  `email`, `username`, `sessioncode`, `ipaddress`, `browser`, `date`, `status`, `location`, `inserttime`, `user_type`, `created_at`, `updated_at`
    
    $browser="";
    $ipaddress="";
    if(isset($usersseslog['browser'])){
        $browser=$usersseslog['browser'];
        $ipaddress=$usersseslog['ipaddress'];
    }

    $notificationtitle="Successful Login Notification.";
    $notificationtext="We noticed you just logged in. If this was not you, kindly chat with our support team. IP Address:$ipaddress Browser: $browser.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🔐 You just logged in";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  register_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Welcome to the Cardify family champ.";
    $notificationtext="Your Cardify account has been created successfully. You can now save, spend and exchange seamlessly.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  external_exchange_reg_user_noti($userid,$password){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $notificationtitle="Welcome to the Cardify family champ.";
    $notificationtext="Welcome to the Cardify family. We have created an account for you, kindly login to claim your account by signing in with the details below.Username: $usernameis Password: $password";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  kyc_approve_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="KYC Approved";
    $notificationtext="Hi $usernameis, Congratulations, your KYC documents and details have been confirmed.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="KYC Approved Successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  kyc_submitted_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="KYC Submitted";
    $notificationtext="Hi $usernameis, Congratulations, your KYC documents and details have been submitted.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="KYC Submitted Successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  update_profile_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Profile Updated";
    $notificationtext="Your Profile Has Been Updated Successfully.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  update_communication_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Settings Updated";
    $notificationtext="You have successfully updated your system settings.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  update_next_of_kin_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Next Of Kin Updated";
    $notificationtext="You have successfully updated your next of kin information.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  deleted_bank_acc_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Bank Account Deleted";
    $notificationtext="You Have Succefully Deleted a Bank Record.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  addNew_bank_acc_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Bank Account Added";
    $notificationtext="New bank record has been successfully added to your account.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  delete_credit_card_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Credit Card Removed";
    $notificationtext="You Have Succefully Deleted Your Credit Card.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=0;
    $fcmtext="";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  turn_off_2fa_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="2FA turned off";
    $notificationtext="You have successfully turned off your two factor authentication.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="You have successfully turned off your two factor authentication.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  turn_on_2fa_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="2FA turned on";
    $notificationtext="You have successfully turned on your two factor authentication.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="You have successfully turned oon your two factor authentication.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  change_password_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Password Changed";
    $notificationtext="You have successfully changed your password";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="You have successfully changed your password";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  change_pin_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="Pin Changed";
    $notificationtext="You have successfully changed your pin";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="You have successfully changed your pin";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  upgrade_user_level_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];
    $level =$userdsatas['userlevel'];

   
    $notificationtitle="Level Upgrade";
    $notificationtext="Hi $usernameis, Congratulations, you are now level $level verified.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="You are now level $level verified.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function  kyc_declined_user_noti($userid,$reason){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

   
    $notificationtitle="KYC Declined";
    $notificationtext="Hi $usernameis,  your kyc documents and details was rejected because of below reason:$reason";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="KYC Declined, kindly check your email for details.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
//USER VIRTUAL CARD ACTIVITIES
// notification when user create customer account is successful for BC customer verification (verified ID)
function  success_vc_cust_user_noti($userid){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];


    $notificationtitle="Successful ID Verification for Card Creation.";
    $notificationtext="Hi $usernameis, 🎉 Congratulations, your details have been verified. Proceed to create card";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="Congratulations, your details have been verified.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
// notification when user create customer account failed for BC customer verification (verified ID)

function  failed_vc_cust_user_noti($userid,$reason){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];


    $notificationtitle="ID Verification for Card Creation Failed.";
    $notificationtext="Hello $usernameis, Your identity details could not be verified for the following reason(s):$reason";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="ID Verification Failure, Check Your Email.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
// notification whenever a user spend from their virtual card
function spent_from_card_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['theusdval'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
    
    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="\$$transrealamt Spent from Card $last4 Balance";
    $notificationtext="You have just spent \$$transrealamt of your card $last4 balance.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💰 You have just spent \$$transrealamt of your card $last4 balance.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
//Notification to send user whenever the virtual card request for OTP
function  vc_3d_otp_user_noti($userid, $otp){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];


    $notificationtitle="Your Secure 3D OTP";
    $notificationtext="Hello $usernameis,Your virtual card's 3D secure OTP is $otp";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="Your virtual card's 3D secure OTP is $otp";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function cashback_withdraw_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['amttopay'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
  

    $notificationtitle="Cashback Sent Successfully";
    $notificationtext="Your Cashback withdrawal of $transrealamt is now sent to your bank account.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="Cashback Sent to your bank account successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function wallet_withdraw_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['amttopay'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
    $amount = number_format((float)$gttransdata['amttopay'], 2, '.', '');
    $currency = substr($gttransdata['currencytag'], 0, 3);

    $notificationtitle="$currency Withdrawal Successfully";
    $notificationtext="Your recent withdrawal of $amount $currency is now sent to your bank account.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💸 Withdrawal of $amount $currency successfully done.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
// Notification when user fund is refunded 
function refund_from_card_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['theusdval'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
    
    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="Failed Transaction and Refund";
    $notificationtext="An amount of \$$transrealamt has been refunded to your card ending in $last4.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🔙 \$$transrealamt has been refunded to your card ending in $last4";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function refund_from_fund_card_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['theusdval'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
    
    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    $last4 = $gtcarddata['last4'];
    $cardbrand = $gtcarddata['brand'];
    //    end of new details

    $notificationtitle="Funding Card Failed";
    $notificationtext="A refund of (\$$transrealamt) from your ($cardbrand) ending with $last4 has been successfully refunded to your Cardify NGN wallet due to current downtime on funding a card";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="\$$transrealamt refunded to your wallet, from your ($cardbrand) ending with $last4 funding.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function deactivate_card_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['theusdval'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
    
    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="Card Deactivated And Fund Moved";
    $notificationtext="An amount of \$$transrealamt has been refunded to your wallet for your card ending in $last4, and your card has been deactivated";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="\$$transrealamt has been refunded to your wallet for your card ending in $last4";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function fund_card_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['theusdval'];
    $virtualcard_tid=$gttransdata['wallettrackid'];
        
    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gtcarddata['brand'];
    $last4 = $gtcarddata['last4'];
    //    end of new details
    //    end of new details

    $notificationtitle="Card Funded";
    $notificationtext="Funded! Your Cardify $cardbrand $last4 has been funded with $transrealamt USD. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="Your Cardify $cardbrand $last4 has been funded with $transrealamt USD.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
// Notification when crpto is pending
function crypto_pend_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];

    $notificationtitle="$transcoinname Deposit";
    $notificationtext="The deposit of $transrealamt $transcoinname has received 0 out of 3 confirmations.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🚀 $transrealamt $transcoinname Deposit Incoming (0/3 Conf.)";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
// Notification when crpto is successful

function crypto_success_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];

    $notificationtitle="$transcoinname Deposit Confirmed";
    $notificationtext="The deposit of $transrealamt $transcoinname has received full confirmations.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🚀 $transrealamt $transcoinname Deposit Confirmed (3/3 Conf.)";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
// notification once bank transfer deposit is successfu
function bankdeposit_NGN_success_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];

    $amount = number_format((float)$gttransdata['amttopay'], 2, '.', '');
    $time = $gttransdata['confirmtime'];
    $currency = substr($gttransdata['currencytag'], 0, 3);

    $notificationtitle="$currency Deposit Confirmed";
    $notificationtext="Hello, $usernameis.This is a notification with regards to your recent deposit of $currency $amount to your Cardify wallet.Thank you for using Cardify.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🚀 $amount  $currency Deposit Confirmed";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function bill_topup_success_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];
    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $transrealamt = $gttransdata['amttopay'];
    $product_tid  = $gttransdata['bill_main_prodtid'];
    $billtypeis = $gttransdata['billtypeis'];
    $bill_product_no = $gttransdata['bill_product_no'];
    $bill_data_prodtid =$gttransdata['bill_data_prodtid'];
    // get name of voucher provide
    $provider_name = getColumnFromField("bill_top_up_main_products", "name", "product_trackid", $product_tid);
    $provider_name = ( $provider_name  )? $provider_name : "";
     $mailtext ="";
     $title="Bill top-up successfully completed.";
     $fcmtext="";
    if($billtypeis==1){//data
        $data_name = getColumnFromField("bill_data_provider", "name", "provider_tid", $bill_data_prodtid);
        $data_name = ( $data_name  )? $data_name : "";
        $mailtext = "Successful purchase of $provider_name ($data_name) for $transrealamt NGN to $bill_product_no was successful. Thanks you for using Cardify";
        $fcmtext="Successful purchase of $provider_name ($data_name)for $transrealamt NGN";
    }else{//airtime
        $mailtext = "Purchase of $transrealamt NGN worth of $provider_name to $bill_product_no was successful. Thanks you for using Cardify";
        $fcmtext="Purchase of $transrealamt NGN worth of $provider_name to $bill_product_no was successful";
    }

    $notificationtitle="$title";
    $notificationtext="$mailtext";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function crypto_sendout_success_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];

    $notificationtitle="$transcoinname Send out Confirmed";
    $notificationtext="The send out of $transrealamt $transcoinname has received full confirmations.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="$transrealamt $transcoinname Send Out Confirmed.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function crypto_sendout_pend_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transrealamt = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];

    $notificationtitle="$transcoinname Send Out Pending";
    $notificationtext="The send out of $transrealamt $transcoinname is pending";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="$transrealamt $transcoinname Send Out Pending.";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function swap_coin_user_noti($user_id,$transorderid,$swapmessage){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    $transswapto = $gttransdata['swapto'];//NGN to USDT
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=isset($coindata['name'])?$coindata['name']:'';

    $notificationtitle="Swap coin($transswapto)";
    $notificationtext="$swapmessage";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="$swapmessage";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function unload_vc_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];

    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gtcarddata['brand'];
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="Virtual Card Unloaded";
    $notificationtext="You have successfully unloaded your $cardbrand card that ends with $last4 on Cardify.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💳 📥 $cardbrand $last4 unloaded successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function delete_vc_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];

    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gtcarddata['brand'];
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="Virtual Card Deleted";
    $notificationtext="You have successfully deleted your $cardbrand card that ends with $last4 on Cardify.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🗑 $cardbrand $last4 deleted successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function fund_vc_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];
    $transrealamt = $gttransdata['theusdval'];

    $gtcarddata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gtcarddata['brand'];
    $last4 = $gtcarddata['last4'];
    //    end of new details

    $notificationtitle="Virtual Card Funded";
    $notificationtext="Funded! Your Cardify $cardbrand $last4 has been funded with $transrealamt USD. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="$cardbrand $last4 funded with $transrealamt USD successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function freeze_vc_user_noti($user_id,$virtualcard_tid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];
    $gttransdata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gttransdata['brand'];
    $last4 = $gttransdata['last4'];


    $notificationtitle="Virtual Card Frozen";
    $notificationtext="Your card $last4 frozen. Cardify: Just for you.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🧊 $cardbrand $last4 frozen successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function unfreeze_vc_user_noti($user_id,$virtualcard_tid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];
    $gttransdata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gttransdata['brand'];
    $last4 = $gttransdata['last4'];


    $notificationtitle="Virtual Card Unfrozen";
    $notificationtext="Your card $last4 unfrozen. Cardify: Just for you.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="$cardbrand $last4 unfrozen successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function create_vc_user_noti($user_id,$virtualcard_tid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];
    $gttransdata=mailgetVirtualCardData($user_id,$virtualcard_tid);
    //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $cardbrand = $gttransdata['brand'];
    $last4 = $gttransdata['last4'];


    $notificationtitle="Virtual Card Created";
    $notificationtext="Congratulations on your new card $last4. Cardify: Just for you.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💳✅$cardbrand  $last4 created successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function  success_generate_address_user_noti($userid,$coinname){
    $userdsatas= noti_getUserData($userid);
    // `email`, `fname`, `username`, `lname`, `password`, `phoneno`, `bal`, `refcode`, `referby`, `fcm`, `status`, `adminseen`, `userpubkey`, `created_at`, `updated_at`, `state`, `country`, `dob`, `sex`, `emailverified`, `phoneverified`, `address1`, `address2`, `nextkinfname`, `nextkinemail`, `nextkinpno`, `nextkinaddress`, ``, `securitynotification`, `transfernotification`, `userlevel`, `lastpassupdate`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];


    $notificationtitle="Generated $coinname Address";
    $notificationtext="Hi $usernameis. Your $coinname address has been generated on Cardify.";
    $trans_orderid="";
    $notificationtype="1";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="🔑 $coinname address generated";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$userid,$notificationstatus,$sendfcm,$fcmtext);
}
function internal_crypto_transfer_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $usernamesentto=$gttransdata['usernamesentto'];
    $amount = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];
  
    $notificationtitle="Fund Sent To User";
    $notificationtext="Sent! $amount $transcoinname sent to $usernamesentto successfully. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="📤💰 $amount $transcoinname sent to $usernamesentto successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function internal_crypto_receive_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];
    $usernamesentto=$gttransdata['usernamesentfrm'];
    $amount = $gttransdata['btcvalue'];
    $transcoindata= $gttransdata['cointrackid'];
    
    $coindata=getCoinDetails($transcoindata);
    $transcoinname=$coindata['name'];
  

  
    $notificationtitle="Fund Received From $usernamesentto";
    $notificationtext="Received! $amount $transcoinname sent from $usernamesentto  recieved successfully. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💵 $amount $transcoinname sent from $usernamesentto  recieved successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function internal_transfer_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];
    $usernamesentto=$gttransdata['usernamesentto'];
    $amount = number_format((float)$gttransdata['amttopay'], 2, '.', '');
    $time = $gttransdata['confirmtime'];
    $currency = substr($gttransdata['currencytag'], 0, 3);

  
    $notificationtitle="Fund Sent To User";
    $notificationtext="Sent! $amount $currency sent to $usernamesentto successfully. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💵 $amount $currency sent to $usernamesentto successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
function internal_receive_user_noti($user_id,$transorderid){
    $userdsatas= noti_getUserData($user_id);
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below
    $usernameis=$userdsatas['username'];

    $gttransdata=mailgetSingleUserTransWithOrderID($transorderid);
    // `userid`, `addresssentto`, `transhash`, `livetransid`, `orderid`, `ordertime`, `confirmtime`, `approvedby`, `status`, `liveusdrate`, `confirmation`, `syslivewallet`, `cointrackid`, `livecointype`, `addresssentfrm`, `btcvalue`, `theusdval`, `manualstatus`, `approvaltype`, `created_at`, `updated_at`, `ourrrate`, `amttopay`, `currencytag`, `transtype`, `virtualcardtrackid`
    //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
    $virtualcard_tid=$gttransdata['wallettrackid'];
    $usernamesentto=$gttransdata['usernamesentfrm'];
    $amount = number_format((float)$gttransdata['amttopay'], 2, '.', '');
    $time = $gttransdata['confirmtime'];
    $currency = substr($gttransdata['currencytag'], 0, 3);

  
    $notificationtitle="Fund Received From $usernamesentto";
    $notificationtext="Received! $amount $currency sent from $usernamesentto  recieved successfully. Thanks for using Cardify: Just for you.";
    $trans_orderid=$transorderid;
    $notificationtype="2";
    $notificationstatus="1";
    $sendfcm=1;
    $fcmtext="💵 $amount $currency sent from $usernamesentto  recieved successfully";
    // Notification ttext,notification title,orderid,notification type(1,2),userid,notification status,send fcm(0,1),fcm notification
    upgrade_add_notification($notificationtext,$notificationtitle,$trans_orderid,$notificationtype,$user_id,$notificationstatus,$sendfcm,$fcmtext);
}
?>