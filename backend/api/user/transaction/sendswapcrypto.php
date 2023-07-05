<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

//     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    require_once '../../../config/GoogleAuthenticator/vendor/autoload.php';
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
    // check if the right request was sent
    if ($method == 'POST') {
        // Get company private key
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

        // get if the user is a shop
        
        // send error if ur is not in the database
        if (!getUserWithPubKey($connect, $user_pubkey)){
            // send user not found response to the user
            $errordesc =  "Not Authorized";
            $linktosolve = 'https://';
            $hint = "Only authorized user allowed";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }

        $user_id = getUserWithPubKey($connect, $user_pubkey);
 

        if ( !isset($_POST['currency'])) {

            $errordesc="Currency is required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Currency is required";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $currency= cleanme($_POST['currency']);
        }

        if(check_if_user_has_done_trans_in1($user_id)==true){
            $errordesc="Please wait a moment before making another transaction to avoid double deduction from your account balance.";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Please wait a moment before making another transaction to avoid double deduction from your account balance.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        
        if ( !isset($_POST['wallettrackid'])) {

            $errordesc="Wallet track ID must be passed";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Wallet track ID must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $wallettrackid= cleanme($_POST['wallettrackid']);
        }

        if ( !isset($_POST['amttopay'])) {

            $errordesc="amttopay required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="amttopay must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $amttopay = sprintf('%.8f',floatval(cleanme($_POST['amttopay'])));
        }
        if ( !isset($_POST['type'])) {

            $errordesc="Amount required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Amount must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $type = cleanme($_POST['type']);// 1=username 2=swap
        }

         if ( $type==1){   
            if (!isset($_POST['username'])) {
    
                $errordesc="amttopay required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="amttopay must be passed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
    
            }else{
                $username = cleanme($_POST['username']);
            }
         }else{
             $username="null";
         }
        if ( $type==4){   
            if (!isset($_POST['address'])) {
    
                $errordesc="Address required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Address must be passed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
    
            }else{
                $address = cleanme($_POST['address']);
            }
            if (!isset($_POST['network_cointid'])) {
    
                $errordesc="Network is  required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Network must be selected";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
    
            }else{
                $network_cointid = cleanme($_POST['network_cointid']);
            }
            if (isset($_POST['memo'])&&!empty($_POST['memo'])) {
                $memo = cleanme($_POST['memo']);
            }else{
                $memo = "None";
            }
            if (isset($_POST['message'])&&!empty($_POST['message'])) {
                $message= cleanme($_POST['message']);
            }else{
                $message = "None";
            }
            // check pin and 2fa
            if (!isset($_POST['pin'])) {
    
                $errordesc="Pin required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Pin must be passed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
    
            }else{
                $pin = cleanme($_POST['pin']);
            }
            if (!isset($_POST['my2fa'])) {
    
                $errordesc="2FA required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="2FA must be passed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
    
            }else{
                $my2fa = cleanme($_POST['my2fa']);
            }
            
            // VALIDATE ADDRESS
            // GET COIN
            // btc 1,3,m,n,bc1
            // btc length 26-35 characters
            
            // TRC T
            // TRC length 34
            
            //  ERC 0x
            // ERC 42
            
            $checkdata =  $connect->prepare("SELECT * FROM coinaddress_validator WHERE cointrack_id=? ");
            $checkdata->bind_param("s",$network_cointid);
            $checkdata->execute();
            $dresultUser = $checkdata->get_result();
            if($dresultUser->num_rows > 0){
                $foundUser= $dresultUser->fetch_assoc();
                $minlen = $foundUser['min_len'];
                $maxlen = $foundUser['max_len'];
                $startwith = $foundUser['start_with'];
                $getstarters=explode(",",$startwith);
                $badstarted=false;
                if(strlen($address)<$minlen || strlen($address)>$maxlen ){
                    $badstarted=true;
                }
                
                $addressfirstletters=substr($address,0,1);
                $addresssecletters=substr($address,0,2);
                $addressthirdletters=substr($address,0,3);
                if(!in_array($addressfirstletters, $getstarters) && !in_array($addresssecletters, $getstarters) && !in_array($addressthirdletters, $getstarters)){
                    $badstarted=true;
                }
                if($badstarted==true){
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Invalid address, please check";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                }
            
            }

            $checkdata =  $connect->prepare("SELECT email,pin,kyclevel,userlevel,google_secret_key,2fa,phoneno FROM users WHERE id=? ");
            $checkdata->bind_param("s", $user_id);
            $checkdata->execute();
            $dresultUser = $checkdata->get_result();
            $foundUser= $dresultUser->fetch_assoc();
            $passpin = $foundUser['pin'];
            $userKycLevel= $foundUser['kyclevel'];
            $userlevel= $foundUser['userlevel'];
            $identity=$foundUser['email'];
            $identity2=$foundUser['phoneno'];
            $secret = $foundUser['google_secret_key'];
            $user2fa=$foundUser['2fa'];
            
            if(($user2fa==1 && empty($secret))||empty($user2fa)||$user2fa==0||is_null($user2fa)){
                 $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="You need to activate 2FA before you can send out";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            
             // check user pin
             $verifypass =check_pass($pin,$passpin);
             if (!$verifypass) {
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid pin.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            } 
            
            $query = 'SELECT name FROM systemsettings where id = 1';
            $stmt = $connect->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row =  mysqli_fetch_assoc($result);
            $companyname = $row['name'];
            if($user2fa==1){
                    $ga = new PHPGangsta_GoogleAuthenticator();
                    $qrCodeUrl = $ga->getQRCodeGoogleUrl($companyname, $secret);
                    $oneCode = $ga->getCode($secret);
                    $checkResult = $ga->verifyCode($secret, $oneCode, 2); 
                    if ($oneCode != $my2fa){
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Invalid 2FA code.";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }
            }else   if($user2fa==2){
                       // EMAIL OTP
                        $sql = "SELECT * FROM token WHERE useridentity = '$identity' and otp = '$my2fa'";
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
                            if($time < time()){
                                    $errordesc="Bad request";
                                    $linktosolve="htps://";
                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Invalid 2FA code.";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                            }
                        }else{
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Invalid 2FA code.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
            }else   if($user2fa==3){
                // phone number
                     $sql = "SELECT * FROM token WHERE useridentity = '$identity2' and otp = '$my2fa'";
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
                            if($time < time()){
                                    $errordesc="Bad request";
                                    $linktosolve="htps://";
                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Invalid 2FA code.";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                            }
                        }else{
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Invalid 2FA code.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
            }
            else{
                 $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="You need to activate 2FA before you can send out";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            
         }else{
                $address="null";
                $memo="null";
                $message ="null";
                $network_cointid="null";
                $pin ="null";
         }
   
        if (empty($amttopay)||empty($username)||empty($wallettrackid)||empty($currency)||empty($type)||empty($address)||empty($memo)||empty($message)||empty($network_cointid)){
            // Insert all fields
            $errordesc = "Insert all fields";
            $linktosolve = 'https://';
            $hint = "Kindly pass value to all the fields in this endpoint";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }else if ($amttopay<=0 || ! is_numeric($amttopay)){
            // Insert all fields
            $errordesc = "Invalid amount";
            $linktosolve = 'https://';
            $hint = "Invalid amount";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }else{
      

            $sysgetdata =  $connect->prepare("SELECT username,userlevel FROM users WHERE id=?");
            $sysgetdata->bind_param("s", $user_id);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            // check if user is sending to himself
            $datais=$dsysresult7->fetch_assoc();
            $usernamesentfrm=$datais['username'];
            $useruserlevel=$datais['userlevel'];
            
            if( $useruserlevel>=2){    
                // check if type and currecny relate
                // preventing the use of another payment method to pay for another currency
                // api is only for subwallet
                if($type==1){
                $active=1;
                $mainorsubwallet=2;
                $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencywithdrawmethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=?");
                $sysgetdata->bind_param("ssss", $currency,$type,$active,$mainorsubwallet);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->num_rows;
                }
                if($type==3){
                $active=1;
                $mainorsubwallet=2;
                $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencywithdrawmethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=? AND swapmethod=?");
                $sysgetdata->bind_param("sssss", $currency,$type,$active,$mainorsubwallet,$active);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->num_rows;
                }
                if($type==4){
                    $active=1;
                    $swapmethod=0;
                    $mainorsubwallet=2;
                    $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencywithdrawmethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=? AND swapmethod=?");
                    $sysgetdata->bind_param("sssss", $currency,$type,$active,$mainorsubwallet,$swapmethod);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    $getsys = $dsysresult7->num_rows;
                }
                 // preventing funding with out the currecncy trackid
                $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal,coinsystrackid,coinplatform FROM usersubwallet WHERE currencytag=? AND trackid=? AND userid=?");
                $sysgetdata->bind_param("sss", $currency,$wallettrackid,$user_id);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys2 = $dsysresult7->num_rows;
                if($getsys>0&&$getsys2>0){
                //   if($getsys2>0){
                    $getuserdata= $dsysresult7->fetch_assoc();
                    $walletbal=$getuserdata['walletbal'];
                    $coinprodtrackidis=$getuserdata['coinsystrackid'];
                    $coinplatform=$getuserdata['coinplatform'];
                      // check if user have enough balance
                           if ($type==1){      //send to user
                                $coindata=getCoinDetails($coinprodtrackidis);
                                 $subwallettag=$coindata['subwallettag'];
                                 $coinname=$coindata['name'];
                                 $securitydata=getSendCoin_Security_Level($subwallettag,$useruserlevel);
                                 $coinminimum_to_send=$securitydata['minimum_to_send']+0;
                                 
                                 
                                $haveneversent=1;
                                $received=2;
                                $yescryp=1;
                                $successdone=1;
                                $getexactdata =  $connect->prepare("SELECT btcvalue FROM userwallettrans WHERE transtype=? AND iscrypto=? AND status=? AND userid=? AND livetransid IS NOT NULL LIMIT 1");
                                $getexactdata->bind_param("ssss",$received,$yescryp,$successdone,$user_id);
                                $getexactdata->execute();
                                $rresult2 = $getexactdata->get_result();
                                $totaldone_aweekis = $rresult2->num_rows;
                                if ($totaldone_aweekis>0) {
                                    $haveneversent=0;
                                }
                                 
                              
                                 
                                if($amttopay>=$coinminimum_to_send){
                                    if($haveneversent==0){
                                      if($walletbal>=$amttopay){
                        
                                            $sysgetdata =  $connect->prepare("SELECT username FROM users WHERE id=? AND (email=? or username=?)");
                                            $sysgetdata->bind_param("sss", $user_id,$username,$username);
                                            $sysgetdata->execute();
                                            $dsysresult7 = $sysgetdata->get_result();
                                            // check if user is sending to himself
                                                if($dsysresult7->num_rows==0){
                                                 // check if user send or email is valid
                                                        $user_recieve_id = ConfirmEmailXUsername($connect, $username);
                                                        $sysgetdata =  $connect->prepare("SELECT userlevel FROM users WHERE id=?");
                                                        $sysgetdata->bind_param("s", $user_recieve_id);
                                                        $sysgetdata->execute();
                                                        $dsysresult7 = $sysgetdata->get_result();
                                                        // check if user is sending to himself
                                                        $datais=$dsysresult7->fetch_assoc();
                                                        $usermainlevel2 =$datais['userlevel'];
                                                        if($usermainlevel2<2){
                                                                $errordesc="Please be informed that the recipient of your internal transfer must have a minimum level 2 access to receive the transfer.";
                                                                $linktosolve="https://";
                                                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Please be informed that the recipient of your internal transfer must have a minimum level 2 access to receive the transfer.";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data); 
                                                        }else{
                                                                        if ($user_recieve_id!=false){
                                                                            // Internal transfer
                                                                                // deduct user fund
                                                                                if(payRemoveUserSubBalance($user_id,$amttopay,$currency,$wallettrackid)){
                                                                                    $coindata=getCoinDetails($coinprodtrackidis);
                                                                                    $coinname=$coindata['name'];
                                                                                    $livecointypeis=$coindata['cointype'];
                                                                                    $subwallettag = $coindata['subwallettag'];
                                                                                    
                                                                                    // add new transaction
                                                                                    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                                    $transhash = '';
                                                                                    // generating  order ref
                                                                                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                    // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","IT",true,true,true);
                                                                                    $reference= $orderId = createTransUniqueToken("CIT", $user_id);
                                                                                    $ordertime = date("h:ia, d M");
                                                                                    $confirmtime = '';
                                                                                    $status = 0; 
                                                                                    $amttopay = $amttopay;
                                                                                    $addresssentto = '';
                                                                                    $manualstatus = 0;
                                                                                    $currencytag = $currency;
                                                                                    $approvaltype = 1;
                                                                                    $message1 = "Sent $amttopay $coinname with Internal transfer";
                                                                                    // insert the values to the transation for recieve
                                                                                    $transtype1 = 1;
                                                                                    $yes=1; 
                                                                                    $comfirmation=1;
                                                                                    $systemsendwith=1;
                                                                                    $empty=" ";
                                                                                    $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,confirmation,livecointype,btcvalue,cointrackid,  iscrypto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,usernamesentfrm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                                    $addTransaction1 = $connect->prepare($query1);
                                                                                    $addTransaction1 ->bind_param("ssssssssssssssssssssssss",$empty,$systemsendwith,$comfirmation,$livecointypeis,$amttopay,$coinprodtrackidis,$yes,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username,$usernamesentfrm);
                                                                                    if ($addTransaction1->execute()){
                                                                                            
                                                                                            
                                                                                             // get send to user details and wallet data
                                                                                            $sysgetdata =  $connect->prepare("SELECT trackid FROM usersubwallet WHERE userid=? AND currencytag=? AND coinsystemtag=?");
                                                                                            $sysgetdata->bind_param("sss", $user_recieve_id,$currency,$subwallettag);
                                                                                            $sysgetdata->execute();
                                                                                            $dsysresult7 = $sysgetdata->get_result();
                                                                                            // check if user is sending to himself
                                                                                            if($dsysresult7->num_rows>0){
                                                                                                    $getsenttodata=$dsysresult7->fetch_assoc();
                                                                                                    $senttowallettrackid=$getsenttodata['trackid'];
                                                                                                    // credit the user sent to
                                                                                                    if(payAddUserSubBalance($user_recieve_id,$amttopay,$currency,$senttowallettrackid)){
                                                                                                        // add new transction for user sent to
                                                                                                        // add new transaction
                                                                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                                                        $transhash = '';
                                                                                                        // generating  order ref
                                                                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                                        // $orderId = createUniqueToken(18,"userwallettrans","orderid","IR",true,true,true);
                                                                                                        $orderId = createTransUniqueToken("CIR", $user_recieve_id);
                                                                                                            // generating  token
                                                                                                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                                        $companypayref = createUniqueToken(16,"userwallettrans","paymentref","IPR",true,true,false);
                                                                                                        $ordertime = date("h:ia, d M");
                                                                                                        $confirmtime = date("h:ia, d M");
                                                                                                        $status = 1; 
                                                                                                        $amttopay = $amttopay;
                                                                                                        $addresssentto = '';
                                                                                                        $manualstatus = 0;
                                                                                                        $currencytag = $currency;
                                                                                                        $approvaltype = 1;
                                                                                                        $message2 = "Received $amttopay $coinname with Internal transfer";
                                                                                                        // insert the values to the transation for recieve
                                                                                                        $transtype1 = 2;
                                                                                                        $approvedby="Automation";
                                                                                                        $yes=1;
                                                                                                        $comfirmation=1;
                                                                                                        $systemsendwith=1;
                                                                                                        $empty=" ";
                                                                                                        $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,confirmation,livecointype,btcvalue,cointrackid, iscrypto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,approvedby,usernamesentfrm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                                                        $addTransaction1 ->bind_param("sssssssssssssssssssssssss",$empty,$systemsendwith,$comfirmation,$livecointypeis,$amttopay,$coinprodtrackidis,$yes,$user_recieve_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message2,$ordertime,$confirmtime,$companypayref,$status,$senttowallettrackid,$username,$approvedby,$usernamesentfrm);
                                                                                                        $addTransaction1->execute();
                                                                                                            
                                                                                                        notify_admin_noti_b_bot($message2,$user_recieve_id);
                                                                                                        notify_admin_noti_b_bot($message1,$user_id);
                                                                                                        // update user that sent trans status 
                                                                                                        
                                                                                                        $valid=true; 
                                                                                                        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                                                                                                        $bankpaidwith=1;
                                                                                                        $systempaidwith=0;
                                                                                                        $response=$paystackref=$paymenttoken="";
                                                                                                        $paystatus=1;
                                                                                                        $status = 1;
                                                                                                        $time = date("h:ia, d M");
                                                                                                        $approvedby="Automation";
                                                                                                        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                                                                                                        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
                                                                                                        $checkdata->execute();
                                                                                                        
                                                                                                        
                                                                                                         giveMarketerPointForEachUsers($user_id,1,$orderId);
                                                                                                        $data = [];
                                                                                                        $text= "$message1";
                                                                                                        $status = true;
                                                                                                        $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                                        respondOK($successData);
                                                                                                  
                                                                                                    }else{
                                                                                                        // return user money and set transstatus to 3
                                                                                                       payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                                        payTransCancled($orderId);
                                                                                                        // Insert all fields
                                                                                                        $errordesc = "Opps , sorry Unable to Credit user";
                                                                                                        $linktosolve = 'https://';
                                                                                                        $hint = "Opps , sorry Unable to Credit user";
                                                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                                        respondBadRequest($data);  
                                                                                                    }
                                                                                            }else{
                                                                                                // create wallet for user
                                                                                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                                $trackcode=createUniqueToken(5,"usersubwallet","trackid","",true,true,false);
                                                                                                $insert_data = $connect->prepare("INSERT INTO usersubwallet (currencytag,trackid,userid,coinplatform,coinsystrackid,coinsystemtag) VALUES (?,?,?,?,?,?)");
                                                                                                $insert_data->bind_param("ssssss", $currency,$trackcode,$user_recieve_id,$coinplatform,$coinprodtrackidis,$subwallettag);
                                                                                                $insert_data->execute();
                                                                                                $insert_data->close();
                                                                                                
                                                                                                
                                                                                                  if(payAddUserSubBalance($user_recieve_id,$amttopay,$currency,$trackcode)){
                                                                                                        // add new transction for user sent to
                                                                                                        // add new transaction
                                                                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                                                        $transhash = '';
                                                                                                        // generating  order ref
                                                                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                                        // $orderId = createUniqueToken(18,"userwallettrans","orderid","IR",true,true,true);
                                                                                                        $orderId = createTransUniqueToken("CIR", $user_recieve_id);
                                                                                                            // generating  token
                                                                                                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                                        $companypayref = createUniqueToken(16,"userwallettrans","paymentref","IPR",true,true,false);
                                                                                                        $ordertime = date("h:ia, d M");
                                                                                                        $confirmtime = date("h:ia, d M");
                                                                                                        $status = 1; 
                                                                                                        $amttopay = $amttopay;
                                                                                                        $addresssentto = '';
                                                                                                        $manualstatus = 0;
                                                                                                        $currencytag = $currency;
                                                                                                        $approvaltype = 1;
                                                                                                        $message2 = "Received $amttopay $coinname with Internal transfer";
                                                                                                        // insert the values to the transation for recieve
                                                                                                        $transtype1 = 2;
                                                                                                        $approvedby="Automation";
                                                                                                        $yes=1;
                                                                                                        $systemsendwith=1;
                                                                                                        $empty=" ";
                                                                                                        $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,iscrypto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,approvedby,usernamesentfrm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                                                        $addTransaction1 ->bind_param("sssssssssssssssssssss",$empty,$systemsendwith,$yes,$user_recieve_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message2,$ordertime,$confirmtime,$companypayref,$status,$senttowallettrackid,$username,$approvedby,$usernamesentfrm);
                                                                                                        $addTransaction1->execute();
                                                                                                            
                                                                                                        // update user that sent trans status 
                                                                                                        
                                                                                                        $valid=true; 
                                                                                                        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                                                                                                        $bankpaidwith=1;
                                                                                                        $systempaidwith=0;
                                                                                                        $response=$paystackref=$paymenttoken="";
                                                                                                        $paystatus=1;
                                                                                                        $status = 1;
                                                                                                        $time = date("h:ia, d M");
                                                                                                        $approvedby="Automation";
                                                                                                        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                                                                                                        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
                                                                                                        $checkdata->execute();
                                                                                                              notify_admin_noti_b_bot($message2,$user_recieve_id);
                                                                                                        
                                                                                                        
                                                                                                        // sms mail noti for who sent
                                                                                                        $subject = paymentSuccessSubject($user_id,$reference); 
                                                                                                        $to =$ussernamesentfrommail;
                                                                                                        $messageText = paymentSuccessfullText($user_id, $reference);
                                                                                                        $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                                                        sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                                        sendUserSMS($usersentfromphone,$messageText);
                                                                                                        // $userid,$message,$type,$ref,$status
                                                                                                        internal_crypto_transfer_user_noti($user_id,$reference);
                                                
                                                                                                        // sms mail noti for who receive
                                                                                                        $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                                                                                                        $sysgetdata->bind_param("s", $user_recieve_id);
                                                                                                        $sysgetdata->execute();
                                                                                                        $dsysresult7 = $sysgetdata->get_result();
                                                                                                        // check if user is sending to himself
                                                                                                        $datais=$dsysresult7->fetch_assoc();
                                                                                                        $ussernamesenttomail=$datais['email'];
                                                                                                        $usersenttophone=$datais['phoneno'];
                                        
                                                                                                        $subject = receivedPaymentSubject($user_recieve_id,$orderId); 
                                                                                                        $to = $ussernamesenttomail;
                                                                                                        $messageText = receivedPaymentText($user_recieve_id, $orderId);
                                                                                                        $messageHTML = receivedPaymentHTML($user_recieve_id, $orderId);
                                                                                                        sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                                        sendUserSMS($usersenttophone,$messageText);
                                                                                                        // $userid,$message,$type,$ref,$status
                                                                                                        internal_crypto_receive_user_noti($user_recieve_id,$orderId);
                                                                                        
                                                                                        giveMarketerPointForEachUsers($user_id,1,$orderId);
                                                                                                        $data = [];
                                                                                                        $text= "$message1";
                                                                                                        $status = true;
                                                                                                        $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                                        respondOK($successData);
                                                                                                  
                                                                                                    }else{
                                                                                                        // return user money and set transstatus to 3
                                                                                                        payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                                        payTransCancled($orderId);
                                                                                                        // Insert all fields
                                                                                                        $errordesc = "Oh sorry, Unable to Credit user, try again later";
                                                                                                        $linktosolve = 'https://';
                                                                                                        $hint = "Oh sorry, Unable to Credit user, try again later";
                                                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                                        respondBadRequest($data);  
                                                                                                    }
                                                                                            }
                                                                                    }else{
                                                                                        // return user money
                                                                                       payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                        
                                                                                        // send db error
                                                                                        $errordesc =  $addTransaction1->error;
                                                                                        $linktosolve = 'https://';
                                                                                        $hint = "500 code internal error, check ur database connections";
                                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                        respondInternalError($data);
                                                                                    }
                                                                                }else{
                                                                                    // Insert all fields
                                                                                    $errordesc = "Unable to deduct fund";
                                                                                    $linktosolve = 'https://';
                                                                                    $hint = "Unable to deduct fund";
                                                                                    $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                    $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                    respondBadRequest($data); 
                                                                                }
                                                                       
                                                                             
                                                                        } else{
                                                                            $errordesc="User Does not Exist";
                                                                            $linktosolve="https://";
                                                                            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                            $text="User does not exist, please confirm and try again later";
                                                                            $method=getenv('REQUEST_METHOD');
                                                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                            respondBadRequest($data);
                                                            }
                                                        }
                                                        
                                                 }else{
                                                    $errordesc="Sending to yourself is not allowed";
                                                    $linktosolve="https://";
                                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="ending to yourself is not allowed";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                                  }
                                        }else{
                                            $errordesc="Insufficient Balance";
                                            $linktosolve="https://";
                                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Your balance is too low for the transaction to be processed";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
                                    }else{
                                        $errordesc="BAD PAY METHOD";
                                        $linktosolve="https://";
                                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                        $text="Sorry, a security error occured, try again later";
                                        $method=getenv('REQUEST_METHOD');
                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                        respondBadRequest($data); 
                                    }
                                }else{
                                                     $errordesc="BAD PAY METHOD";
                                                    $linktosolve="https://";
                                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="The minimum you can send is $coinminimum_to_send $coinname";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data); 
                                }
                                  
                            }else if($type==3){//swap
                                // convert usd to cointype
                                exit;
                                $coindata=getCoinDetails($coinprodtrackidis);
                                $getlivevalu=0;  
                                $coinname=$coindata['name'];
                                $coinrate=$coindata['rate'];
                                $livevale =  $coindata['liveratefunctions'];  
                                $coinplatform = $coindata['coinplatform'];
                                $cointype =$coindata['cointype'];
                                // get currency details $currency 
                           
                                $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis); 
                                // check if balance reach
                              if( $getlivevalu!=0){
                               
                                if($walletbal>=$amttopay){
                                       $amttopayusd=$amttopay * $getlivevalu;
                                        // subtract bal
                                                  if(payRemoveUserSubBalance($user_id,$amttopay,$currency,$wallettrackid)){
                                                        $nairavalueis=$coinrate*$amttopayusd;
                                                        $amttopaycoin=$amttopay;
                                                        $ngntag="NGNT55";
                                                        $sysgetdata =  $connect->prepare("SELECT wallettrackid FROM userwallet WHERE userid=? AND currencytag=?");
                                                        $sysgetdata->bind_param("ss", $user_id,$ngntag);
                                                        $sysgetdata->execute();
                                                        $dsysresult7 = $sysgetdata->get_result();
                                                        $datais=$dsysresult7->fetch_assoc();
                                                        $ngnwallettrackid=$datais['wallettrackid'];
                                                        
                                                       
                                                        if(payAddUserBalance($user_id,$nairavalueis,$ngntag,$ngnwallettrackid)){
                                                        // convert usd to naira
                                                        // add fund to use balance
                                                        // add new transaction
                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                        $transhash = '';
                                                        // generating  order ref
                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                        // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","SWAP",true,true,true);
                                                        $reference= $orderId = createTransUniqueToken("SWAP", $user_id);
                                                        $ordertime = date("h:ia, d M");
                                                        $confirmtime = '';
                                                        $status = 1; 
                                                        $username="";
                                                        $accountsentto="";
                                                        $amttopay = $nairavalueis;
                                                        $addresssentto = '';
                                                        $manualstatus = 0;
                                                        $currencytag = "NGNT55";
                                                        $approvaltype = 1;
                                                        $message1 = "Convert $amttopaycoin $coinname to naira";
                                                        // insert the values to the transation for recieve
                                                        $transtype1 = 2;
                                                        $yes=0;
                                                        $systemsendwith=3;
                                                        $empty=" ";
                                                        $query1 = "INSERT INTO userwallettrans (ourrrate,payapiresponse,swapto,cointrackid,livecointype,systemsendwith,iscrypto,theusdval,btcvalue,bankaccsentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ssssssssssssssssssssssssss",$coinrate,$empty,$currencytag,$coinprodtrackidis,$cointype,$systemsendwith,$yes,$amttopayusd,$amttopaycoin,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                        if ($addTransaction1->execute()){
                                                                
                                                                
                                                      //store swap history
                                                    //   $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","SWAP",true,true,true);
                                                      $reference= $orderId = createTransUniqueToken("SWAP", $user_id);
                                                        $transtype1 = 3;
                                                         $yes=1;
                                                       $addTransaction1 ->bind_param("ssssssssssssssssssssssssss",$coinrate,$empty,$currencytag,$coinprodtrackidis,$cointype,$systemsendwith,$yes,$amttopayusd,$amttopaycoin,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopaycoin,$currency,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                        $addTransaction1->execute();
                                                            
                                                                                        notify_admin_noti_b_bot($message1,$user_id);
                                                                
                                                                $userid=$user_id;
                                                                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                                                                $sysgetdata->bind_param("s",$userid);
                                                                $sysgetdata->execute();
                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                // check if user is sending to himself
                                                                $datais=$dsysresult7->fetch_assoc();
                                                                $ussernamesenttomail=$datais['email'];
                                                                $usersenttophone=$datais['phoneno'];
                                                                $reference=$orderId;
                                                                $subject = swapPaymentSubject($userid,$reference); 
                                                                $to = $ussernamesenttomail;
                                                                $messageText = swapPaymentText($userid, $reference);
                                                                $messageHTML = swapPaymentHTML($userid, $reference);
                                                                sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                sendUserSMS($usersenttophone,$messageText);
                                                                // $userid,$message,$type,$ref,$status
    
                                                                giveMarketerPointForEachUsers($userid,3,$reference);
                                                                
                                                                $data = [];
                                                                $text= $message1;
                                                                $status = true;
                                                                $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                respondOK($successData);
                                            
                                                            } else{
                                                                        // return user money
                                                                       payAddUserSubBalance($user_id,$amttopaycoin,$currency,$wallettrackid);
                                                                        
                                                                        // send db error
                                                                        $errordesc =  $addTransaction1->error;
                                                                        $linktosolve = 'https://';
                                                                        $hint = "500 code internal error, check ur database connections";
                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                        respondInternalError($data);
                                                            }
                                                        } else{
                                                                        // return user money
                                                                       payAddUserSubBalance($user_id,$amttopaycoin,$currency,$wallettrackid);
                                                                        
                                                                        // send db error
                                                                        $errordesc =  "Error adding funds for user";
                                                                        $linktosolve = 'https://';
                                                                        $hint = "500 code internal error, check ur database connections";
                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                        respondInternalError($data);
                                                            }
                                                            
                                                   }else{
                                                            // Insert all fields
                                                            $errordesc = "Unable to deduct fund";
                                                            $linktosolve = 'https://';
                                                            $hint = "Unable to deduct fund";
                                                            $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                            respondBadRequest($data); 
                                                        }
                                                
                                         
                                }else{
                                    $errordesc="Insufficient Balance";
                                    $linktosolve="https://";
                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Your balance is too low for the transaction to be processed";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                                  }
                            }else{
                                    $errordesc="Error from server";
                                    $linktosolve="https://";
                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Error processing transaction, try again later";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                                  }
                            }else if($type==4){ //send to address
                                
                                
                                
                                //send to address
                                // get the send coin details
                                    $coindata=getCoinDetails($network_cointid);
                                    $coinsubwallettag=$coindata['coin_send_sys_tid'];
                                    $coinname=$coindata['name'];
                                    $coinplatform = $coindata['coinplatform'];
                                    $subwallettag=$coindata['subwallettag'];
                                    $securitydata=getSendCoin_Security_Level($subwallettag,$useruserlevel);
                                    $coinminimum_to_send=$securitydata['minimum_to_send']+0;
                                    $securitymax_oneday_auto=$securitydata['max_oneday_auto']+0;
                                    $security_maxsendcountaday=$securitydata['max_count_auto_daily'];
                                    $security_maxsendcountweekly=$securitydata['max_weekly_send'];
                                    $security_lockaccount_if=$securitydata['lockaccount_if'];
                                    $security_check_if_deposit=$securitydata['check_if_deposit'];
                                 
                                   if($amttopay>=$coinminimum_to_send){
                                        $sendcoindata=getSendCoinDetailsByTid($coinsubwallettag);
                                        $getlivevalu=0;  
                                        $coinrate=0;  
                                        // get currency details $currency 
                                        $getlivevalu=getMeCoinLiveUSdValue($network_cointid); 
                                        // check if balance reach
                                       if( $getlivevalu!=0){
                                          
                                                $amttopayusd=$amttopay * $getlivevalu;
                                                $productTid=$sendcoindata['producttrackid'];
                                                $sendcoinplatform=$sendcoindata['coinplatform'];
                                                $sendcointype =$sendcoindata['cointype'];
                                                $active=1;
                                                $coinfee=0;
                                                // calculate fee,
                                                $getdata =  $connect->prepare("SELECT generalfee FROM coinsend_fee WHERE cointrackid=? AND status=? AND max >=? AND min <=?");
                                                $getdata->bind_param("siss",$productTid, $active,$amttopayusd,$amttopayusd);
                                                $getdata->execute();
                                                $dresult = $getdata->get_result();
                                                if ($dresult->num_rows > 0) {
                                                $ddetails = $dresult->fetch_assoc();
                                                        // extend api expire date
                                                        $coinfee=$ddetails['generalfee'];
                                                }
                                                 // remobve fee and amount from user bal
                                                 
                                                 
                                                 
                                                 // if user is sending all money 2000 and fee is 100 naira, 
                                                 // chekc if amout is >= user bal if yes, amount to send is 2000-100=1900
                                                if($amttopay>=$walletbal){
                                                  
                                                  $amttopay=$walletbal-$coinfee; 
                                                }
                                                // if amount is not then check if amount topay + fee > wallt bal
                                                 // if yes subtract sum of amttopay+fee from wallbal then d result subtract from amout to pay
                                                 $expectedtodebit=$amttopay+$coinfee;
                                                if($expectedtodebit > $walletbal){
                                                    $topper=$expectedtodebit-$walletbal;
                                                    $amttopay=$amttopay-$topper;
                                                }
                                                $realamouttopay=$amttopay; 
                                                $amttopay=$amttopay+$coinfee;
                                                
                                               if($coinfee>0&&$walletbal>0&&$amttopay>0 && $realamouttopay >0){
                                                        if($walletbal >= $amttopay && ($walletbal-$amttopay) >=0){
                                                        
                                                                // subtract bal
                                                                          if(payRemoveUserSubBalance($user_id,$amttopay,$currency,$wallettrackid)){
                                                                                $amttopaycoin=$realamouttopay;
                                                                                
                                                                                // 1 on choose of coin, get it send with subwallettag to gt send details which would now b used to form d trans details nd move to next
                                                                                // 2  generate trans with order id ans status 0 pend and call function
                                                                                // form trans data
                                                                              
                                                                                // convert usd to naira
                                                                                // add fund to use balance
                                                                                // add new transaction
                                                                                $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                                $transhash = '';
                                                                                // generating  order ref
                                                                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","SEND",true,true,true);
                                                                                $reference= $orderId = createTransUniqueToken("CES", $user_id);
                                                                                $ordertime = date("h:ia, d M");
                                                                                $confirmtime = '';
                                                                                $status = 0; 
                                                                                $username="";
                                                                                $accountsentto="";
                                                                                $addresssentto = '';
                                                                                $manualstatus = 0;
                                                                                $currencytag = "USD256";
                                                                                $approvaltype = 1;
                                                                                $message1 = "Send $amttopaycoin $coinname to $address";
                                                                                // insert the values to the transation for recieve
                                                                                $transtype1 = 1;
                                                                                $yes=1;
                                                                                $systemsendwith=4;
                                                                                $empty=" ";
                                                                                $sendtype=2;
                                                                                if($memo=="None"){
                                                                                $memo="";
                                                                                }
                                                                                if($message="None"){
                                                                                $message="";
                                                                                }
                                                                                $query1 = "INSERT INTO userwallettrans (bill_profit_loose,sendcrypto_message,network_cointrackid,memo,addresssentto,send_type,send_fee,ourrrate,payapiresponse,swapto,cointrackid,livecointype,systemsendwith,iscrypto,theusdval,btcvalue,bankaccsentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                                $addTransaction1 = $connect->prepare($query1);
                                                                                $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssssss",$coinfee,$message,$productTid,$memo,$address,$sendtype,$coinfee,$coinrate,$empty,$empty,$network_cointid,$sendcointype,$systemsendwith,$yes,$amttopayusd,$amttopaycoin,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$realamouttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                                                if ($addTransaction1->execute()){
                                                                                    
                                                                                        // get trans details
                                                                                        $transid="";
                                                                                        $getexactdata =  $connect->prepare("SELECT id FROM 	userwallettrans WHERE orderid=? AND addresssentto=?");
                                                                                        $getexactdata->bind_param("ss", $reference,$address);
                                                                                        $getexactdata->execute();
                                                                                        $rresult2 = $getexactdata->get_result();
                                                                                        $num = $rresult2->num_rows ;
                                                                                        if ($num>0) {
                                                                                        $ddatasent=$rresult2->fetch_assoc();
                                                                                        $transid=$ddatasent['id'];
                                                                                        
                                                                                        }
                                                                                        
                                                                                        // count sum user sent a day
                                                                                        $selectedmonth= date('n');//12
                                                                                        $selectedyear= date('Y');//2022
                                                                                        $todaydayis=date('d');
                                                                                        $sendtypeis=2;
                                                                                        $donesuccess=1;
                                                                                        $totalcoinsent=0;
                                                                                        $totaldone_adayis=0;
                                                                                        // SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=2 AND MONTH(created_at) = 1 AND YEAR(created_at)=2023 AND DAY(created_at)=19 AND status=1 AND userid=7 GROUP BY userid
                                                                                        $getexactdata =  $connect->prepare("SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=?  AND  MONTH(created_at) = ? AND YEAR(created_at)=? AND DAY(created_at)=? AND status=? AND userid=?  GROUP BY userid");
                                                                                        $getexactdata->bind_param("ssssss", $sendtypeis,$selectedmonth,$selectedyear,$todaydayis,$donesuccess,$user_id);
                                                                                        $getexactdata->execute();
                                                                                        $rresult2 = $getexactdata->get_result();
                                                                                        $totaldone_adayis = $rresult2->num_rows;
                                                                                        if ($totaldone_adayis>0) {
                                                                                            $ddatasent=$rresult2->fetch_assoc();
                                                                                            $totalcoinsent=$ddatasent['totalsent'];
                                                                                        }
                                                                                        
                                                                                        $totalcoinsent_week=0;
                                                                                        $totaldone_aweekis=0;
                                                                                        $getexactdata =  $connect->prepare("SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=? AND (created_at BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()) AND status=? AND userid=?  GROUP BY userid");
                                                                                        $getexactdata->bind_param("sss", $sendtypeis,$donesuccess,$user_id);
                                                                                        $getexactdata->execute();
                                                                                        $rresult2 = $getexactdata->get_result();
                                                                                        $totaldone_aweekis = $rresult2->num_rows;
                                                                                        if ($totaldone_aweekis>0) {
                                                                                            $ddatasent=$rresult2->fetch_assoc();
                                                                                            $totalcoinsent_week=$ddatasent['totalsent'];
                                                                                        }
                                                                                        
                                                                                        $haveneversent=1;
                                                                                        $received=2;
                                                                                        $yescryp=1;
                                                                                        $successdone=1;
                                                                                        $getexactdata =  $connect->prepare("SELECT btcvalue FROM userwallettrans WHERE transtype=? AND iscrypto=? AND status=? AND userid=? AND livetransid IS NOT NULL LIMIT 1");
                                                                                        $getexactdata->bind_param("ssss",$received,$yescryp,$successdone,$user_id);
                                                                                        $getexactdata->execute();
                                                                                        $rresult2 = $getexactdata->get_result();
                                                                                        $totaldone_aweekis = $rresult2->num_rows;
                                                                                        if ($totaldone_aweekis>0) {
                                                                                            $haveneversent=0;
                                                                                        }
                                                                                        
                                                                                        
                                                                                        // check balance
                                                                                        // store as approval trans
                                                                                        $auto=1;
                                                                                        $response="";
                                                                                        $manualAtype=0;// used the telegram bot to get reason for manual
                                                                                        if($security_check_if_deposit==1 && $haveneversent==1){
                                                                                                $manualAtype=6;
                                                                                                $auto=0; 
                                                                                                $response="*SENDING OUT CRYPTO*\n\nPROCEED WITH CAUTION,User have never gotten any deposit and he his trying to send out, kindly check and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      

                                                                                        }else if($amttopay>check_SenderBal_api($productTid)){
                                                                                            $manualAtype=1;
                                                                                          $auto=0;  
                                                                                          $response="*SENDING OUT CRYPTO*\n\nBecause of insufficient funds, the following transaction cannot be processed; please fund your wallet and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      
                                                                                        }else  if($totalcoinsent>$securitymax_oneday_auto){
                                                                                              $manualAtype=2;
                                                                                            $auto=0; 
                                                                                            $response="*SENDING OUT CRYPTO*\n\nUser have sent more than allowed amount in a day, kindly check and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      
                                                                                        
                                                                                        }else  if($totaldone_adayis>$security_maxsendcountaday){
                                                                                              $manualAtype=3;
                                                                                            $auto=0; 
                                                                                            $response="*SENDING OUT CRYPTO*\n\nUser have sent more than the times allowed in a day, kindly check and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      
                                                                                        
                                                                                        }else  if($totalcoinsent_week>$security_maxsendcountweekly){
                                                                                              $manualAtype=4;
                                                                                            $auto=0; 
                                                                                            $response="*SENDING OUT CRYPTO*\n\nUser have sent more than the times allowed in a week, kindly check and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      
                                                                                        
                                                                                        }else if($security_lockaccount_if>0 && $amttopaycoin>=$security_lockaccount_if){
                                                                                              $manualAtype=5;
                                                                                            $auto=0; 
                                                                                            $response="*SENDING OUT CRYPTO*\n\nUser account has been locked for auto withdrawal; the amount sent once has exceeded the limit; please check and approve.\n\nUsername-$usernamesentfrm\nAddress-`$address`\nAmount-$amttopaycoin $coinname\nFee-$coinfee $coinname\n\nOrder ID-`$reference`";      
                                                                                            // lock account
                                                                                            $lockme=0;
                                                                                            $insert_data4 = $connect->prepare("UPDATE users SET allow_autosend_crypto=? WHERE id=?");
                                                                                            $insert_data4->bind_param("ss",$lockme,$user_id);
                                                                                            $insert_data4->execute();
                                                                                        }
                                                                                        
                                                                                        
                                                                                        
                                                                                        if($auto==1){               
                                                                                                        if(sendFrom_HT_sender_wallet($productTid,$user_id,$currencytag,$sendcoinplatform,$address,$amttopaycoin,$memo,$message,$reference,$network_cointid)){
                                                                                                            
                                                                                                            $zero=0;
                                                                                                            $sysgetdata =  $connect->prepare("SELECT telegramcsschatid FROM admin WHERE telegramcsschatid!=?");
                                                                                                            $sysgetdata->bind_param("s", $zero);
                                                                                                            $sysgetdata->execute();
                                                                                                            $dsysresult7 = $sysgetdata->get_result();
                                                                                                            // check if user is sending to himself
                                                                                                            if($dsysresult7->num_rows>0){
                                                                                                                $datais=$dsysresult7->fetch_assoc();
                                                                                                                $finalchatid=$datais['telegramcsschatid'];
                                                                                                                $response="*SENDING OUT CRYPTO*%0A%0AThe following transaction has been sent to the processing system automatically.%0A%0AUsername-$usernamesentfrm%0AAddress-`$address`%0AAmount-$amttopaycoin $coinname%0AFee-$coinfee $coinname%0A%0AOrder ID-`$reference`";
                                                                                                                $finalbotid=$mainCryptoBotIdfor_telegrm;
                                                                                                                $keyboard = [];
                                                                                                                replyuser($finalchatid, "0", $response, false, $keyboard,$finalbotid,"markdown");  
                                                                                                            }
                                                                                                        
                                                                                                            notify_admin_noti_b_bot($message1,$user_id); 
                                                                                                            giveMarketerPointForEachUsers($user_id,1,$reference);
                                                                                                            $data = [];
                                                                                                            $text= $message1;
                                                                                                            $status = true;
                                                                                                            $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                                            respondOK($successData);
                                                                                                        }else{
                                                                                                            $cancle=3;
                                                                                                            $insert_data4 = $connect->prepare("UPDATE userwallettrans SET status=? WHERE orderid=?");
                                                                                                            $insert_data4->bind_param("ss",$cancle,$reference);
                                                                                                            $insert_data4->execute();
                                                                                                            // return user money
                                                                                                            payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                                              // send db error
                                                                                                            $errordesc =  "Error Processing order, money refunded";
                                                                                                            $linktosolve = 'https://';
                                                                                                            $hint = "Error from server";
                                                                                                            $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                                            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                                            respondInternalError($data);
                                                                                                        }
                                                                                        }else{
                                                                                            // update to awaiting apporval
                                                                                            $cancle=5;
                                                                                            $insert_data4 = $connect->prepare("UPDATE userwallettrans SET status=?,manual_approvaltype=? WHERE orderid=?");
                                                                                            $insert_data4->bind_param("sss",$cancle,$manualAtype,$reference);
                                                                                            $insert_data4->execute();
                                                                                            
                                                                                            $zero=0;
                                                                                            $sysgetdata =  $connect->prepare("SELECT telegramcsschatid FROM admin WHERE telegramcsschatid!=?");
                                                                                            $sysgetdata->bind_param("s", $zero);
                                                                                            $sysgetdata->execute();
                                                                                            $dsysresult7 = $sysgetdata->get_result();
                                                                                            // check if user is sending to himself
                                                                                            if($dsysresult7->num_rows>0){
                                                                                                $datais=$dsysresult7->fetch_assoc();
                                                                                                $finalchatid=$datais['telegramcsschatid'];
                                                                                                $finalbotid=$mainCryptoBotIdfor_telegrm;
                                                                                                $keyboard = [
                                                                                                'inline_keyboard' => [
                                                                                                        [
                                                                                                        ['text' => 'Confirm Withdrawal', 'callback_data' => "withdrawit^$reference^$transid^5^1"],
                                                                                                        ],
                                                                                                              [
                                                                                                        ['text' => 'Reverse fund', 'callback_data' => "withdrawit^$reference^$transid^5^6"],
                                                                                                        ],
                                                                                                            [
                                                                                                        ['text' => 'Scam flag', 'callback_data' => "withdrawit^$reference^$transid^5^4"],
                                                                                                        ],
                                                                                                ],
                                                                                                ];
                                                                                                replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");   
                                                                                            }
                                                                                            notify_admin_noti_b_bot($message1,$user_id); 
                                                                                            $data = [];
                                                                                            $text= $message1;
                                                                                            $status = true;
                                                                                            $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                            respondOK($successData);
                                                                                        }
                                                                                        
                                                                                        
                                                                                } else{
                                                                                        // return user money
                                                                                        payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                        
                                                                                        // send db error
                                                                                        $errordesc =  $addTransaction1->error;
                                                                                        $linktosolve = 'https://';
                                                                                        $hint = "500 code internal error, check ur database connections";
                                                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                        respondInternalError($data);
                                                                                }
                                                                           }else{
                                                                                    // Insert all fields
                                                                                    $errordesc = "Unable to deduct fund";
                                                                                    $linktosolve = 'https://';
                                                                                    $hint = "Insufficient fund,Unable to deduct fund";
                                                                                    $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                    $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                    respondBadRequest($data); 
                                                                                }
                                                        }else{
                                                            $errordesc="Insufficient Balance";
                                                            $linktosolve="https://";
                                                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                            $text="Your balance is too low for the transaction to be processed";
                                                            $method=getenv('REQUEST_METHOD');
                                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                            respondBadRequest($data);
                                                          }
                                               }else{
                                                    $errordesc="Error calculating fee, try again later";
                                                    $linktosolve="https://";
                                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Your balance is too low for the transaction to be processed";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                                  }         
                                                  
                                        }else{
                                                $errordesc="Error from server";
                                                $linktosolve="https://";
                                                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="Opps,Error processing transaction, try again later";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);
                                        }
                                
                                    }else{
                                                    $errordesc="BAD PAY METHOD";
                                                    $linktosolve="https://";
                                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="The minimum you can send is $coinminimum_to_send $coinname";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                    } 
                                    
                                    
                                    
                                
                            }else{
                                            $errordesc="BAD PAY METHOD";
                                            $linktosolve="https://";
                                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Payment method passed not available";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                            }    
                }  else{
                    $errordesc="BAD PAY METHOD";
                    $linktosolve="https://";
                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Feature is currently  not available";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
               
            }  else{
                        $errordesc="BAD PAY METHOD";
                        $linktosolve="https://";
                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="You must be in level 2 before you can withdraw";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                }   
        }
    }else{

        // Send an error response because a wrong method was passed 
        $errordesc = "Method not allowed";
        $linktosolve = 'https://';
        $hint = "This route only accepts POST request, kindly pass a post request";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondMethodNotAlowed($data);
        
    }
?>