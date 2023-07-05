<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/systems/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') == 'POST') {
    $maindata['frozedate']="";
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
        $user_id = getUserWithPubKey($connect, $user_pubkey);
        // check if the referral code field was passed 
        if ( !isset($_POST['code'] ) ) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required ref code field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $code = cleanme($_POST['code'],1);
        }
        // check if user have used the copon category before
        $brokecode=explode("_",$code);
        if(count($brokecode)==2){
            $category=$brokecode[0];
            $sentcode="%{$code}%";
            $sysgetdata =  $connect->prepare("SELECT id FROM coupon_used WHERE code LIKE ? AND userid=?");
            $sysgetdata->bind_param("ss",$sentcode,$user_id);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $num_row = $dsysresult7->num_rows;
            if ( $num_row > 0){
                    $errordesc="You can only use coupon code of the same category once";
                    $linktosolve="https://";
                    $hint=["Kindly pass value to the user_id, code field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="You can only use coupon code of the same category once";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
            }
        }

        if (empty($user_id) || empty($code) ){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, code field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the refcode in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        // To Check if Referral Code Exists
        // check if code is a reedem code
       
        $sysgetdata =  $connect->prepare("SELECT * FROM coupon_codes WHERE code= ?");
        $sysgetdata->bind_param("s", $code);
        $sysgetdata->execute();
        $dsysresult7 = $sysgetdata->get_result();
        $num_row = $dsysresult7->num_rows;
        if ( $num_row > 0){
                // `userid`, `code`, `amount`, `expiredate`, `startdate`, `currencytag`, `subwalletcointid`, `subwallet`, `status`,
                $getsys = $dsysresult7->fetch_assoc();
                $codeid = $getsys['id']; 
                $codeuserid = $getsys['userid']; 
                $codeamount = $getsys['amount']; 
                $codeexpiredate= $getsys['expiredate'];
                $codestartdate= $getsys['startdate'];
                $codecurrencytag= $getsys['currencytag'];
                $codesubwalletcointid= $getsys['subwalletcointid'];
                $codesubwallet= $getsys['subwallet'];
                $codestatus= $getsys['status'];
                // check if status is still on
                if($codestatus==1){
                     // check if code has expired or in the date range
                    if($codeexpiredate >= time() && $codestartdate<=time()){
                        // check if user id is not already used in coupon redeem tbale
                        $sysgetdata =  $connect->prepare("SELECT * FROM coupon_used WHERE userid= ? AND 	couponid=?");
                        $sysgetdata->bind_param("ss", $user_id,$codeid);
                        $sysgetdata->execute();
                        $dsysresult7 = $sysgetdata->get_result();
                        $num_row = $dsysresult7->num_rows;
                        if ( $num_row ==0){
                            // check if code is for a certain user
                            if($codeuserid!=0&&!empty($codeuserid)&&$codeuserid!=''){
                                // for a certain user
                                  //  check if the id is the user trying to reedem,
                                  if($codeuserid==$user_id){
                                       //  if yes check if its subwallet
                                       if($codesubwallet==1){
                                        //   subwallet bonus
                                        //  give user in his sub wallet
                                          // get user wallet details
                                            $sysgetdata =  $connect->prepare("SELECT trackid FROM usersubwallet WHERE userid= ? AND  currencytag=? AND coinsystrackid=?");
                                            $sysgetdata->bind_param("iss", $user_id,$codecurrencytag,$codesubwalletcointid);
                                            $sysgetdata->execute();
                                            $dsysresult7 = $sysgetdata->get_result();
                                            $num_row = $dsysresult7->num_rows;
                                            if ( $num_row >0){
                                                $getsys = $dsysresult7->fetch_assoc();
                                                $wallettrackid = $getsys['trackid']; 
                                                 if(payAddUserSubBalance($user_id,$codeamount,$codecurrencytag,$wallettrackid)){
                                                        //  store in history
                                                        $query1 = "INSERT INTO coupon_used (userid,code,couponid) VALUES (?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("sss",$user_id,$code,$codeid);
                                                        $addTransaction1->execute();
                                                              //  set coupon status to 0
                                                        $used=0;
                                                        $query1 = "UPDATE coupon_codes SET status=? WHERE id=?";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ss",$used,$codeid);
                                                        $addTransaction1->execute();
                                                        
                                                             
                                                    $maindata=[];
                                                    $errordesc = "";
                                                    $linktosolve = "https://";
                                                    $hint = "Referral Code Valid";
                                                    $errordata = [];
                                                    $text = "Code redeemed successfully.";
                                                    $status = true;
                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                    respondOK($data);
                                                        
                                                 }else{
                                                    $errordesc="Invalid Code";
                                                    $linktosolve="https://";
                                                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Error assigning bonus to you, try again later";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                                 }
                                            }else{
                                                 $errordesc="Invalid Code";
                                                $linktosolve="https://";
                                                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="Opps you dont have the wallet, kindly visit the wallet page to generate the wallet";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);  
                                            }
                                       }else{
                                            //   main wallet
                                            // get user wallet details
                                            $sysgetdata =  $connect->prepare("SELECT wallettrackid FROM userwallet WHERE userid= ? AND  currencytag=?");
                                            $sysgetdata->bind_param("ss", $user_id,$codecurrencytag);
                                            $sysgetdata->execute();
                                            $dsysresult7 = $sysgetdata->get_result();
                                            $num_row = $dsysresult7->num_rows;
                                            if ( $num_row >0){
                                                $getsys = $dsysresult7->fetch_assoc();
                                                $wallettrackid = $getsys['wallettrackid']; 
                                                  // /give user bonus in main wallet
                                                 if(payAddUserBalance($user_id,$codeamount,$codecurrencytag,$wallettrackid)){
                                                        //  store in history
                                                        $query1 = "INSERT INTO coupon_used (userid,code,couponid) VALUES (?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("sss",$user_id,$code,$codeid);
                                                        $addTransaction1->execute();
                                                        //  set coupon status to 0
                                                        $used=0;
                                                        $query1 = "UPDATE coupon_codes SET status=? WHERE id=?";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ss",$used,$codeid);
                                                        $addTransaction1->execute();
                                                        
                                                             
                                                    $maindata=[];
                                                    $errordesc = "";
                                                    $linktosolve = "https://";
                                                    $hint = "Referral Code Valid";
                                                    $errordata = [];
                                                    $text = "Code redeemed successfully.";
                                                    $status = true;
                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                    respondOK($data);
                                                        
                                                 }else{
                                                    $errordesc="Invalid Code";
                                                    $linktosolve="https://";
                                                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Error assigning bonus to you, try again later";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                                 }
                                            }else{
                                                $errordesc="Invalid Code";
                                                $linktosolve="https://";
                                                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="Opps you dont have the wallet, kindly visit the wallet page to generate the wallet";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);
                                            }
                                       }
                                  }else{
                                    $errordesc="Invalid Code";
                                    $linktosolve="https://";
                                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="You are not the user the code is meant for.";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data); 
                                  }
                            }else{
                                // for general user
                               //  if yes check if its subwallet
                                   if($codesubwallet==1){
                                    //   subwallet bonus
                                    //  give user in his sub wallet
                                      // get user wallet details
                                        $sysgetdata =  $connect->prepare("SELECT trackid FROM usersubwallet WHERE userid= ? AND  currencytag=? AND coinsystrackid=?");
                                        $sysgetdata->bind_param("sss", $user_id,$codecurrencytag,$codesubwalletcointid);
                                        $sysgetdata->execute();
                                        $dsysresult7 = $sysgetdata->get_result();
                                        $num_row = $dsysresult7->num_rows;
                                        if ( $num_row >0){
                                            $getsys = $dsysresult7->fetch_assoc();
                                            $wallettrackid = $getsys['trackid']; 
                                             if(payAddUserSubBalance($user_id,$codeamount,$codecurrencytag,$wallettrackid)){
                                                    //  store in history
                                                    $query1 = "INSERT INTO coupon_used (userid,code,couponid) VALUES (?,?,?)";
                                                    $addTransaction1 = $connect->prepare($query1);
                                                    $addTransaction1 ->bind_param("sss",$user_id,$code,$codeid);
                                                    $addTransaction1->execute();
                                                    
                                                         
                                                    $maindata=[];
                                                    $errordesc = "";
                                                    $linktosolve = "https://";
                                                    $hint = "Code Valid";
                                                    $errordata = [];
                                                    $text = "Code redeemed successfully.";
                                                    $status = true;
                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                    respondOK($data);
                                             }else{
                                                $errordesc="Invalid Code";
                                                $linktosolve="https://";
                                                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="Error assigning bonus to you, try again later";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);
                                             }
                                        }else{
                                             $errordesc="Invalid Code";
                                            $linktosolve="https://";
                                            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Opps you dont have the wallet, kindly visit the wallet page to generate the wallet";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);  
                                        }
                                   }else{
                                        //   main wallet
                                        // get user wallet details
                                        $sysgetdata =  $connect->prepare("SELECT wallettrackid FROM userwallet WHERE userid= ? AND  currencytag=?");
                                        $sysgetdata->bind_param("ss", $user_id,$codecurrencytag);
                                        $sysgetdata->execute();
                                        $dsysresult7 = $sysgetdata->get_result();
                                        $num_row = $dsysresult7->num_rows;
                                        if ( $num_row >0){
                                            $getsys = $dsysresult7->fetch_assoc();
                                            $wallettrackid = $getsys['wallettrackid']; 
                                              // /give user bonus in main wallet
                                             if(payAddUserBalance($user_id,$codeamount,$codecurrencytag,$wallettrackid)){
                                                    //  store in history
                                                    $query1 = "INSERT INTO coupon_used (userid,code,couponid) VALUES (?,?,?)";
                                                    $addTransaction1 = $connect->prepare($query1);
                                                    $addTransaction1 ->bind_param("sss",$user_id,$code,$codeid);
                                                    $addTransaction1->execute();
                                                    
                                                    $maindata=[];
                                                    $errordesc = "";
                                                    $linktosolve = "https://";
                                                    $hint = "Referral Code Valid";
                                                    $errordata = [];
                                                    $text = "Code redeemed successfully.";
                                                    $status = true;
                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                    respondOK($data);
                                    
                                             }else{
                                                $errordesc="Invalid Code";
                                                $linktosolve="https://";
                                                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="Error assigning bonus to you, try again later";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);
                                             }
                                        }else{


                                            if($codecurrencytag=="NGNBILLS55"){
                                                if(payAddUserNgnBillsBalance($user_id,$codeamount)){
                                                        //  store in history
                                                        $query1 = "INSERT INTO coupon_used (userid,code,couponid) VALUES (?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("sss",$user_id,$code,$codeid);
                                                        $addTransaction1->execute();
                                                        //  set coupon status to 0
                                                        $used=0;
                                                        $query1 = "UPDATE coupon_codes SET status=? WHERE id=?";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ss",$used,$codeid);
                                                        $addTransaction1->execute();
                                                        $message1="Used coupon code $code to get $codeamount NGN";
                                                        notify_admin_noti_b_bot($message1,$user_id);
                                            
                                                        $maindata=[];
                                                        $errordesc = "";
                                                        $linktosolve = "https://";
                                                        $hint = "Referral Code Valid";
                                                        $errordata = [];
                                                        $text = "Code redeemed successfully.";
                                                        $status = true;
                                                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                        respondOK($data);
                                                } else{
                                                    // add usd bills top up with coupon here bwfore the else statement incase requetsed
                                                    $errordesc="Invalid Code";
                                                    $linktosolve="https://";
                                                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Error assigning bonus to you, try again later";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                                }
                                            }else{
                                                $errordesc="Invalid Code";
                                                $linktosolve="https://";
                                                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="You are not the user the code is meant for.";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data); 
                                            }
                                        }
                                   }
                            }
                            
                        }else{
                            $errordesc="Invalid Code";
                            $linktosolve="https://";
                            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="You have already used this code";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
                    }else{
                        $errordesc="Invalid Code";
                        $linktosolve="https://";
                        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Code has expired";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }
                }else{
                    $errordesc="Invalid Code";
                    $linktosolve="https://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Code is not active";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
                
            // if code is not for certain user
            //  if yes check if its subwallet
            //  if yes get currency tag and subwallet tid and amount and update for the user  and record in coupon reedem table
        }else{
            // if no then process referal code
            $sysgetdata =  $connect->prepare("SELECT id FROM users WHERE refcode = ?||username=?");
            $sysgetdata->bind_param("ss", $code, $code);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $num_row = $dsysresult7->num_rows;
            if ( $num_row == 0){
                $errordesc="Invalid Refcode";
                $linktosolve="https://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="This refcode does not exist.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            
            }else{
                    $getsys = $dsysresult7->fetch_assoc();
                    $owner_of_ref_id = $getsys['id']; 
                    if ($owner_of_ref_id == $user_id){
                        $errordesc="Invalid referral code";
                        $linktosolve="https://";
                        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Cannot register your own referral code.";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }
                    else{
                        // To Check if Referral Code Has Been Used...
                        $query = 'SELECT * FROM users WHERE id = ?';
                        $stmt = $connect->prepare($query);
                        $stmt->bind_param("i",$user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $getsys = $result->fetch_assoc();
                        $owner_of_ref_id = $getsys['referby']; 
                        if ($owner_of_ref_id == "" || empty($owner_of_ref_id )){
                            $stmt->close();
                                $updatePassQuery = "UPDATE users SET referby = ? WHERE id = ?";
                                $updateStmt = $connect->prepare($updatePassQuery);
                                $updateStmt->bind_param('si', $code, $user_id);
                                $updateStmt->execute();
                                if ($updateStmt->affected_rows > 0 ){
                                    $maindata=[];
                                    $errordesc = "";
                                    $linktosolve = "https://";
                                    $hint = "Referral Code Valid";
                                    $errordata = [];
                                    $text = "Referral Code was successfully recorded.";
                                    $status = true;
                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                    respondOK($data);
                                
                                }else{
                                     //invalid input/ server error
                                     $errordesc="Bad request";
                                     $linktosolve="https://";
                                     $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                     $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                     $text="Referral Code was failed to be recorded.";
                                     $method=getenv('REQUEST_METHOD');
                                     $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                     respondBadRequest($data);
                                }
                        }else{
                            $errordesc="Invalid Refcode";
                            $linktosolve="https://";
                            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Already Reedeemed a referral code.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
                    }
                
            }
        }
        // TEST CASE
        // input ref code
        //  input my own ref code
        //  re input same ref code after inputting success
        //  input another code when already inputed
        
        // TEST CASE REDEEM
        // use one code
        // re use code
        // use expired code
        // check if code is for s single user
        //  test bonus for btc
        
        
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