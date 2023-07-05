<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)

error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../../config/utilities.php";

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
?>
<?php
function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
    try{
        $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes / 1024;
        $size_in_mb    = $size_in_kb / 1024;

        return $size_in_kb;
    }
    catch(Exception $e){
        return $e;
    }
}

$maindata=[];
if (getenv('REQUEST_METHOD') == 'POST') {
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
        $errordesc="Bad request";
        $linktosolve="htps://";
        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="User is not in the database ensure the user is in the database";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
        
    } else{
        $user_id = getUserWithPubKey($connect, $user_pubkey);
    }

    $allowed =  array('jpg','jpeg','svg','png','gif');
    $userfacebook = $usertwitter= $userinstagram= $usertelegram=$imagename4="null";
    
        $data = json_decode(file_get_contents("php://input"));

    if (isset($data->regtype)) {
        $userregtype = cleanme($data->regtype);
    } else {
        $userregtype = '';
    }

    if (isset($data->passimage)) {
        $passimage = cleanme($data->passimage,1);
    } else {
        $passimage  = '';
    }
    if (isset($data->passimgname)) {
        $passimgname = cleanme($data->passimgname);
    } else {
        $passimgname = '';
    }

    if (isset($data->regimage)) {
        $regimage = cleanme($data->regimage,1);
    } else {
        $regimage  = '';
    }
    if (isset($data->regimgname)) {
        $regimgname = cleanme($data->regimgname);
    } else {
        $regimgname = '';
    }

   
    // 'image/gif', 
   $array_file_type = array('image/png', 'image/x-png', 'image/jpeg', 'image/pjpeg');
   $regarray=getimagesize("$regimage");
   $passarray=[];
   if(!empty($passimage)){
        $passarray=getimagesize("$passimage");
   }
    
    if(getBase64ImageSize($regimage) > 250){
         $fail="Regulation card File size must not be more than 250KB.";
    }else if(!isset($regarray['mime'])|| !in_array($regarray['mime'],$array_file_type) || explode("/",$regarray['mime'])[0]!="image"){
          $fail="Your Regulation card is not a valid image, only png, jpeg, jpg is allowed";
    }else if(getBase64ImageSize($passimage) > 250){
         $fail="Your Selfie File size must not be more than 250KB.";
    }else if(!isset($passarray['mime'])|| !in_array($passarray['mime'],$array_file_type) || explode("/",$passarray['mime'])[0]!="image"){
          $fail="Passport is not a valid image, only png, jpeg, jpg is allowed";
    }else if (empty($regimgname)||empty($passimgname)) {
          $fail="Please fill all required fields";
    }
 
    if ($fail=="") {
                    $getUser = $connect->prepare("SELECT * FROM users WHERE id= ?");
                    $getUser->bind_param("s",$user_id);
                    $getUser->execute();
                    $result = $getUser->get_result();
                    if($result->num_rows > 0){
                        //user exist
                        $row = $result->fetch_assoc();
                        $dashid = $row['id'];
                        $dashuname =$row['username'];
                        $phoneno=$row['phoneno'];
                        $email =$row['email'];
                    }
            
                    $checkdata =  $connect->prepare("SELECT * FROM kyc_details WHERE user_id=?");
                    $checkdata->bind_param("s",$user_id);
                    $checkdata->execute();
                    $dresult = $checkdata->get_result();
                    if ($dresult->num_rows > 0) {
                        $dta= $dresult->fetch_assoc();
                        $pimg=  $dta['passport'];
                        $front= $dta['front_regcard'];
                        $back = $dta['back_regcard'];
                        $business=  $dta['business_cc'];
                        
                        $rpathToThumbs = "../../../assets/images/userutility_bill/";
                        $rpathToThumbs2 = "../../../assets/images/userregulatorycards/";
                        if(!empty($pimg)){
                        deleteinFolder($pimg, $rpathToThumbs);
                        }
                        
                       
                        if(!empty($front)){
                        deleteinFolder($front ,$rpathToThumbs2);
                        }
                        
                        
                    }
                    $checkdata->close();
                    $target_dir = "../../../assets/images/imgholder/";
            
            
                    //optimization=
                    $rpathToImages = $target_dir;
                    $rpathToThumbs = "../../../assets/images/userutility_bill/";
                    $rthumbWidth = 520;
                    //optimization
                    $rpathToThumbs2 = "../../../assets/images/userregulatorycards/";
                    //optimization
                    $passimgarr= explode(',',$passimage);

                    $realImage = base64_decode($passimgarr[1]);
                    $imagename = cleanme(preg_replace("#[^a-z0-9.]#i", "", $dashuname.$passimgname));
                    file_put_contents($target_dir.$imagename, $realImage);
                    try {
                    createThumbsDynamic($rpathToImages, $rpathToThumbs, $rthumbWidth, $imagename);
                    }
                    catch(Exception $e) {
                    // echo 'Message: ' .$e->getMessage();
                    }
                    $regimagearr= explode(',',$regimage);
                    $realImage2 = base64_decode($regimagearr[1]);
                    $imagename2 = cleanme(preg_replace("#[^a-z0-9.]#i", "", $dashuname.$regimgname));
                    file_put_contents($target_dir.$imagename2, $realImage2);
                    try {
                    createThumbsDynamic($rpathToImages, $rpathToThumbs2, $rthumbWidth, $imagename2);
                    }catch(Exception $e) {
                    // echo 'Message: ' .$e->getMessage();
                    }
              


                    //delete first image
                    $dir = "../../../assets/images/imgholder/";
                    deleteinFolder($imagename, $dir);
                    deleteinFolder($imagename2, $dir);
                    $now=time();
                    $status=0;
                    $showadmin=2;
                  
                    
                    $update_data = $connect->prepare("UPDATE kyc_details SET   showadmin=?,utility_bills=?,front_regcard=?,reg_type=?,status=? WHERE 	user_id=?");
                    $update_data->bind_param("ssssss",$showadmin,  $imagename, $imagename2, $userregtype, $status,$dashid);
                    if ($update_data->execute()) {
                       
                                    $kyc=2;// pending $kyclevel=1;// 3-approved(fully added and valid) 1-partly added 2-pending (added via level 3 form)
                                    $update_data = $connect->prepare("UPDATE users SET kyclevel=? WHERE id=?");
                                    $update_data->bind_param("ss", $kyc, $dashid);
                                    $update_data->execute();
                                    $update_data->close();
                                    
                                    $subject = kycSubmittedSubject($user_id); 
                                    $to = $email;
                                    $messageText = kycSubmittedText($user_id);
                                    $messageHTML = kycSubmittedHTML($user_id);
                                    sendUserMail($subject,$to,$messageText, $messageHTML);
                                    sendUserSMS($phoneno,$messageText);
                                    // $userid,$message,$type,$ref,$status
                                    kyc_submitted_user_noti($user_id);
                                    
                                    $getUser = $connect->prepare("SELECT * FROM users WHERE id= ?");
                                    $getUser->bind_param("s",$dashid);
                                    $getUser->execute();
                                    $result = $getUser->get_result();
                                    //user exist
                                    $row = $result->fetch_assoc();
                                    $user_id = $row['id'];
                                    $email =$row['email'];
                                    $firstName = $row['fname'];
                                    $lastName = $row['lname'];
                                    $username =$row['username'];
                                    $fullname = $row['fname']." ".$row['lname'];
                                    $regtypetxt="";
                                    if($userregtype==1){
                                        $regtypetxt="National ID Card";
                                    }else if($userregtype==2){
                                        $regtypetxt="Drivers Lincense";
                                    }else if($userregtype==3){
                                       $regtypetxt ="Permanent Voter's Card";
                                    }else if($userregtype==4){
                                        $regtypetxt="International Passport";
                                    }
                                    
                                    // SEND TO TG
                                    $botToken = $mainKYCBotIdfor_telegram;
                                    $chatId = '-982449392';
                                    
                                    // URL for the Telegram Bot API
                                    $url = "https://api.telegram.org/bot$botToken/sendMediaGroup";
                                    $response="*KYC Verification*\n\nFull name: $fullname\nEmail: $email\nUsername: $username\nID Type:  $regtypetxt\n\n";
                                    // Send the media group
                                    $postFields = [
                                        'chat_id' =>$chatId,
                                        'media' => json_encode([
                                            ['type' => 'photo', 'media' => "attach://$imagename",'caption' => $response  ],
                                            ['type' => 'photo', 'media' => "attach://$imagename2",'caption' => $response  ],
                                        ]),
                                        "$imagename" => new CURLFile(realpath("../../../assets/images/userutility_bill/$imagename")),
                                        "$imagename2" => new CURLFile(realpath("../../../assets/images/userregulatorycards/$imagename2")),
                                    ];
                                    $curld = curl_init();
                                    curl_setopt($curld, CURLOPT_POST, true);
                                    curl_setopt($curld, CURLOPT_POSTFIELDS,  $postFields);
                                    curl_setopt($curld, CURLOPT_URL, $url);
                                    curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
                                    $output = curl_exec($curld);
                                    curl_close($curld);

                                    $finalchatid='-982449392';
                                    $finalbotid=$mainKYCBotIdfor_telegram;
                                    $keyboard = [
                                    'inline_keyboard' => [
                                        [
                                        ['text' => 'View Utility Bill', 'url' => "https://app.cardify.co/assets/images/userutility_bill/$imagename"],
                                         ['text' => 'View Gov ID', 'url' => "https://app.cardify.co/assets/images/userregulatorycards/$imagename2"],
                                        ],
                                              [
                                            ['text' => 'Reject', 'callback_data' => "0^$dashid"],
                                            ],
                                              [
                                            ['text' => 'Approve', 'callback_data' => "1^$dashid"],
                                            ],
                                    ],
                                    ];
                                    replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");   
                                    

                                    $errordesc = " ";
                                    $linktosolve = "htps://";
                                    $hint = [];
                                    $errordata = [];
                                    $text = "Form submitted successfully, kindly wait while we process your submission.";
                                    $method = getenv('REQUEST_METHOD');
                                    $status = true;
                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                    respondOK($data);
                        
                        // }
                        
                        
                    } else {
                        $errordesc = "Internal server error";
                        $linktosolve = "htps://";
                        $hint = ["System not saving user details into the KYC table"];
                        $errordata = returnError7003($errordesc, $linktosolve, $hint);
                        $text = "An error occured";
                        $method = getenv('REQUEST_METHOD');
                        $data = returnErrorArray($text, $method, $endpoint, $errordata);
                        respondInternalError($data);
                        $update_data->close();
                    }
                
    } else {
        $errordesc = "Bad request";
        $linktosolve = "htps://";
        $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
        $errordata = returnError7003($errordesc, $linktosolve, $hint);
        $text = $fail;
        $data = returnErrorArray($text, $method, $endpoint, $errordata);
        respondBadRequest($data);
    }
} else {
    $errordesc = "Method not allowed";
    $linktosolve = "htps://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>