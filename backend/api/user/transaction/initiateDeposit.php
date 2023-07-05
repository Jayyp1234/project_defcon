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
if ($method  == 'POST') {
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
    $user_pubkey = cleanme($decodedToken->usertoken);
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
            
        }
        else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
        
        if ( !isset($_POST['amount']) || !isset($_POST['currency']) || !isset($_POST['type'])|| !isset($_POST['wallettrackid'])){
            $errordesc="All fields must be passed";
            $linktosolve="https://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="All fields must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
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
        }
        else{
            $merchantid="";
            $amount = cleanme($_POST['amount']);
            $type =  cleanme($_POST['type']);
            $currency=cleanme($_POST['currency']);
            $wallettrackid =cleanme($_POST['wallettrackid']);
            // GET USER DETAILS FROM DB
            $checkdata =  $connect->prepare("SELECT email,fname,lname,phoneno FROM users WHERE id=? ");
            $checkdata->bind_param("s", $userid);
            $checkdata->execute();
            $dresultUser = $checkdata->get_result();
            $foundUser= $dresultUser->fetch_assoc();
            $email = $foundUser['email'];
            $fname= $foundUser['fname'];
             $lname = $foundUser['lname'];
            $phone=$foundUser['phoneno'];
            
            // $email = cleanme($_POST['email']);
            // $fname = cleanme($_POST['firstname']);
            // $lname = cleanme($_POST['lastname']);
            // $phone = cleanme($_POST['phone']);
    
            if(isset($_POST['merchant_id'])){
                $merchantid=cleanme($_POST['merchant_id']);
            }
        

        if (empty($userid)  || empty($amount)|| !is_numeric($amount)  || empty($currency)||empty($wallettrackid)){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass a valid value to fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else  if ($type == 3 && (empty($fname) || empty($lname) || empty($email)||  empty($phone))){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Update your profile before this can be processed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if ($amount<=0 || ! is_numeric($amount)){
            // Insert all fields
            $errordesc = "Insert all fields";
            $linktosolve = 'https://';
            $hint = "Invalid amount";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }else if ($type==5 && empty($merchantid)){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass a valid value to fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else{
            // preventing the use of another payment method to pay for another currency
            // only main wallet can use this API
            $active=1;
            $mainorsubwallet=1;
            $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencyreceivemethods WHERE currencytag=? AND systemtouseid=? AND status=? AND mainorsubwallet=?");
            $sysgetdata->bind_param("ssss", $currency,$type,$active,$mainorsubwallet);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys = $dsysresult7->num_rows;
             // preventing funding with out the currecncy trackid
            $sysgetdata =  $connect->prepare("SELECT currencytag FROM userwallet WHERE currencytag=? AND wallettrackid=?");
            $sysgetdata->bind_param("ss", $currency,$wallettrackid);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys2 = $dsysresult7->num_rows;
            if($getsys>0&&$getsys2>0){
                
                // systemtouseid 1- Paystack, 2- Monify, 3- 1app, 4- Crypto Biz 5-PeerstaCK 6- any other new
                if ($type == 1){// paystack deposit
                    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                    $transhash = '';
                    // generating  order ref
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    // $orderId = createUniqueToken(23,"userwallettrans","orderid","PS",true,true,true);//PS ref is case sensitive
                    $orderId = createTransUniqueToken("PS", $userid);
                    $ordertime = date("h:ia, d M");
                    $confirmtime = '';
                    $status = 0; 
                    $amttopay = $amount;
                    $addresssentto = '';
                    $manualstatus = 0;
                    $currencytag = $currency;
                    $approvaltype = 1;
                    $message1 = "Deposited NGN ".$amount."  with paystack";
                    $url = paystackPaywithCard($amount,$email,$orderId);
                    // insert the values to the transation for recieve
                    $transtype1 = 2;
                    $empty=" ";
                    $systempaidwith=1;
                    $query1 = "INSERT INTO userwallettrans (systempaidwith,payapiresponse,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $addTransaction1 = $connect->prepare($query1);
                    $addTransaction1 ->bind_param("sssssssssssssssss", $systempaidwith,$empty,$userid,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid);
                    
                    if ($addTransaction1->execute()){
                        notify_admin_noti_b_bot($message1,$userid);
                        $maindata['redirect_url']= $url;
                        $errordesc = "";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $text = "Data found";
                        $method = getenv('REQUEST_METHOD');
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                    else{
                        echo 'error';
                    }
                }
                else if ($type == 3){// 1 app deposit
                    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                    $transhash = '';
                    // generating  order ref
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    // $orderId = createUniqueToken(18,"userwallettrans","orderid","1APP",true,true,true);
                    $orderId = createTransUniqueToken("1A", $userid);
                    $ordertime = date("h:ia, d M");
                    $confirmtime = '';
                    $status = 0; 
                    $amttopay = $amount;
                    $addresssentto = '';
                    $manualstatus = 0;
                     $currencytag = $currency;
                    $approvaltype = 1;
                    $message1 = "Deposited NGN ".$amount." with 1App";
                    $url = oneappPaywithCard($amount,$email,$orderId,$phone,$fname,$lname);
                    // insert the values to the transation for recieve
                    $transtype1 = 2;
                    $empty="";
                    $systempaidwith=3;
                    $query1 = "INSERT INTO userwallettrans (systempaidwith,payapiresponse,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $addTransaction1 = $connect->prepare($query1);
                    $addTransaction1 ->bind_param("sssssssssssssssss",$systempaidwith,$empty,$userid,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid);
                    
                    if ($addTransaction1->execute()){
                        notify_admin_noti_b_bot($message1,$userid);
                        $maindata['redirect_url']= $url;
                        $errordesc = "";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $text = "Data found";
                        $method = getenv('REQUEST_METHOD');
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                    else{
                        echo 'error';
                    }
                }else if($type==5){// peerstack deposit
                    // get merchant agent
                    $peerstack_fee=0;
                    $query = 'SELECT depositcharge,id FROM peerstackmerchants WHERE merchant_trackid= ?';
                    $stmt = $connect->prepare($query);
                    $stmt->bind_param("s",$merchantid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $num_row = $result->num_rows;
                    if($num_row>0){
                        $agentdata=$result->fetch_assoc();
                        $peerstack_fee=$agentdata['depositcharge'];
                        $agentid =$agentdata['id'];
                            $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                            $transhash = '';
                            // generating  order ref
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            // agent num, date and transaction number
                            

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
                            $orderId=$orderId."D";
                            // $orderId = createUniqueToken(14,"userwallettrans","orderid","PERS",true,true,true);//PS ref is case sensitive
                            $ordertime = date("h:ia, d M");
                            $confirmtime = '';
                            $status = 0; 
                            $amttopay = $amount-$peerstack_fee;
                            $amountsentin= $amount;
                            $addresssentto = '';
                            $manualstatus = 0;
                            $currencytag = $currency;
                            $approvaltype = 1;
                            $message1 = "Deposited NGN ".$amttopay."  with peerstack";
                            // $url = paystackPaywithCard($amount,$email,$orderId);
                            // insert the values to the transation for recieve
                            $transtype1 = 2;
                            $empty=" ";
                            $systempaidwith=5;
                            $query1 = "INSERT INTO userwallettrans (bill_profit_loose,peerstack_fee,amountsentin,systempaidwith,peerstack_agent,payapiresponse,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                            $addTransaction1 = $connect->prepare($query1);
                            $addTransaction1 ->bind_param("sssssssssssssssssssss",$peerstack_fee,$peerstack_fee,$amountsentin, $systempaidwith,$merchantid,$empty,$userid,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid);
                            
                            if ($addTransaction1->execute()){
                                // sumtractmerchant bal
                                $checkdata =  $connect->prepare("UPDATE peerstackmerchants  SET active_balance=active_balance-?,active_escrow_balance=active_escrow_balance+? WHERE merchant_trackid=?");
                                $checkdata->bind_param("sss",$amttopay,$amttopay,$merchantid);
                                $checkdata->execute();
                                $dresult = $checkdata->get_result();
                                $checkdata->close();
                                notify_admin_noti_b_bot($message1,$userid);
                                $maindata['orderid']=$orderId;
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
                                $errordesc="BAD PAY METHOD";
                                $linktosolve="https://";
                                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="An error occured please try again in 1 minute";
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
                $text="Payment method passed not available";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }   
            
        }
        }
        }
}else{
    $errordesc = "Method not allowed";
    $linktosolve = "https://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>