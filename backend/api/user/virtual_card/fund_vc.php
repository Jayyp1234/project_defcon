<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);


include "../../../config/utilities.php";

   // in fund virtual card, // add fund wallet history 
//   store profite in creating card for admin, 
// admin approve processing fund

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') === 'POST'){
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
        else{
            $user_id = getUserWithPubKey($connect, $user_pubkey);
        }
        
        $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel FROM users WHERE id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
        
                // check if NGN is allowed to fund
        $query = 'SELECT allow_ngn_fund_vc FROM systemsettings WHERE id=1';
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row =  mysqli_fetch_assoc($result);
        $allowedngnfund=$row['allow_ngn_fund_vc'];
        
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
        $unavailable=0;
        $htmlni=0;
        if(isset($_POST['showhtml'])){
            //  $htmlni=1;
        }
        if($unavailable==1){
            $errordesc="Service temporarily unavailable. We apologize for the inconvenience.";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            if($htmlni==1){
                $text="<b>Service temporarily unavailable. We apologize for the inconvenience.</b><br><a style='font-size: 14px;' target='_blank' href='https://news.intercom.com/cardify-technology-limited/news/18870-upgrades-to-our-virtual-card'>Click here to learn more</a>";
            }else{
                $text="Service temporarily unavailable. We apologize for the inconvenience.";
            }
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        
    
        //collect input and validate it
        // check if the current password field was passed 
        if (!isset($_POST['currencytid']) || !isset($_POST['cardtid'])|| !isset($_POST['amount'])|| !isset($_POST['pin'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if ($_POST['amount']<=0 || ! is_numeric($_POST['amount'])){
            // Insert all fields
            $errordesc = "Invalid amount";
            $linktosolve = 'https://';
            $hint = "Invalid amount";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }else if ($_POST['amount']>5000){
            // Insert all fields
            $errordesc = "Apologies, but it's not possible to fund an amount exceeding $5000 at once. If you wish to fund more than $5000, you will need to divide it into separate transactions.";
            $linktosolve = 'https://';
            $hint = "Apologies, but it's not possible to fund an amount exceeding $5000 at once. If you wish to fund more than $5000, you will need to divide it into separate transactions.";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        } else{
             $currencytid = cleanme($_POST['currencytid']);
             $cardptid = cleanme($_POST['cardtid']);
             $amount = cleanme($_POST['amount']);// amount in usd
             $pin = cleanme($_POST['pin']);
        }
        

   
        //  check if its naira funduning or crypto
        $currencytagis="NGNT55";
        $itsnairafund=0;
        $coinrate=0;
        $amounttodeduct=0;
        $userwalletbal=0;
          //  check if currency can fund wallet and check if naira fund is active
        $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=? AND wallettrackid=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sss",$user_id, $currencytagis,$currencytid);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $users = $result->fetch_assoc();
            $itsnairafund=1;
            $userwalletbal=$users['walletbal']; 

        }
          

        $verifypass =check_pass($pin,$passpin);
        
        // check if card  is active and exist   //  check if card is active
        $active=1;
        $checkdata =  $connect->prepare("SELECT customer_id,vc_type_tid,wallet_id,vc_card_id,freeze FROM  vc_customer_card WHERE trackid=? AND status=? AND user_id=?");
        $checkdata->bind_param("sii",$cardptid,$active,$user_id);
        $checkdata->execute();
        $dresult2 = $checkdata->get_result();
       
        // check if user subwallet is active and exist
        $checkdata =  $connect->prepare("SELECT id,coinsystemtag,walletbal,currencytag FROM  usersubwallet WHERE trackid=? AND userid=?");
        $checkdata->bind_param("si",$currencytid,$user_id);
        $checkdata->execute();
        $dresult3 = $checkdata->get_result();
        
        if (empty($currencytid)||empty( $cardptid)||empty($amount)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the user_id, username field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if (!$verifypass) {
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Invalid pin.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else  if($dresult2->num_rows==0){
            $errordesc="Card is not active.";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Card is not active.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else if($allowedngnfund==0 && $itsnairafund==1){
            $errordesc="Sub wallet does not exist.";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Funding with NGN currently not allowed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if($dresult3->num_rows==0 && $itsnairafund==0){
            $errordesc="Sub wallet does not exist.";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Sub wallet does not exist.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else  if($amount <=0 || !is_numeric($amount)){ 
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid amount to deduct";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        
        }else  if($amount <5){ 
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Minimum you can fund is \$5";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        
        }else if($userlevel<2){
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please upgrade your level to level 2";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }else{
            // card details
            $coinprodtrackidis=$cointype="";
            $cardTypeData= $dresult2->fetch_assoc();
            $customer_id = $cardTypeData['customer_id'];
            $maincardtid= $cardTypeData['vc_type_tid'];
            //  get user card wallet id
            $wallet_id= $cardTypeData['wallet_id'];
            $vc_card_id= $cardTypeData['vc_card_id'];
            $vc_card_freeze= $cardTypeData['freeze'];
            if($vc_card_freeze==1){
                        $errordesc="Please unfreeze your card before you can add funds.";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please unfreeze your card before you can add funds.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else{
                $active=1;
                $checkdata =  $connect->prepare("SELECT id,max_vc_generate,creation_fee,currency,funding_percent,maintanace_fee,second_fund_fee,supplier,naira_fund_exhange_rate,need_activation,name,cardbrand,provider_fund_percent FROM  vc_type WHERE trackid=? AND status=?");
                $checkdata->bind_param("si",$maincardtid,$active);
                $checkdata->execute();
                $dresult4 = $checkdata->get_result();
                if($dresult4->num_rows>0){
                            $vc_typedata= $dresult4->fetch_assoc();
                            $vc_typeMainTainfee =$vc_typedata['maintanace_fee'];
                            $need_activation= $vc_typedata['need_activation'];
                            $cardname= $vc_typedata['name'];
                            $vc_typeSecFundFee= $vc_typedata['second_fund_fee'];
                            $cardfunding_percent= $vc_typedata['funding_percent'];
                            $cardfunding_percent_profit= $vc_typedata['provider_fund_percent'];
                            $cardCurrency=$vc_typedata['currency'];
                            $cardsupplier=$vc_typedata['supplier'];
                            $cardbrand=$vc_typedata['cardbrand'];
                            $naira_fund_exhange_rate= $vc_typedata['naira_fund_exhange_rate'];
                            
                            // check if NGN is allowed to fund
                            $query = 'SELECT allow_ngn_fund_vc,amountto_add_ngnrate,activate_rate_flaunt FROM systemsettings WHERE id=1';
                            $stmt = $connect->prepare($query);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row =  mysqli_fetch_assoc($result);
                            $allowedngnfund=$row['allow_ngn_fund_vc'];
                            $amountto_add_ngnrate=$row['amountto_add_ngnrate'];
                            $activate_rate_flaunt=$row['activate_rate_flaunt'];
                            if($activate_rate_flaunt==1){
                                $currency="USD";
                                $fund=1;
                                $coinrate=getLiveNGNtoUSDRate($currency,$fund);//getNGNtoUSDRate($currency,$fund);
                                $naira_fund_exhange_rate =$coinrate + $amountto_add_ngnrate;
                            }
                
                            $coinrate =$naira_fund_exhange_rate;
    
                            if($need_activation==1 && strtolower($cardbrand)=="visa"){
                                $cardbrand="Visa2";
                            }
                            $cardsuppliername="";
                            if($cardsupplier==1){
                                $cardsuppliername="Sudo";
                            }else if($cardsupplier==2){
                                $cardsuppliername="Bridgecard";
                            }
                            
                            
                            $currency="USD";
                            $fund=1;
                             $getlivecoinrate=900;
                            if($cardsupplier==1){
                            $getlivecoinrate= getLiveNGNtoUSDRate($currency,$fund);
                            }else if($cardsupplier==2){
                              $getlivecoinrate= getLiveBCNGNtoUSDRate($currency,$fund);   
                            }
                            
                            
                            if($getlivecoinrate>$coinrate && $itsnairafund==1){
                                       // inform admin telegram
                                    $zero=0;
                                    $sysgetdata =  $connect->prepare("SELECT telegramvc_sys_code FROM admin WHERE telegramvc_sys_code!=?");
                                    $sysgetdata->bind_param("s", $zero);
                                    $sysgetdata->execute();
                                    $dsysresult7 = $sysgetdata->get_result();
                                    // check if user is sending to himself
                                    if($dsysresult7->num_rows>0){
                                            $datais=$dsysresult7->fetch_assoc();
                                            $finalchatid=$datais['telegramvc_sys_code'];
                                            $response="*RATE IS ABOVE WHAT YOU SET* $user_id Rate set is $coinrate NGN and live rate is $getlivecoinrate NGN";
                                            $finalbotid=$mainCardify_CARD_noti_bot;
                                            $keyboard = [];
                                            replyuser($finalchatid, "0", $response, false, $keyboard,$finalbotid,"markdown"); 
                                    }
                                // Insert all fields
                                // $errordesc = "An error occured, while funding account, please try again later(01)";
                                // $linktosolve = 'https://';
                                // $hint = "An error occured, while funding account, please try again later(01)";
                                // $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                // $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                                // respondBadRequest($data);
                            }
                            
                            
                            // get card type details
                            $mainamount=0;
                            $fundfee=0;
                            // remove fund charges if not card creation
                            // charge funding fee
                            //cheeck trans if user have fund card this month
                            // if yes charge second_fund_fee
                            //if no charge second_fund_fee + maintanance
    
                            // count sum user sent a day
                            $selectedmonth= date('n');//12
                            $selectedyear= date('Y');//2022
                            $todaydayis=date('d');
                            $donesuccess=1;
                            // SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=2 AND MONTH(created_at) = 1 AND YEAR(created_at)=2023 AND DAY(created_at)=19 AND status=1 AND userid=7 GROUP BY userid
                            $getexactdata =  $connect->prepare("SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE wallettrackid=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=?  GROUP BY userid");
                            $getexactdata->bind_param("sssss", $cardptid,$selectedmonth,$selectedyear,$donesuccess,$user_id);
                            $getexactdata->execute();
                            $rresult2 = $getexactdata->get_result();
                            $totaldone_adayis = $rresult2->num_rows;
                            if ($totaldone_adayis>0) {
                                $fundfee=$vc_typeSecFundFee+(($cardfunding_percent/100) * $amount);
                                $profitfundfee=$vc_typeSecFundFee+((($cardfunding_percent-$cardfunding_percent_profit)/100) * $amount);
                                
                            }else{
                                 $profitfundfee=$vc_typeSecFundFee+((($cardfunding_percent-$cardfunding_percent_profit)/100) * $amount);
                                $fundfee= $vc_typeSecFundFee+(($cardfunding_percent/100) * $amount)+$vc_typeMainTainfee;
                            }
                            $mainamount=$amount + $fundfee;
                            
                        
                            $fundMethod="";
                            if($dresult3->num_rows>0){
                                // if its sub wallet
                                // subwallet details
                                $foundUserWallet= $dresult3->fetch_assoc();
                                $coinsystemtag = $foundUserWallet['coinsystemtag'];
                                $userwalletbal = $foundUserWallet['walletbal'];
                                $currencytagis=$foundUserWallet['currencytag'];
                      
                                // check if wallet is allowed to fund Virtual card
                                       //  check if currency is active
                                         //  check if currency can fund wallet and check if naira fund is active
                                $canfund=1;
                                $sql = "SELECT id,producttrackid,cointype,roundto_dp,name,crypto_covinence_fee FROM coinproducts WHERE subwallettag=? AND can_fund_vc=? AND status=?";
                                $getCoinProduct = $connect->prepare($sql);
                                $getCoinProduct->bind_param('ssi',$coinsystemtag,$canfund,$canfund);
                                $getCoinProduct->execute();
                                $Coinpresult = $getCoinProduct->get_result();
                                 if($Coinpresult->num_rows>0){
                                        // coin details
                                        $coinPData= $Coinpresult->fetch_assoc();
                                        $coinprodtrackidis =  $coinPData['producttrackid'];
                                        $cointype=$coinPData['cointype'];
                                        $roundto_dp=$coinPData['roundto_dp'];
                                        $fundMethod=$coinPData['name'];
                                        //calculat crypto xonvinient fee
                                        $cryptoFeePercent=$coinPData['crypto_covinence_fee'];
                                        $cryptofeetotake=($cryptoFeePercent/100) * $amount;
                                        $fundfee =$fundfee+$cryptofeetotake;
                                        $mainamount = $mainamount + $cryptofeetotake;
                                          // check if user hv  the fund
                                         $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis); 
                                         if($getlivevalu>0){
                                             // get total price to pay from card
                                             //  get user balance
                                               //  convert anout from USD to curreny valeu
                                             //  get live rate and use to divide above to get coin value to pay, then check user balnce for d pay
                                                  $amounttodeduct= round($mainamount/$getlivevalu,$roundto_dp);
                                         }else{
                                            $errordesc="Bad request";
                                            $linktosolve="htps://";
                                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Error getting Curency data, try again later";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data); 
                                         }
                                 }else{
                                        $errordesc="Bad request";
                                        $linktosolve="htps://";
                                        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                        $text="Invalid Curency selected";
                                        $method=getenv('REQUEST_METHOD');
                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                        respondBadRequest($data); 
                                 }
                            }else{
                                $fundMethod="Naira Wallet";
                                $amounttodeduct= $mainamount * $coinrate;
                            }
                            
                            // sms mail noti for who receive
                            $sysgetdata11 =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
                            $sysgetdata11->bind_param("s",$user_id);
                            $sysgetdata11->execute();
                            $dsysresult71 = $sysgetdata11->get_result();
                            // check if user is sending to himself
                            $datais11=$dsysresult71->fetch_assoc();
                            $ussernamesenttomail=$datais11['email'];
                            $usersenttophone=$datais11['phoneno'];
                            $userusername=$datais11['username'];
                            
                        //  echo $userwalletbal;
                            //if amount is valid
                            if($amounttodeduct <=0 || !is_numeric($amounttodeduct)){
                                    $errordesc="Bad request";
                                    $linktosolve="htps://";
                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Invalid amount to deduct";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                            // check if wallet is allowed to fund Virtual card
                            }else if($userwalletbal<$amounttodeduct){   //  check if use hv fund
                                    $errordesc="Bad request";
                                    $linktosolve="htps://";
                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Insufficient fund";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                                    // check if wallet is allowed to fund Virtual card
                            }else{
                                   //  debit suer fund
                                    $funddeducted=false;
                                    $iscryptotrans=0;;
                                    if($itsnairafund==1){
                                        $iscryptotrans=0;
                                        $funddeducted=payDeductUserBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                    }else if($itsnairafund==0){
                                        $iscryptotrans=1;
                                        $funddeducted=payRemoveUserSubBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                    }
                                     //   if fund deduxted
                                                if($funddeducted){
                                                            //  creat trans for debit as pending
                                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                        $transhash = '';
                                                        // generating  order ref
                                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                        // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","VC",true,true,true);
                                                        $reference=$orderId = createTransUniqueToken("FVC", $user_id);
                                                        $ordertime = date("h:ia, d M");
                                                        $confirmtime = '';
                                                        $status = 1; 
                                                        $username="";
                                                        $accountsentto="";
                                                        $amttopay = $amounttodeduct;
                                                        $addresssentto = '';
                                                        $manualstatus = 0;
                                                        $currencytag = $currencytagis;//$currencytid;
                                                        $approvaltype = 1;
                                                        $message1 = "Fund Virtual Card for $amounttodeduct";
                                                        // insert the values to the transation for recieve
                                                        $transtype1 = 1;
                                                        $yes=0;
                                                        $systemsendwith=6;
                                                        $empty=" ";
                                                        $virtual_card_trans=0;
                                                        $profitfee=0;
                                                        $query1 = "INSERT INTO userwallettrans (bill_profit_loose,vc_maintainfee,fund_fee,virtual_card_trans,ourrrate,payapiresponse,swapto,cointrackid,livecointype,systemsendwith,iscrypto,theusdval,btcvalue,bankaccsentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                        $addTransaction1 = $connect->prepare($query1);
                                                        $addTransaction1 ->bind_param("ssssssssssssssssssssssssssssss",$profitfee,$vc_typeMainTainfee,$fundfee,$virtual_card_trans,$coinrate,$empty,$empty,$coinprodtrackidis,$cointype,$systemsendwith,$iscryptotrans,$amount,$amounttodeduct,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$currencytid,$username);
                                                        if ($addTransaction1->execute()){
                                                            //   START HERE
                                                                //store swap history
                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","VC",true,true,true);
                                                                $reference=$orderId = createTransUniqueToken("FVC", $user_id);
                                                                $transtype1 = 2;
                                                                $currencytid=$cardptid;
                                                                 $currencytag="USD256";
                                                                $systemsendwith=6;
                                                                $virtual_card_trans=1;
                                                                $profitfee=$profitfundfee;
                                                                $addTransaction1 ->bind_param("ssssssssssssssssssssssssssssss",$profitfee,$vc_typeMainTainfee,$fundfee,$virtual_card_trans,$coinrate,$empty,$empty,$coinprodtrackidis,$cointype,$systemsendwith,$iscryptotrans,$amount,$amounttodeduct,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$currencytid,$username);
                                                                if ($addTransaction1->execute()){
                                                                        $narration="Fund Virtual card";
                                                                        $payref=$orderId;
                                                                        $currency=$cardCurrency;
                                                                        $userid=$user_id;
                                                                        // get trans id
                                                                        
                                                                        // get trans details
                                                                        $transid="";
                                                                        $getexactdata =  $connect->prepare("SELECT id FROM 	userwallettrans WHERE orderid=?");
                                                                        $getexactdata->bind_param("s",$payref);
                                                                        $getexactdata->execute();
                                                                        $rresult2 = $getexactdata->get_result();
                                                                        $num = $rresult2->num_rows ;
                                                                        if ($num>0) {
                                                                        $ddatasent=$rresult2->fetch_assoc();
                                                                        $transid=$ddatasent['id'];
                                                                        
                                                                        }
                                                                                  //  call fund card
                                                                                     //  if return true change trabs status to 1 else set to 2 for admin apporval
                                                                                    //  $cardsupplier
                                                                                    $cardfunded=false;
                                                                                         if($amount>450){
                                                                                        $cardfunded=6;
                                                                                    }else{
                                                                                        if($cardsupplier==1){
                                                                                            $cardfunded=fundUserWallet($wallet_id,$amount,$narration,$payref,$currency,$userid);
                                                                                        }else if($cardsupplier==2){
                                                                                            $cardfunded=fundUserBCWallet($vc_card_id,$amount,$narration,$payref,$currency,$userid);
                                                                                        }
                                                                                    }
                                                                                    
                                                                               
                                                                                    //0= not started at all,1=success, 2= no fund,3 = server error 4 empty order id 5 duplicate trans blocked
                                                                        if($cardfunded==1){
                                                                            
                                                                                     // sms mail noti for who receive
                                                                                $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
                                                                                $sysgetdata->bind_param("s",$user_id);
                                                                                $sysgetdata->execute();
                                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                                // check if user is sending to himself
                                                                                $datais=$dsysresult7->fetch_assoc();
                                                                                $ussernamesenttomail=$datais['email'];
                                                                                $usersenttophone=$datais['phoneno'];
                                                                                $userusername=$datais['username'];
                                                                                $reference=$orderId;
                                                                                $userid=$user_id;
                                                                                $subject = fundVirtualCardSuccessSubject($userid,$reference); 
                                                                                $to = $ussernamesenttomail;
                                                                                $messageText =fundVirtualCardSuccessText($userid, $reference);
                                                                                $messageHTML = fundVirtualCardSuccessHTML($userid, $reference);
                                                                                sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                sendUserSMS($usersenttophone,$messageText);
                                                                                // $userid,$message,$type,$ref,$status
                                                                                fund_vc_user_noti($userid,$reference);
                                                                                 notify_admin_noti_b_bot($message1,$userid);
                                                                                 
                                                                                    //  store admin profit table data
                                                                        //  id, amount,profit,profittype,
                                                                        $pforittype=1;
                                                                        $query1 = "INSERT INTO virtualcard_profit (amount, profit, profittype,orderid) VALUES (?,?,?,?)";
                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                        $addTransaction1 ->bind_param("ssss",$mainamount,$fundfee,$pforittype,$orderId);
                                                                        $addTransaction1->execute();
                                                                        
                                                                              // inform admin telegram
                                    $zero=0;
                                    $sysgetdata =  $connect->prepare("SELECT telegramvc_sys_code FROM admin WHERE telegramvc_sys_code!=?");
                                    $sysgetdata->bind_param("s", $zero);
                                    $sysgetdata->execute();
                                    $dsysresult7 = $sysgetdata->get_result();
                                    // check if user is sending to himself
                                    if($dsysresult7->num_rows>0){
                                            $datais=$dsysresult7->fetch_assoc();
                                            $finalchatid=$datais['telegramvc_sys_code'];
                                            $response="*FUND VIRTUAL CARD*%0A%0AA User with following detail funded his/her virtual card.%0A%0AUsername-$userusername%0AWallet-$fundMethod%0AAmount-`\$$amount`%0AOrder ID-`$reference`%0ACard Name-`$cardname`%0ACard Type-`$cardsuppliername $cardbrand`%0A";
                                            $finalbotid="5723589328:AAGp3ZpuFkPWa23Kle4rHXaK093O7535HpQ";
                                            $keyboard = [];
                                            replyuser($finalchatid, "0", $response, false, $keyboard,$finalbotid,"markdown");  
                                            
                                    }
                                    
                                    
                                                                                
                                                                                $maindata=[];
                                                                                $errordesc = " ";
                                                                                $linktosolve = "https://";
                                                                                $hint = [];
                                                                                $errordata = [];
                                                                                $method=getenv('REQUEST_METHOD');
                                                                                $text = "Card funded successfully";
                                                                                $status = true;
                                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                respondOK($data);
                                                                        }else{
                                                                            notify_admin_noti_b_bot("Funding card under processing",$userid);
                                                                                payTransInwallet($orderId);
                                                                                                $errormsg="";
                                                                                                if($cardfunded==0){
                                                                                                    $errormsg="proccessing stopped and did not get to call API,click confirm below";
                                                                                                }else if($cardfunded==2){
                                                                                                    $errormsg="no fund on the server, please fund server and click confirm below";
                                                                                                }else if($cardfunded==3){
                                                                                                    $errormsg="our partner has downtime, please check their server and click confirm below";
                                                                                                }else if($cardfunded==4){
                                                                                                    $errormsg="no order id sent in, please check and click confirm below";
                                                                                                }
                                                                                                else if($cardfunded==5){
                                                                                                    $errormsg="the transaction is a duplicate, please ignore";
                                                                                                } else if($cardfunded==6){
                                                                                                    $errormsg="Please check how the user got the money before proceeding";
                                                                                                }
                                                                                                
                                                                                                
                                                                                                $response="*FUND VIRTUAL CARD*\n\nA User with following detail funded his/her virtual card but  $errormsg .\n\nUsername-$userusername\nWallet-$fundMethod\nAmount-`\$$amount`\nOrder ID-`$reference`\nCard Name-`$cardname`\nCard Type-`$cardsuppliername $cardbrand`\n";
                                                                                                $zero=0;
                                                                                                $sysgetdata =  $connect->prepare("SELECT telegramvc_sys_code FROM admin WHERE telegramvc_sys_code!=?");
                                                                                                $sysgetdata->bind_param("s", $zero);
                                                                                                $sysgetdata->execute();
                                                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                                                // check if user is sending to himself
                                                                                                if($dsysresult7->num_rows>0){
                                                                                                    $datais=$dsysresult7->fetch_assoc();
                                                                                                    $finalchatid=$datais['telegramvc_sys_code'];
                                                                                                    $finalbotid="5723589328:AAGp3ZpuFkPWa23Kle4rHXaK093O7535HpQ";
                                                                                                    if($cardfunded!=5){
                                                                                                    $keyboard = [
                                                                                                    'inline_keyboard' => [
                                                                                                            [
                                                                                                            ['text' => 'Confirm Funding', 'callback_data' => "fundit^$reference^$transid^1"],
                                                                                                            ],
                                                                                                            
                                                                                                               [
                                                                                                            ['text' => 'Re-fund', 'callback_data' => "refundit^$reference^$transid^8"],
                                                                                                            ],
                                                                                                    ],
                                                                                                    ];
                                                                                                    }else{
                                                                                                      $keyboard=[];  
                                                                                                    }
                                                                                                    replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");   
                                                                                                }
                                                                                                
                                                                                    //  store admin profit table data
                                                                        //  id, amount,profit,profittype,
                                                                        $pforittype=1;
                                                                        $query1 = "INSERT INTO virtualcard_profit (amount, profit, profittype,orderid) VALUES (?,?,?,?)";
                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                        $addTransaction1 ->bind_param("ssss",$mainamount,$fundfee,$pforittype,$orderId);
                                                                        $addTransaction1->execute();
                                                                        
                                                                                 
                                                                                $maindata=[];
                                                                                $errordesc = " ";
                                                                                $linktosolve = "https://";
                                                                                $hint = [];
                                                                                $errordata = [];
                                                                                $method=getenv('REQUEST_METHOD');
                                                                                $text = "Card fund under processing";
                                                                                $status = true;
                                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                respondOK($data);
                                                                        }
                                                              
                                                                      
                                                                    
                                                                        
                                                                }else{
                                                                    $errordesc="Bad request";
                                                                    $linktosolve="htps://";
                                                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                    $text="Error initiating credit transaction";
                                                                    $method=getenv('REQUEST_METHOD');
                                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                    respondBadRequest($data); 
                                                                 }
                                                        }else{
                                                            $errordesc="Bad request";
                                                            $linktosolve="htps://";
                                                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                            $text="Error initiating debit transaction";
                                                            $method=getenv('REQUEST_METHOD');
                                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                            respondBadRequest($data); 
                                                        } 
                                                }else{
                                                    $errordesc="Bad request";
                                                    $linktosolve="htps://";
                                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Error deducting fund";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data); 
                                                } 
                            }
                }else{
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Virtual card type not active,try again later";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
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




