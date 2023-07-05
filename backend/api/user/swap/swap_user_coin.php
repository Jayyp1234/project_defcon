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

        // amttopay: 500
        // currency: NGNT55
        // towallettrackid: NGUSDT57
        // wallettrackid: TYiDHY

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
        if ( !isset($_POST['towallettrackid'])) {

            $errordesc="Wallet track ID must be passed";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Wallet track ID must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $towallettrackid= cleanme($_POST['towallettrackid']);
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
            $amttopay =cleanme($_POST['amttopay']);
        }
        $checkme=1;
           $productamt=0;
           $bilswap=0;
         if (isset($_POST['swapcheckmin'])&&$_POST['swapcheckmin']==0) {
             $bilswap=1;
             $checkme=cleanme($_POST['swapcheckmin']);
             if ( !isset($_POST['productamt'])) {
                    $errordesc="productamt required";
                    $linktosolve="https://";
                    $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="productamt must be passed";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
             }else{
                $productamt=cleanme($_POST['productamt']);
                    
             }
         }
        
        if (empty($amttopay)||empty($wallettrackid)||empty($currency)||empty($towallettrackid)){
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
            // GET COIN TO DETAILS
            $amon=1;
            $sysgetdata =  $connect->prepare("SELECT * FROM swap_system_settings WHERE trackid=? AND status=?");
            $sysgetdata->bind_param("si", $towallettrackid,$amon);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $num_foundis = $dsysresult7->num_rows;
            // check if user is sending to himself
            if($num_foundis>0){
                    $datais=$dsysresult7->fetch_assoc();
                    $coin_from_trackid=$datais['coin_frm_trackid'];
                    $coin_to_trackid=$datais['coin_to_trackid'];
                    $user_level_allowed=$datais['user_level_allowed'];
                    $conversion_rate=$datais['conversion_rate'];
                    $multiply_it=$datais['multiply_it'];
                    $swap_min=floatval($datais['swap_min']);
                    $from_is_crypto=$datais['from_is_crypto'];
                    $to_is_crypto=$datais['to_is_crypto'];
                    $currency_to_tag=$datais['currency_to_tag'];
                    $currency_from_tag=$datais['currency_from_tag'];
                    $currency_from_name=$datais['coin_from_name'];
                    $currency_to_name=$datais['coin_to_name'];
                   
                    // check limit
                  if($amttopay>=$swap_min||$checkme==0){
                         
                        $sysgetdata =  $connect->prepare("SELECT username,userlevel FROM users WHERE id=?");
                        $sysgetdata->bind_param("s", $user_id);
                        $sysgetdata->execute();
                        $dsysresult7 = $sysgetdata->get_result();
                        // check if user is sending to himself
                        $datais=$dsysresult7->fetch_assoc();
                        $usernamesentfrm=$datais['username'];
                        $useruserlevel=$datais['userlevel'];
                        // check level
                        if( $useruserlevel>=$user_level_allowed){    
                            // check if type and currecny relate
                                // preventing the use of another payment method to pay for another currency
                             if($from_is_crypto==0){
                                    $active=1;
                                    $mainorsubwallet=1;
                                    $type=8;
                                    $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencywithdrawmethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=?");
                                    $sysgetdata->bind_param("ssss", $currency,$type,$active,$mainorsubwallet);
                                    $sysgetdata->execute();
                                    $dsysresult7 = $sysgetdata->get_result();
                                    $getsys = $dsysresult7->num_rows;
                             }else{
                                    $active=1;
                                    $mainorsubwallet=2;
                                    $sysgetdata =  $connect->prepare("SELECT id FROM coinproducts WHERE subwallettag=? AND status=? AND allow_swap=?");
                                    $sysgetdata->bind_param("sss", $coin_from_trackid,$active,$active);
                                    $sysgetdata->execute();
                                    $dsysresult7 = $sysgetdata->get_result();
                                    $getsys = $dsysresult7->num_rows; 
                             }
                             
                             
                            if($getsys>0){
                                $walletbal=0;
                               
                            //   get user from wallet balance and wallet details
                                 $coinprodtrackidisfrom="";
                                 $cointypefrom ="";
                                 $fromiscryoto=0;
                                if($from_is_crypto==1){
                                    $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal,coinsystrackid,coinplatform,coinsystemtag FROM usersubwallet WHERE currencytag=? AND trackid=? AND userid=?");
                                    $sysgetdata->bind_param("sss", $currency,$wallettrackid,$user_id);
                                    $sysgetdata->execute();
                                    $dsysresult7 = $sysgetdata->get_result();
                                    $getsys2 = $dsysresult7->num_rows;
                                    if($getsys2 > 0){
                                            $getuserdata= $dsysresult7->fetch_assoc();
                                            $walletbal=$getuserdata['walletbal'];
                                            $coinprodtrackidisfrom=$getuserdata['coinsystrackid'];
                                            $from_coinsystemtag=$getuserdata['coinsystemtag'];
                                            $fromiscryoto=1;
                                            $coindata=getCoinDetailsWithSubTag($from_coinsystemtag);
                                            $getlivevalu=0;  
                                            $cointypefrom =$coindata['cointype'];

                                     }
                                }else{
                                    $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=? AND wallettrackid=?";
                                    $stmt= $connect->prepare($sqlQuery);
                                    $stmt->bind_param("sss",$user_id, $currency,$wallettrackid);
                                    $stmt->execute();
                                    $result= $stmt->get_result();
                                    $getsys2 = $result->num_rows;
                                    if($getsys2 > 0){
                                            $users = $result->fetch_assoc();
                                            $walletbal=$users['walletbal'];
                                    }
                        
                                }
                               
                                  // check if user have enough balance
                                       if($getsys2>0){//swap
                              
                                
                                            if($walletbal>=$amttopay){
                                                // get to conversion
                                                if($multiply_it==1){
                                                   $nairavalueis=$amttopay * $conversion_rate;
                                                }else{
                                                   $nairavalueis=$amttopay /$conversion_rate;  
                                                }
                                                if($bilswap==1){// s the 5% can be removed for bill crypto
                                                    $nairavalueis=$productamt;
                                                }
                                              
                                                $removed=false;
                                                if($from_is_crypto==1){
                                                   $removed= payRemoveUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                }else{
                                                    $removed=payDeductUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                }
                                                // deduct from from wallet and fund to wallet
                                                // add notification/mail/sms
                                                // add telegram
                                                // get user balance for each wallet type
                                                // preventing funding with out the currecncy trackid
                                                // subtract bal
                                                  if($removed){
                                                    //   GET SEND TO  WALLET TRACK ID
                                                    
                                                     
                                                      $addedfund=false;
                                                      $coinprodtrackidisto="";
                                                      $cointypeto="";
                                                      $toiscrypto=0;
                                                        if($to_is_crypto==1){
                                                            
                                                            $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal,coinsystrackid,trackid,coinsystemtag FROM usersubwallet WHERE currencytag=? AND coinsystemtag=? AND userid=?");
                                                            $sysgetdata->bind_param("sss", $currency_to_tag,$coin_to_trackid,$user_id);
                                                            $sysgetdata->execute();
                                                            $dsysresult7 = $sysgetdata->get_result();
                                                            $getsys2 = $dsysresult7->num_rows;
                                                            if($getsys2 > 0){
                                                                $getuserdata= $dsysresult7->fetch_assoc();
                                                                $ngnwallettrackid=$getuserdata['trackid'];
                                                                $coinprodtrackidisto=$getuserdata['coinsystrackid'];
                                                                $from_coinsystemtag=$getuserdata['coinsystemtag'];
                                                                $toiscrypto=1;

                                                                $coindata=getCoinDetailsWithSubTag($from_coinsystemtag);
                                                                $getlivevalu=0;  
                                                                $cointypeto =$coindata['cointype'];

                                                                $addedfund=payAddUserSubBalance($user_id,$nairavalueis,$currency_to_tag,$ngnwallettrackid);  
                                                            }
                                                         
                                                        }else{
                                                            $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=?";
                                                            $stmt= $connect->prepare($sqlQuery);
                                                            $stmt->bind_param("ss",$user_id, $currency_to_tag);
                                                            $stmt->execute();
                                                            $result= $stmt->get_result();
                                                            $getsys2 = $result->num_rows;
                                                            if($getsys2 > 0){
                                                                $users = $result->fetch_assoc();
                                                                $ngnwallettrackid=$users['wallettrackid'];
                                                                $addedfund=payAddUserBalance($user_id,$nairavalueis,$currency_to_tag,$ngnwallettrackid);
                                                            }
                                                        }
                                                        
                                                       
                                                        if($addedfund){
                                                             //START HERE
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
                                                                $confirmtime =  date("h:ia, d M");
                                                                $status = 1; 
                                                                $username="";
                                                                $accountsentto="";
                                                                $addresssentto = '';
                                                                $manualstatus = 0;
                                                                $currencytag =$currency_to_tag;
                                                                $approvaltype = 1;
$rounded = floor($amttopay * 100) / 100;
$toshowamt=number_format($rounded, 2);

                                                                $message1 = "Convert $toshowamt $currency_from_name to $nairavalueis $currency_to_name";
                                                                // insert the values to the transation for recieve
                                                                $transtype1 = 2;
                                                                $yes=0;
                                                                if($to_is_crypto==1&&$from_is_crypto==1){
                                                                    $systemsendwith=4;//3 crypto to NGN 4 crypto to crytp
                                                                }else{
                                                                    $systemsendwith=3;//3 crypto to NGN 4 crypto to crytp
                                                                }
                                                                $empty=" "; 
                                                                $amttopayusd=0;
                                                                // TO
                                                                $swaptois="$currency_from_name to $currency_to_name";
                                                                $query1 = "INSERT INTO userwallettrans (ourrrate,payapiresponse,swapto,cointrackid,livecointype,systemsendwith,iscrypto,theusdval,btcvalue,bankaccsentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,swaptonametxt) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                $addTransaction1 = $connect->prepare($query1);
                                                                $addTransaction1 ->bind_param("sssssssssssssssssssssssssss",$conversion_rate,$empty,$swaptois,$coinprodtrackidisto,$cointypefrom ,$systemsendwith,$toiscrypto,$amttopayusd,$nairavalueis,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$ngnwallettrackid,$username,$currency_to_name);
                                                                if ($addTransaction1->execute()){
                                                                        
                                                                        // FROM
                                                                        //store swap history
                                                                        //   $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","SWAP",true,true,true);
                                                                        $reference= $orderId = createTransUniqueToken("SWAP", $user_id);
                                                                        $transtype1 = 3;
                                                                        $yes=1;
                                                                        $currencytag =$currency_from_tag;
                                                                        $addTransaction1 ->bind_param("sssssssssssssssssssssssssss",$conversion_rate,$empty,$swaptois,$coinprodtrackidisfrom,$cointypeto,$systemsendwith,$fromiscryoto,$amttopayusd,$nairavalueis,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username,$currency_to_name);
                                                                        $addTransaction1->execute();
                                                                    notify_admin_noti_b_bot($message1,$user_id); 
                                                                        
                                                                        
                                                                        
                                                                        $userid=$user_id;
                                                                        $sysgetdata =  $connect->prepare("SELECT email,phoneno,username,fcm FROM users WHERE id=?");
                                                                        $sysgetdata->bind_param("s",$userid);
                                                                        $sysgetdata->execute();
                                                                        $dsysresult7 = $sysgetdata->get_result();
                                                                        // check if user is sending to himself
                                                                        $datais=$dsysresult7->fetch_assoc();
                                                                        $ussernamesenttomail=$datais['email'];
                                                                        $usersenttophone=$datais['phoneno'];
                                                                        $usernamesentfrm=$datais['username'];
                                                                        $usersentfromfcm=$datais['fcm'];
                                                                        $reference=$orderId;
                                                                        $subject = swapPaymentSubject($userid,$reference); 
                                                                        $to = $ussernamesenttomail;
                                                                        $messageText = swapPaymentText($userid, $reference);
                                                                        $messageHTML = swapPaymentHTML($userid, $reference);
                                                                        sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                        sendUserSMS($usersenttophone,$messageText);
                                                                        // $userid,$message,$type,$ref,$status
                                                                        swap_coin_user_noti($userid,$reference,$message1);
                                                                        // NOTIFY TG
                                                                        $zero=0;
                                                                        $sysgetdata =  $connect->prepare("SELECT telegramswapchatid FROM admin WHERE telegramswapchatid!=?");
                                                                        $sysgetdata->bind_param("s", $zero);
                                                                        $sysgetdata->execute();
                                                                        $dsysresult7 = $sysgetdata->get_result();
                                                                        // check if user is sending to himself
                                                                        if($dsysresult7->num_rows>0){
                                                                            $datais=$dsysresult7->fetch_assoc();
                                                                            $finalchatid=$datais['telegramswapchatid'];
                                                                            $swapvia="Swap System";
                                                                            if($bilswap==1){
                                                                                 $swapvia="Bill System";
                                                                            }
                                                                            $response="*SWAPPING SYSTEM*\n\nThe following transaction has been processed automatically.\n\nUsername-$usernamesentfrm\nActivity:`Swap $swaptois`\nAmount-$amttopay $currency_from_name to $nairavalueis $currency_to_name\n\nOrder ID-`$reference`\nRate:$conversion_rate\nSwap Via:$swapvia";
                                                                            $finalbotid=$mainCardify_SWAP_noti_bot;
                                                                            $keyboard = [];
                                                                            replyuser($finalchatid, "0", $response, false, $keyboard,$finalbotid,"markdown");  
                                                                        }
                                                                        
                                                                        giveMarketerPointForEachUsers($userid,3,$reference);
                                                                        $data = [];
                                                                        $text= $message1;
                                                                        $status = true;
                                                                        $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
                                                                        respondOK($successData);
                                                    
                                                            } else{
                                                                          // return user money
                                                                if($from_is_crypto==1){
                                                                   $removed= payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                }else{
                                                                    $removed=payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                }
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
                                                                if($from_is_crypto==1){
                                                                   $removed= payAddUserSubBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                }else{
                                                                    $removed=payAddUserBalance($user_id,$amttopay,$currency,$wallettrackid);
                                                                }
                                                                
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
                                                        $errordesc="BAD PAY METHOD";
                                                        $linktosolve="https://";
                                                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                        $text="Wallet not found";
                                                        $method=getenv('REQUEST_METHOD');
                                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                        respondBadRequest($data);
                                        }    
                            } else{
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
                                    $text="You must be in level $user_level_allowed before you can swap this pair";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                            }   
                  }else{
                        $errordesc="BAD PAY METHOD";
                        $linktosolve="https://";
                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Minimum you can swap is $swap_min";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                  }
            }else{
                        $errordesc="BAD PAY METHOD";
                        $linktosolve="https://";
                        $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Swap pair not available";
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