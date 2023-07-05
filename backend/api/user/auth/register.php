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

$endpoint="../../api/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') == 'POST') {
    #Get Post Data
    $firstname = isset($_POST['firstname']) ? cleanme($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? cleanme($_POST['lastname']) : '';
    $email = isset($_POST['email']) ? cleanme($_POST['email']) : '';
    $referedby = isset($_POST['referedby']) ? cleanme($_POST['referedby']) : '';
    $password = isset($_POST['password']) ? cleanme($_POST['password'],1) : '';
    $devicetype = isset($_POST['devicetype']) ? cleanme($_POST['devicetype'],1) : 'Web';
    $quarter= date("Y",time())."Q";

    if(date("m")==1||date("m")==2||date("m")==3){
       $quarter.="1"; 
    }else if(date("m")==6||date("m")==4||date("m")==5){
       $quarter.="2"; 
    }else if(date("m")==9||date("m")==7||date("m")==8){
       $quarter.="3"; 
    }else if(date("m")==10||date("m")==11||date("m")==12){
       $quarter.="4"; 
    }
    
    $fail=""; 
    
    // $phoneNumber = $phone;
    // $pattern = '/^(\+?234|0)?([789]\d{9})$/';
    // $replacement = '0$2';
    // $newPhoneNumber = preg_replace($pattern, $replacement, $phoneNumber);
    // $replacement = '$2';
    // $newPhoneNumber2 = preg_replace($pattern, $replacement, $phoneNumber);


    $checkdata =  $connect->prepare("SELECT id FROM users WHERE email = ?");
    $checkdata->bind_param("s", $email);
    $checkdata->execute();
    $dresult = $checkdata->get_result();
    
    
    
    if (empty($email) || empty($firstname) || empty($lastname) || empty($password)) {//checking if data is empty
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
    elseif (strlen($password)<8) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Password can not be less than 8 characters ";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
    elseif ($dresult->num_rows >0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Your email already exists.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Invalid email format";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } 
    elseif (isStringHasEmojis($firstname)||isStringHasEmojis($lastname)) {
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Emoji is not allowed in username, first name and last name";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
    }
    else{
        if($referedby=="undefined"){
            $referedby="";
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
        $public_key = createUniqueToken(29,"users","userpubkey","$systemname",true,true,true);
        // generating user referal code
        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
        $refcode=createUniqueToken(5,"users","refcode","",true,true,false);
        $bal=0;
        $empty = '';





        $insert_data = $connect->prepare("INSERT INTO users (`email`, `firstname`, `lastname`, `password`, `userpubkey`, `refcode`, `referby`, `status`, `adminseen`) VALUES (?,?,?,?,?,?,?,?,?)");
        $insert_data->bind_param("sssssssss", $email, $firstname, $lastname, $password, $public_key,$refcode,$empty,$status,$bal);
        if($insert_data->execute()){
        $insert_data->close();
        
        
        // getting the user id
        $sysgetdata =  $connect->prepare("SELECT id FROM users WHERE email=?");
        $sysgetdata->bind_param("s",$email);
        $sysgetdata->execute();
        $dsysresult = $sysgetdata->get_result();
        $getsys = $dsysresult->fetch_assoc();
        $last_id = $getsys['id'];
        
        // saving user login session
        // $seescode = str_shuffle(time().(mt_rand(43, 615)));
        // $ipaddress= getIp();
        // $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
        //     //Put sessioncode inside database
        // $dateloggedin= time();
        // $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Sessioncode,Date,Ipaddress,Browser) VALUES (?,?,?,?,?)");
        // $insert_data->bind_param("sssss", $email, $seescode, $dateloggedin, $ipaddress, $browser);
        // $insert_data->execute();
        // $insert_data->close();

    
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
        

        // $subject =newlyRegisteredSubject($last_id);
        // $to = $email;
        // $messageText = newlyRegisteredText($last_id);
        // $messageHTML = newlyRegisteredHTML($last_id);
        // // send user email
        // sendUserMail($subject,$to,$messageText, $messageHTML);
        // sendUserSMS($phone,$messageText);
        // // $userid,$message,$type,$ref,$status
        // register_user_noti($last_id);
        
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
        
       } else{
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["problem encountered while trying to send email"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Error creating user. Try again! $insert_data->error";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
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