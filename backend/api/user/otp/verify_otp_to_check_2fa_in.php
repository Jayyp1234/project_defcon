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
if (getenv('REQUEST_METHOD') === 'POST'){
    $detailsID =1;
        $getCompanyDetails = $connect->prepare("SELECT * FROM apidatatable WHERE id=?");
        $getCompanyDetails->bind_param('i', $detailsID);
        $getCompanyDetails->execute();
        $result = $getCompanyDetails->get_result();
        $companyDetails = $result->fetch_assoc();
        $companyprivateKey = $companyDetails['privatekey'];
        $minutetoend = $companyDetails['tokenexpiremin'];
        $serverName = $companyDetails['servername'];
        // $serverName="2FA_verification";
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
            $email = $row['email'];
            $username = $row['username'];
            $phoneno = $row['phoneno'];
             
             
             
            // getting system settings
            $myloc=1;
            $sysgetdata =  $connect->prepare("SELECT baseurl FROM systemsettings WHERE id=?");
            $sysgetdata->bind_param("s", $myloc);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys = $dsysresult7->fetch_assoc();
            $systembaseurl=$getsys['baseurl'];
            $sysgetdata->close();
            
            $getUser->close();
            
        }
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
        if(strlen($_POST['code']) <7){
            $errordesc="Pin required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input Valid OTP Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
      
        if(isset($_POST['code'])){
            $code = cleanme($_POST['code']);
        }
        
        if(isset($_POST['type'])) {
            $type = cleanme($_POST['type']);
        }
     
        if ($type == "2"){
            $identity = $email;
        } 
        else if ($type == "3"){
            $identity = $phoneno;
        }

        //check if empty('') return true
        if((isset($_POST['code']) && empty($code))){
            $errordesc="input cannot be empty";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input Username and OTP Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $seescode = str_shuffle(time().(mt_rand(43, 615)));
            $ipaddress= getIp();
            $location = getLoc($ipaddress);
            $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
            //Put sessioncode inside database
            $dateloggedin= time();
            $insert_data = $connect->prepare("INSERT INTO usersessionlog (Email,Username,Sessioncode,Date,Ipaddress,Browser, Location) VALUES (?,?,?,?,?,?,?)");
            $insert_data->bind_param("sssssss", $email,$username, $seescode, $dateloggedin, $ipaddress, $browser, $location);
            $insert_data->execute();
            $insert_data->close();
            
            //check if token exist
            $sql = "SELECT * FROM token WHERE useridentity = '$identity' and otp = '$code'";
            $getToken = $connect->prepare($sql);
            $getToken->execute();
            $result = $getToken->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_assoc();
                $otp = $row['otp'];
                $time = $row['time'];
                $id = $row['user_id'];
                $verifytype= $row['verifytype'];
                
                //then check expiry
                $expiredAt = time();
                if($time > time()){
                    $subject = loginmailSubject($user_id); 
                    $to = $email;
                    $messageText = loginMailText($user_id, $seescode);
                    $messageHTML = loginMailHTML($user_id, $seescode);
                    sendUserMail($subject,$to,$messageText, $messageHTML);
                    sendUserSMS($phoneno,$messageText);
                    
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
                    $accesstoken=getTokenToSendAPI($userpubkey,$companyprivateKey,$minutetoend,$serverName);
                    $maindata['access_token']=$accesstoken;
                    
                    # code...
                    $maindata=[$maindata];
                    $errordesc=" ";
                    $linktosolve="https://";
                    $hint=[];
                    $errordata=[];
                    $text="Login Successful";
                    $method=getenv('REQUEST_METHOD');
                    $status=true;
                    $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);


                }else{
                    //otp expired
                    $errordesc="OTP Expired";
                    $linktosolve="https://";
                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="The One-Time Password (OTP) you received has expired. Please click on the 'Resend' option to receive a new token.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);

                }
            }else{
                //invalid token
                $errordesc="Incorrect token";
                $linktosolve="htps://";
                $hint=["Input token sent to your email or phone","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Fill in valid token";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);

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

