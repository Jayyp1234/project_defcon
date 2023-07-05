<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
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
        $activatepincheck=1;

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
            $amttopay = cleanme($_POST['amttopay']);
        }
        // if ( !isset($_POST['username'])) {

        //     $errordesc="amttopay required";
        //     $linktosolve="https://";
        //     $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
        //     $text="amttopay must be passed";
        //     $method=getenv('REQUEST_METHOD');
        //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
        //     respondBadRequest($data);

        // }else{
        //     $username = cleanme($_POST['username']);
        // }
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
            $type = cleanme($_POST['type']);
        }

    
        if (empty($amttopay)||empty($username)||empty($wallettrackid)||empty($currency)||empty($type)){
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
        }else if ($_POST['type']==5 && !isset($_POST['merchant_id'])){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass a valid value to fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if (($_POST['type']==1 && !isset($_POST['username']))||($_POST['type']==1 && empty($_POST['username']))){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass a valid username to the field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if ($activatepincheck==1 && (($_POST['type']==1 && !isset($_POST['pin']))||($_POST['type']==1 && empty($_POST['pin'])))){
            $errordesc="To proceed, kindly enter your PIN.";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="To proceed, kindly enter your PIN.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else{
            $merchantid="";
            $username ="";
            if(isset($_POST['merchant_id'])){
                 $merchantid=cleanme($_POST['merchant_id']);
            }
            if(isset($_POST['username'])){
                 $username =cleanme($_POST['username']);
            }
                
            $yesits=1;
            $sysgetdata =  $connect->prepare("SELECT maxngn_auto FROM systemsettings WHERE id=?");
            $sysgetdata->bind_param("s", $yesits);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            // check if user is sending to himself
            $datais=$dsysresult7->fetch_assoc();
            $sysmaxngn_auto=$datais['maxngn_auto'];

            $sysgetdata =  $connect->prepare("SELECT username,email,phoneno,userlevel,pin,fcm,cashback_bal FROM users WHERE id=?");
            $sysgetdata->bind_param("s", $user_id);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            // check if user is sending to himself
            $datais=$dsysresult7->fetch_assoc();
            $usernamesentfrm=$datais['username'];
            $ussernamesentfrommail=$datais['email'];
            $usersentfromphone=$datais['phoneno'];
            $usermainlevel =$datais['userlevel'];
            $passpin = $datais['pin'];
            $usersentfromfcm = $datais['fcm'];
            $cashback_bal=$datais['cashback_bal'];
            
            // check if type and currecny relate
            // preventing the use of another payment method to pay for another currency
            // only main wallet can use this API
            $active=1;
            $mainorsubwallet=1;
            $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencywithdrawmethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=?");
            $sysgetdata->bind_param("ssss", $currency,$type,$active,$mainorsubwallet);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys = $dsysresult7->num_rows;
             // preventing funding with out the currecncy trackid
            $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal FROM userwallet WHERE currencytag=? AND wallettrackid=? AND userid=?");
            $sysgetdata->bind_param("sss", $currency,$wallettrackid,$user_id);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys2 = $dsysresult7->num_rows;
            if($getsys>0&&$getsys2>0){
                $getuserdata= $dsysresult7->fetch_assoc();
                $walletbal=$getuserdata['walletbal'];
                  // check if user have enough balance
                  if($walletbal>=$amttopay){
                        if ($type==1){//send to user
                             if($usermainlevel<2){
                                $errordesc="You have to be in level 2 before you can withdraw";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="You have to be in level 2 before you can withdraw";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data); 
                            }else{
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
                                                  if($dsysresult7->num_rows>0){
                                                        // check if user is sending to himself
                                                        $datais=$dsysresult7->fetch_assoc();
                                                        $usermainlevel2 =$datais['userlevel'];
                                                        // check user pin
                                                        $pin=isset($_POST['pin'])?cleanme($_POST['pin']):'';
                                                        $verifypass =check_pass($pin,$passpin);
                                                         if (!$verifypass&&$activatepincheck==1) {
                                                            $errordesc="The entered PIN is invalid. Please try again.";
                                                            $linktosolve="htps://";
                                                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                            $text="The entered PIN is invalid. Please try again.";
                                                            $method=getenv('REQUEST_METHOD');
                                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                            respondBadRequest($data);
                                                        } else if($usermainlevel2<2){
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
                                                                    
                                                                    if(payDeductUserBalance($user_id,$amttopay,$currency,$wallettrackid)){
                                                                        
                                                                        // add new transaction
                                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                        $transhash = '';
                                                                        // generating  order ref
                                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                        $reference=$orderId = createTransUniqueToken("IT", $user_id);
                                                                        // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","IT",true,true,true);
                                                                        $ordertime = date("h:ia, d M");
                                                                        $confirmtime = '';
                                                                        $status = 0; 
                                                                        $amttopay = $amttopay;
                                                                        $addresssentto = '';
                                                                        $manualstatus = 0;
                                                                        $currencytag = $currency;
                                                                        $approvaltype = 1;
                                                                        $message1 = "Sent NGN ".$amttopay." with internal transfer";
                                                                        $empty=" ";
                                                                        // insert the values to the transation for recieve
                                                                        $transtype1 = 1;
                                                                        $systemsendwith=1;
                                                                        $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,usernamesentfrm,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                        $addTransaction1 ->bind_param("sssssssssssssssssss",$empty,$systemsendwith,$usernamesentfrm,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                                        if ($addTransaction1->execute()){
                                                                                 // get send to user details and wallet data
                                                                                $sysgetdata =  $connect->prepare("SELECT wallettrackid FROM userwallet WHERE userid=? AND currencytag=?");
                                                                                $sysgetdata->bind_param("ss", $user_recieve_id,$currency);
                                                                                $sysgetdata->execute();
                                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                                // check if user is sending to himself
                                                                                if($dsysresult7->num_rows>0){
                                                                                        $getsenttodata=$dsysresult7->fetch_assoc();
                                                                                        $senttowallettrackid=$getsenttodata['wallettrackid'];
                                                                                        // credit the user sent to
                                                                                        if(payAddUserBalance($user_recieve_id,$amttopay,$currency,$senttowallettrackid)){
                                                                                            // add new transction for user sent to
                                                                                            // add new transaction
                                                                                            $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                                            $transhash = '';
                                                                                            // generating  order ref
                                                                                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                                            // $orderId = createUniqueToken(18,"userwallettrans","orderid","IR",true,true,true);
                                                                                            $orderId = createTransUniqueToken("IR", $user_recieve_id);
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
                                                                                            $message2 = "Received NGN ".$amttopay." with internal transfer";
                                                                                            // insert the values to the transation for recieve
                                                                                            $transtype1 = 2;
                                                                                            $approvedby="Automation";
                                                                                            $systemsendwith=1;
                                                                                            $empty=" ";
                                                                                            $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,approvedby,usernamesentfrm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                                            $addTransaction1 = $connect->prepare($query1);
                                                                                            $addTransaction1 ->bind_param("ssssssssssssssssssss",$empty,$systemsendwith,$user_recieve_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message2,$ordertime,$confirmtime,$companypayref,$status,$senttowallettrackid,$username,$approvedby,$usernamesentfrm);
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
                                                                                            notify_admin_noti_b_bot($message1,$user_id);
                                                                                            // sms mail noti for who sent
                                                                                            $subject = paymentSuccessSubject($user_id,$reference); 
                                                                                            $to =$ussernamesentfrommail;
                                                                                            $messageText = paymentSuccessfullText($user_id, $reference);
                                                                                            $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                                            sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                            sendUserSMS($usersentfromphone,$messageText);
                                                                                            // $userid,$message,$type,$ref,$status
                                                                                            internal_transfer_user_noti($user_id,$reference);
                                                                                          
                                                                                            
                                                                                            // sms mail noti for who receive
                                                                                            $sysgetdata =  $connect->prepare("SELECT email,phoneno,fcm,username FROM users WHERE id=?");
                                                                                            $sysgetdata->bind_param("s", $user_recieve_id);
                                                                                            $sysgetdata->execute();
                                                                                            $dsysresult7 = $sysgetdata->get_result();
                                                                                            // check if user is sending to himself
                                                                                            $datais=$dsysresult7->fetch_assoc();
                                                                                            $ussernamesenttomail=$datais['email'];
                                                                                            $usersenttophone=$datais['phoneno'];
                                                                                            $usersenttofcm=$datais['fcm'];
                                                                                             $usersenttousername=$datais['username'];
                                                                                            $subject = receivedPaymentSubject($user_recieve_id,$orderId); 
                                                                                            $to = $ussernamesenttomail;
                                                                                            $messageText = receivedPaymentText($user_recieve_id, $orderId);
                                                                                            $messageHTML = receivedPaymentHTML($user_recieve_id, $orderId);
                                                                                            sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                            sendUserSMS($usersenttophone,$messageText);
                                                                                            // $userid,$message,$type,$ref,$status
                                                                                            internal_receive_user_noti($user_recieve_id,$orderId);
                                                                                            
                                                                                            
                                                                                            
                                                                                            
                                                                                            giveMarketerPointForEachUsers($user_id,1,$orderId);
                                                                                            $data = [];
                                                                                            $text= "$message1";
                                                                                            $status = true;
                                                                                            $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                            respondOK($successData);
                                                                                      
                                                                                        }else{
                                                                                            // return user money and set transstatus to 3
                                                                                            payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                            payTransCancled($orderId);
                                                                                            // Insert all fields
                                                                                            $errordesc = "Unable to Credit user";
                                                                                            $linktosolve = 'https://';
                                                                                            $hint = "Unable to Credit user";
                                                                                            $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                            respondBadRequest($data);  
                                                                                        }
                                                                                }else{
                                                                                    // return user money and set transstatus to 3
                                                                                    payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                                    payTransCancled($orderId);
                                                                                                               
                                                                                    // Insert all fields
                                                                                    $errordesc = "User does not have the currency type";
                                                                                    $linktosolve = 'https://';
                                                                                    $hint = "User does not have the currency type";
                                                                                    $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                                    $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                                    respondBadRequest($data);  
                                                                                }
                                                                        }else{
                                                                            // return user money
                                                                            payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                            
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
                                                        $errordesc="User not found";
                                                        $linktosolve="https://";
                                                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                        $text="ending to yourself is not allowed";
                                                        $method=getenv('REQUEST_METHOD');
                                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                        respondBadRequest($data);
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
                            }
                        }else if($type==2){//external transfer
                                if ( !isset($_POST['bankid'])) {
                                
                                $errordesc="user bank is required";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Please reselect your bank account";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                                
                                }else{
                                        $bankid = cleanme($_POST['bankid']);
                                        $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                        $getUser->bind_param("ss",$bankid,$user_id);
                                        $getUser->execute();
                                        $result = $getUser->get_result();
                                        if($result->num_rows > 0){
                                            //bank exist
                                            $row = $result->fetch_assoc();
                                            $accbnkcode =$row['bankcode'];
                                            $acctosendto = $row['account_no'];
                                            $refcode = $row['refcode'];
                                            $accountname = $row['account_name'];
                                            $bankname = $row['bank_name'];
                                            $getUser->close();
                                            
                                            
                                              if(payDeductUserBalance($user_id,$amttopay,$currency,$wallettrackid)){
                                                        
                                                        // add new transaction
                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                        $transhash = '';
                                                        // generating  order ref
                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                        // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","ET",true,true,true);
                                                        $reference=$orderId =createTransUniqueToken("ET", $user_id);
                                                        $ordertime = date("h:ia, d M");
                                                        $confirmtime = '';
                                                        $status = 0; 
                                                        $username="";
                                                        $accountsentto="$bankname/$acctosendto";
                                                        $amttopay = $amttopay;
                                                        $addresssentto = '';
                                                        $manualstatus = 0;
                                                        $currencytag = $currency;
                                                        $approvaltype = 1;
                                                        $message1 = "Sent NGN ".$amttopay." with External transfer";
                                                        // insert the values to the transation for recieve
                                                        $transtype1 = 1;
                                                        $systemsendwith=2;
                                                        $empty=" ";
                                                        $query1 = "INSERT INTO userwallettrans (payapiresponse,systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ssssssssssssssssssss",$empty,$systemsendwith,$accountsentto, $accbnkcode ,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                        if ($addTransaction1->execute()){
                                                            
                                                                    if($amttopay<=$sysmaxngn_auto){
                                                            
                                                                            $pay = payUserWithAnyBankSystem($amttopay, $accbnkcode, $accountname, $bankname, $acctosendto, $refcode, $orderId);
                                                                            if ($pay){
                                                                                
                                                                                
                                                                              // sms mail noti for who sent
                                                                            $subject = paymentSuccessSubject($user_id,$reference); 
                                                                            $to =$ussernamesentfrommail;
                                                                            $messageText = paymentSuccessfullText($user_id, $reference);
                                                                            $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                            sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                            sendUserSMS($usersentfromphone,$messageText);
                                                                            // $userid,$message,$type,$ref,$status
                                                                            wallet_withdraw_user_noti($user_id,$reference);
                                                                            
                                                                              giveMarketerPointForEachUsers($user_id,1,$reference);
                                                                              notify_admin_noti_b_bot($message1,$user_id);
                                                                                $data = [];
                                                                                $text= $message1;
                                                                                $status = true;
                                                                                $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                respondOK($successData);
                                                                            }  else{
                                                                                 payTransInwallet($orderId);
                                                                                      
                                                                                
                                                                           // sms mail noti for who sent
                                                                            $subject = paymentSuccessSubject($user_id,$reference); 
                                                                            $to =$ussernamesentfrommail;
                                                                            $messageText = paymentSuccessfullText($user_id, $reference);
                                                                            $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                            sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                            sendUserSMS($usersentfromphone,$messageText);
                                                                            // $userid,$message,$type,$ref,$status
                                                                            wallet_withdraw_user_noti($user_id,$reference);
                                                                            notify_admin_noti_b_bot($message1,$user_id);
                                                                            
                                                                                
                                                                                
                                                                                $data = [];
                                                                                $text= $message1;
                                                                                $status = true;
                                                                                $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                                respondOK($successData);
                                                                            }
                                                                    }else{
                                                                        
                                                                        payTransInwallet($orderId);
                                                                        
                                                                                           
                                                                           // sms mail noti for who sent
                                                                            $subject = paymentSuccessSubject($user_id,$reference); 
                                                                            $to =$ussernamesentfrommail;
                                                                            $messageText = paymentSuccessfullText($user_id, $reference);
                                                                            $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                            sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                            sendUserSMS($usersentfromphone,$messageText);
                                                                            // $userid,$message,$type,$ref,$status
                                                                            wallet_withdraw_user_noti($user_id,$reference);
                                                                            notify_admin_noti_b_bot($message1,$user_id);
                                                                            
                                                                            
                                                                        $data = [];
                                                                        $text= $message1;
                                                                        $status = true;
                                                                        $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                        respondOK($successData);
                                                                    }
                                                                
                                                                
                                                            
                                                        } else{
                                                                    // return user money
                                                                    payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                    
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
                                            
                                        }else{
                                            $errordesc="Bank details not found";
                                            $linktosolve="https://";
                                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Bank details not found";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);  
                                        }
                                }
                        }else if($type==5){//external transfer peer stack
                              // check if user is in level 2
                            if($usermainlevel<2){
                                $errordesc="You have to be in level 2 before you can withdraw";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="You have to be in level 2 before you can withdraw";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data); 
                            }else if ( !isset($_POST['bankid'])) {//peerStack
                                
                                $errordesc="user bank is required";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Please ensure you have added a bank account";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                                
                            }else{
                                $cashback=0;
                                if (isset($_POST['cashback']) && $_POST['cashback']==1) {
                                    $cashback=1;
                                    $amttopay=$cashback_bal;
                                }
                                
                                        $bankid = cleanme($_POST['bankid']);
                                        $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                        $getUser->bind_param("ss",$bankid,$user_id);
                                        $getUser->execute();
                                        $result = $getUser->get_result();
                                        if($result->num_rows > 0){
                                            //bank exist
                                            $row = $result->fetch_assoc();
                                            $accbnkcode =$row['bankcode'];
                                            $acctosendto = $row['account_no'];
                                            $refcode = $row['refcode'];
                                            $accountname = $row['account_name'];
                                            $bankname = $row['bank_name'];
                                            $getUser->close();
                                            
                                           // get merchant agent
                                            $peerstack_fee=0;
                                            $query = 'SELECT withdrawalcharge,id,min_withdrawal FROM peerstackmerchants WHERE merchant_trackid= ?';
                                            $stmt = $connect->prepare($query);
                                            $stmt->bind_param("s",$merchantid);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $num_row = $result->num_rows;
                                            if($num_row>0){
                                                    $agentdata=$result->fetch_assoc();
                                                    $peerstack_fee=$agentdata['withdrawalcharge'];  
                                                    $agentid =$agentdata['id'];
                                                    $min_withdrawal =$agentdata['min_withdrawal'];
                                                    if($cashback==1){
                                                         $systemSettings = getAllSystemSetting();
                                                         $withdrawlLimit = ( $systemSettings )? $systemSettings['min_referall_withdraw'] : 0;
                                                         $min_withdrawal=floatval($withdrawlLimit);
                                                    }
                                                    
                                                            
                                                    $mainamttopay = $amttopay-$peerstack_fee;
                                                if ( $mainamttopay>=$min_withdrawal) {
                                                            
                                                     // deduct
                                                     // save in escrow
                                                            $funddeducted=false;
                                                             if($cashback==1){
                                                                 $funddeducted=payDeductUserCashbackBal($user_id,$amttopay);
                                                             }else{
                                                                $funddeducted= payDeductUserBalance($user_id,$amttopay,$currency,$wallettrackid) && payAddUserEscrowBalance($user_id,$mainamttopay,$currency,$wallettrackid);
                                                             }
                                                           if($funddeducted){
                                                                
                                                            
                          
                                    //  save merchat, amout deducted, amout to pay, system type
                                                                // add new transaction
                                                                $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                $transhash = '';
                                                                // generating  order ref
                                                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                $thedayis=date("d");
                                    $themonthis=date("m");
                                    $theyearis=date("y");
                                    $getexactdata =  $connect->prepare("SELECT id FROM userwallettrans WHERE peerstack_agent=?");
                                    $getexactdata->bind_param("s",$merchantid);
                                    $getexactdata->execute();
                                    $rresult2 = $getexactdata->get_result();
                                    $thenextcount=$num = $rresult2->num_rows ;
                                    //  echo $thenextcount; 
                                    $thenextcount2=$thenextcount+1;
                                    $orderIdmini ="$agentid".$thedayis.$themonthis.$theyearis;
                                    $orderId = $orderIdmini.$thenextcount2;
        
                                    $loopit=true;
                                    while($loopit){
                                            // check field
                                        $query = "SELECT id FROM userwallettrans WHERE orderid = ?";
                                        $stmt = $connect->prepare($query);
                                        $stmt->bind_param("s",$orderId);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $num_row = $result->num_rows;
                                        if ($num_row > 0){
                                            $thenextcount2= $thenextcount2+1;
                                            $orderId = $orderIdmini.$thenextcount2;
                                        }else{
                                            $loopit=false; 
                                            $orderId =$orderId;
                                        }
                                    } 
                                    $orderId=$orderId."W";
                                        if($cashback==1){
                                            $orderId="CASHBACK".$orderId;
                                        }
                                    
                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","PEST",true,true,true);
                                                                $reference=$orderId;
                                                                $ordertime = date("h:ia, d M");
                                                                $confirmtime = '';
                                                                $status = 0; 
                                                                $username="";
                                                                $accountsentto="$bankname/$acctosendto";
                                                              
                                                                $amountsentin= $amttopay;
                                                                $addresssentto = '';
                                                                $manualstatus = 0;
                                                                $currencytag = $currency;
                                                                $approvaltype = 1;
                                                                $message1 = "Sent NGN ".$mainamttopay." with Peerstack";
                                                                // insert the values to the transation for recieve
                                                                $transtype1 = 1;
                                                                $systemsendwith=5;
                                                                $empty=" ";
                                                                $query1 = "INSERT INTO userwallettrans (bill_profit_loose,peerstack_fee,amountsentin,peerstack_agent,payapiresponse,systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,cashbacktrans) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                $addTransaction1 = $connect->prepare($query1);
                                                                $addTransaction1 ->bind_param("ssssssssssssssssssssssssi",$peerstack_fee,$peerstack_fee,$amountsentin,$merchantid,$empty,$systemsendwith,$accountsentto, $accbnkcode ,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$mainamttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username,$cashback);
                                                                if ($addTransaction1->execute()){
                                                                    $tptype=1;
                                                                      if($cashback==1){
                                                                           sendAdminCashbackTeleNoti($tptype,$orderId,$mainamttopay,$user_id,$merchantid,$bankid);
                                                                      }else{
                                                                          sendAdminPeersatckTeleNoti($tptype,$orderId,$mainamttopay,$user_id,$merchantid,$bankid); 
                                                                      }
                                                                   
                                                                                // sumtractmerchant bal
                                                                                // $checkdata =  $connect->prepare("UPDATE peerstackmerchants  SET active_balance=active_balance-?,active_escrow_balance=active_escrow_balance+? WHERE merchant_trackid=?");
                                                                                // $checkdata->bind_param("sss",$mainamttopay,$mainamttopay,$merchantid);
                                                                                // $checkdata->execute();
                                                                                // $dresult = $checkdata->get_result();
                                                                                // $checkdata->close();
                                                                                // set status = 2
                                                                                payTransInwallet($orderId);
                                                                                               
                                                                                               
                                                                                notify_admin_noti_b_bot($message1,$user_id);
                                                                              
                                                                        
                                                                               // sms mail noti for who sent
                                                                                // $subject = paymentSuccessSubject($user_id,$reference); 
                                                                                // $to =$ussernamesentfrommail;
                                                                                // $messageText = paymentSuccessfullText($user_id, $reference);
                                                                                // $messageHTML = paymentSuccessfullHTML($user_id, $reference);
                                                                                // sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                // sendUserSMS($usersentfromphone,$messageText);
                                                                                // // $userid,$message,$type,$ref,$status
                                                                                
                                                                                 //  send back order id and amount
                                                                                    $maindata['orderid']=$orderId;
                                                                                    $maindata['amttoget']=$mainamttopay;
                                                                                    $maindata=[$maindata];
                                                                                    $errordesc = "";
                                                                                    $linktosolve = "https://";
                                                                                    $hint = [];
                                                                                    $errordata = [];
                                                                                    $text = "Data found";
                                                                                    $method = getenv('REQUEST_METHOD');
                                                                                    $status = true;
                                                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                    respondOK($data);
                                                                } else{
                                                                            // return user money
                                                                             if($cashback==1){
                                                                                 payAddUserCashbackBalance($user_id,$amttopay);
                                                                             }else{
                                                                            payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                             }
                                                                            
                                                                            // send db error
                                                                            $errordesc =  $addTransaction1->error;
                                                                            $linktosolve = 'https://';
                                                                            $hint = "500 code internal error, check ur database connections";
                                                                            $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                                            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                                            respondInternalError($data);
                                                                }
                                                            }else{
                                    $errordesc="BAD PAY METHOD";
                                    $linktosolve="https://";
                                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Payment method passed not available, oops";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                                }
                                                }else{
                                                        // Insert all fields
                                                        $errordesc = "The minimum withdrawal amount is $min_withdrawal Naira";
                                                        $linktosolve = 'https://';
                                                        $hint = "We value your efforts and strive to make things easy for you. Just a quick reminder that the minimum withdrawal amount is $min_withdrawal Naira. Thank you for choosing us, and please let us know if you have any questions.";
                                                        $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                                        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                                        respondBadRequest($data); 
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
                                            $errordesc="Bank details not found";
                                            $linktosolve="https://";
                                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Bank details not found";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);  
                                        }
                                }
                        }else{
                                        $errordesc="BAD PAY METHOD";
                                        $linktosolve="https://";
                                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                        $text="Payment method passed not available,ok";
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
            }  else{
                $errordesc="BAD PAY METHOD";
                $linktosolve="https://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Payment method passed not available,oh";
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
