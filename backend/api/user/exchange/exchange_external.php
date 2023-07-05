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
 
    $email = isset($_POST['email']) ? cleanme($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? cleanme($_POST['phone']) : '';
    $exchangetid = isset($_POST['exchangetid']) ? cleanme($_POST['exchangetid']) : '';
    $currencytid = isset($_POST['currencytid']) ? cleanme($_POST['currencytid']) : '';
    $accountname = isset($_POST['accountname']) ? cleanme($_POST['accountname']) : '';
    $bankcode = isset($_POST['bankcode']) ? cleanme($_POST['bankcode']) : '';
    $bankname = isset($_POST['bankname']) ? cleanme($_POST['bankname']) : '';
    $accountnumber = isset($_POST['accountnumber']) ? cleanme($_POST['accountnumber']) : '';
    
    $fcm ='';
    $referedby ='';
    $input="ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
    $breakemail=explode("@",$email);
    $firstname =$breakemail[0];
    $lastname = $breakemail[0];
    $username = $breakemail[0];
    $password =   generate_string($input, 7);

    $paymentid = '';
    $fail=""; 
    
    if (empty($email) || empty($firstname) || empty($lastname) || empty($username) || (empty($password)) || (empty($phone))  || (empty($accountnumber)) || (empty($bankname)) || (empty($bankcode)) || (empty($accountname)) || (empty($currencytid)) || (empty($exchangetid)) ) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
        exit;
    }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Invalid email format";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } 
    elseif (!preg_match("([0-9]+)", $phone)) { 
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Invalid phone number format";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }

    // checkif email exist
    $checkdata =  $connect->prepare("SELECT id,userpubkey FROM users WHERE email=? || phoneno=?");
    $checkdata->bind_param("ss", $email,$phone);
    $checkdata->execute();
    $dresult = $checkdata->get_result();
    if ($dresult->num_rows >0){
        $getsys = $dresult->fetch_assoc();
        $userid=$getsys['id'];
        $userPubkey=$getsys['userpubkey'];
        // check if user bank already exist and pick it id, if not add and pick id
        $getdataemail =  $connect->prepare("SELECT * FROM userbanks WHERE user_id=? AND account_no=? AND bankcode=? ");
        $getdataemail->bind_param("sss",$userid,$accountnumber,$bankcode);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if( $getresultemail->num_rows== 0){
            $fullname=$firstname." ". $lastname;
            $refcode =addUserToPayStack($fullname,$accountnumber,payGetBankcode($bankcode)['paystackbankcode']);
            $insert_data = $connect->prepare("INSERT INTO userbanks (user_id,bank_name,account_no,account_name,bankcode,refcode) VALUES (?,?,?,?,?,?)");
            $insert_data->bind_param("ssssss",$userid,$bankname,$accountnumber,$accountname,$bankcode,$refcode);
            if( $insert_data->execute()){
                $paymentid=$insert_data->insert_id;
                $insert_data->close();
            }
        }else{
            // pick ID
            $getsys = $getresultemail->fetch_assoc();
            $paymentid=$getsys['id'];
        }
        // generate access toekn
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
            
        // create exchange address
        $url ="https://app.cardify.co/api/user/exchange/generate_exchange_address.php";
        
        $arr = array(
            "exchangetid"=> $exchangetid,
            "paymentid"=> $paymentid,
            "currencytid"=> $currencytid,
            "exchangetype"=> 1,
        );
        // print_r($arr);
        //below is the base url
        // $params =  json_encode($arr);
        $params = http_build_query($arr);// to send post request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $accesstoken",
            "cache-control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
        ),
        ));
        $resp = curl_exec($curl);
        print_r($resp);
        $err = curl_error($curl);
        curl_close($curl);
        exit;
    }else{
    
    // create user account and send mail and genartae access token
    // save user bank details
    // call create exchange address api,add tag to know external exchange
    
    $checkdata =  $connect->prepare("SELECT id FROM users WHERE username=? ");
    $checkdata->bind_param("s", $username);
    $checkdata->execute();
    $dresult2 = $checkdata->get_result();
    
    if ($referedby != ""&&!empty($referedby)&&$referedby!="undefined"){
        $checkdata =  $connect->prepare("SELECT id FROM users WHERE refcode=? ");
        $checkdata->bind_param("s", $referedby);
        $checkdata->execute();
        $dresult4 = $checkdata->get_result();
        
    }
    if ($dresult2->num_rows >0) {// checking if data is valid
        $username=$username."1";
    }
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
        $mainpass=$password;
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
        $regmethod=3;
        $insert_data = $connect->prepare("INSERT INTO users (email,fname,lname,password,username,userpubkey,status,phoneno,referby,refcode,bal,reg_method) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $insert_data->bind_param("ssssssssssss", $email, $firstname, $lastname, $password, $username, $public_key,$status,$phone,$referedby,$refcode,$bal,$regmethod);
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

        $subject =externalExchangeSubject($last_id,$mainpass);
        $to = $email;
        $messageText = externalExchangeText($last_id,$mainpass);
        $messageHTML = externalExchangeHTML($last_id,$mainpass);
        // send user email
        sendUserMail($subject,$to,$messageText, $messageHTML);
        // $userid,$message,$type,$ref,$status
        external_exchange_reg_user_noti($last_id,$mainpass);
        
        // save bank account
        
        // genarte address
        $userid=$last_id;
        // check if user bank already exist and pick it id, if not add and pick id
        $getdataemail =  $connect->prepare("SELECT * FROM userbanks WHERE user_id=? AND account_no=? AND bankcode=? ");
        $getdataemail->bind_param("sss",$userid,$accountnumber,$bankcode);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if( $getresultemail->num_rows== 0){
            $fullname=$firstname." ". $lastname;
            $refcode =addUserToPayStack($fullname,$accountnumber,payGetBankcode($bankcode)['paystackbankcode']);
            $insert_data = $connect->prepare("INSERT INTO userbanks (user_id,bank_name,account_no,account_name,bankcode,refcode) VALUES (?,?,?,?,?,?)");
            $insert_data->bind_param("ssssss",$userid,$bankname,$accountnumber,$accountname,$bankcode,$refcode);
            if( $insert_data->execute()){
                $paymentid=$insert_data->insert_id;
                $insert_data->close();
            }
        }else{
            // pick ID
            $getsys = $getresultemail->fetch_assoc();
            $paymentid=$getsys['id'];
        }
        // generate access toekn
        // create exchange address
        $url ="https://app.cardify.co/api/user/exchange/generate_exchange_address.php";
        $arr = array(
            "exchangetid"=> $exchangetid,
            "paymentid"=> $paymentid,
            "currencytid"=> $currencytid,
            "exchangetype"=> 1,
        );
        // print_r($arr);
        //below is the base url
        // $params =  json_encode($arr);
        $params = http_build_query($arr);// to send post request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $accesstoken",
            "cache-control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
        ),
        ));
        $resp = curl_exec($curl);
        print_r($resp);
        $err = curl_error($curl);
        curl_close($curl);
        exit;
        
        
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
} else {
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