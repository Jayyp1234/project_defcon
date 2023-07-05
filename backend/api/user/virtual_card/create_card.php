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
      
        
              //collect input and validate it
        // check if the current password field was passed 
        if (!isset($_POST['currencytid']) || !isset($_POST['cardptid'])|| !isset($_POST['pin']) ||!isset($_POST['amount'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
             $currencytid = cleanme($_POST['currencytid']);
             $cardptid = cleanme($_POST['cardptid']);
             $pin = cleanme($_POST['pin']);
             $amount= cleanme($_POST['amount']);
        }
        
        $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel,country,state,postalcode,address1,address2,username FROM users WHERE id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
        $usercountry= $foundUser['country'];
        $userstate= $foundUser['state'];
        $userpostalcode= $foundUser['postalcode'];
        $useraddress1= $foundUser['address1'];
        $mainusernameisis= $foundUser['username'];
        
        $allowedusers=array(816,7,3159);
        
        $errmsg="";
        $alreadycreatedcard_inmonth=false;
        $checkdata =  $connect->prepare("SELECT created_at FROM vc_customer_card WHERE user_id=? AND vc_type_tid=? ORDER BY id DESC LIMIT 1");
        $checkdata->bind_param("ss", $user_id,$cardptid);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        if($dresultUser->num_rows>0){
            $foundUser= $dresultUser->fetch_assoc();
            $cardcreated_at= $foundUser['created_at'];
            $current_date = date('Y-m-d H:i:s');

            // Extract the year and month from the current date
            $current_year = date('Y', strtotime($current_date));
            $current_month = date('m', strtotime($current_date));
            
            // Get the date when the user last created a card
            $last_card_date = $cardcreated_at; // Replace this with the actual date stored in your system
            
            // Extract the year and month from the last card date
            $last_card_year = date('Y', strtotime($last_card_date));
            $last_card_month = date('m', strtotime($last_card_date));
            $last_card_month_text = date('F', strtotime($last_card_date));
            
            // Check if the user has already created a card this month
            if ($current_year == $last_card_year && $current_month == $last_card_month) {
                $alreadycreatedcard_inmonth=true;
                $errmsg="You have created a card in $last_card_month_text. You can only create 1 card in a calendar month. Please try again in " . date('F', strtotime($current_date.' +1 month')) . ".";
            }
        }
        
        if($alreadycreatedcard_inmonth){
            $errordesc=$errmsg;
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text=$errmsg;
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
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
        $unavailable=0;
        $htmlni=0;
        if(isset($_POST['showhtml'])){
             $htmlni=1;
        }
        if($unavailable==1 && !in_array($user_id,$allowedusers)){
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
        
        // check user address and details
        $checkdata =  $connect->prepare("SELECT full_address,stateorigin,country FROM kyc_details WHERE user_id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        if($dresultUser->num_rows>0){
            
            $foundUser= $dresultUser->fetch_assoc();
            // check if city state and country is empty
            $kycfull_address= $foundUser['full_address'];
            $kycstateorigin= $foundUser['stateorigin'];
            $kyccountry= $foundUser['country'];
            // if both bvn detail and profile detail is empty
            if((empty($kycfull_address)||empty($kycstateorigin)||empty($kyccountry)) && (empty($usercountry)||empty($userstate)||empty($userpostalcode)||empty($useraddress1))){
                    $errordesc="Please update your profile detail";
                    $linktosolve="htps://";
                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="1";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
            }
            // if empty bvn details, get data from profile detail
            if(empty($kycfull_address)||empty($kycstateorigin)||empty($kyccountry)) {
                // (city postal code ->state) (country) address
                $postalcode= getPostalCodeFromState($userstate);
                $city= getCityFromState($userstate);
            
                $updatePassQuery = "UPDATE kyc_details SET 	stateorigin= ?,	country=?,full_address=?,postalcode=?,city=? WHERE user_id=?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('ssssss', $userstate,$usercountry,$useraddress1, $postalcode,$city, $user_id);
                $updateStmt->execute();
            }
        }else{
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Please upgrade your level to level 2";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }


  

        //  check if its naira funduning or crypto
        $walletcurrency_tag="NGNT55";
        $itsnairafund=0;
        $coinrate=0;
        $amounttodeduct=0;
        $userwalletbal=0;
          //  check if currency can fund wallet and check if naira fund is active
        $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=? AND wallettrackid=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sss",$user_id, $walletcurrency_tag,$currencytid);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $users = $result->fetch_assoc();
            $itsnairafund=1;
            $userwalletbal=$users['walletbal'];
            // $currency="USD";
            // $fund=1;
            // $coinrate=getNGNtoUSDRate($currency,$fund);
            // $getlivecoinrate= getLiveNGNtoUSDRate($currency,$fund);
            // if($getlivecoinrate>=$coinrate){
            //         // Insert all fields
            //         $errordesc = "An error occured, while funding account, please try again later";
            //         $linktosolve = 'https://';
            //         $hint = "An error occured, while funding account, please try again later";
            //         $errorData = returnError7003($errordesc, $linktosolve, $hint);
            //         $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            //         respondBadRequest($data);
            // }
            //  convert anout from USD to curreny valeu
            // $amounttodeduct= $amount * $coinrate;
        }
          
          
        
        // check user pin
        $verifypass =check_pass($pin,$passpin);
        // check if plan is active
        // check if card plan is active and exist
        $active=1;
        $checkdata =  $connect->prepare("SELECT id,max_vc_generate,min_fund,creation_fee,currency,maintanace_fee,funding_percent,second_fund_fee,cardbrand,supplier,level_needed,naira_fund_exhange_rate,name,need_activation,provider_fund_percent FROM  vc_type WHERE trackid=? AND status=?");
        $checkdata->bind_param("si",$cardptid,$active);
        $checkdata->execute();
        $dresult2 = $checkdata->get_result();
       
        // check if user subwallet is active and exist
        $checkdata =  $connect->prepare("SELECT id,coinsystemtag,walletbal,currencytag FROM  usersubwallet WHERE trackid=?");
        $checkdata->bind_param("s",$currencytid);
        $checkdata->execute();
        $dresult3 = $checkdata->get_result();
        
        if (empty($currencytid)||empty( $cardptid)||empty($pin)||empty($amount)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Fill all fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if($amount<0 || !is_numeric($amount)){
               $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Invalid amount";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if(floor( $amount ) != $amount){
               $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Only whole number is allowed in amount";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else  if($dresult2->num_rows==0){
            $errordesc="Card type is not active.";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Card type is not active.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if($dresult3->num_rows==0 &&  $itsnairafund==0){
            $errordesc="Sub wallet does not exist.";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Sub wallet does not exist.";
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
        } else if($userlevel<2){
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please upgrade your level to level 2";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }else{
             $coinprodtrackidis=$cointype="";
            // card type details
            $cardTypeData= $dresult2->fetch_assoc();
            $cardname= $cardTypeData['name'];
            $cardmax_vc_generate = $cardTypeData['max_vc_generate'];
            $cardfunding_percent= $cardTypeData['funding_percent'];
            $need_activation= $cardTypeData['need_activation'];
            $cardsecond_fund_fee= $cardTypeData['second_fund_fee'];
            $cardmaintanace_fee= $cardTypeData['maintanace_fee'];
            $cardfunding_percent_profit= $cardTypeData['provider_fund_percent'];
            $cardCreationFee= $cardTypeData['creation_fee'];
            $cardCurrency=$cardTypeData['currency'];
            $cardmin_fund=$cardTypeData['min_fund'];
            $cardbrand= $cardTypeData['cardbrand'];
            $cardsupplier= $cardTypeData['supplier'];
            $cardlevel_needed= $cardTypeData['level_needed'];
             $naira_fund_exhange_rate=$cardTypeData['naira_fund_exhange_rate'];
             
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
            
            
            if($getlivecoinrate>=$coinrate){
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
                                        $response="*RATE IS ABOVE WHAT YOU SET* $mainusernameisis Rate set is $coinrate NGN and live rate is $getlivecoinrate NGN";
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
            // else
            if($userlevel>=$cardlevel_needed){
                    if($amount>=$cardmin_fund){
                            $profitfundfee=$cardsecond_fund_fee+((($cardfunding_percent-$cardfunding_percent_profit)/100) * $amount);
                            $fundfee=$cardsecond_fund_fee+(($cardfunding_percent/100) * $amount);
                            $totalfeetodeduct=$cardCreationFee+$cardmaintanace_fee+$amount+$fundfee;
                            $amountofundganagn=$amount+$fundfee+$cardmaintanace_fee;
                            $adminProfit=$cardCreationFee;
                            $feetodeductincoinvalue=0;
                            $feedeductedamountincoin=0;
                            // subwallet details
                            if($dresult3->num_rows>0){
                                $foundUserWallet= $dresult3->fetch_assoc();
                                $coinsystemtag = $foundUserWallet['coinsystemtag'];
                                $userwalletbal = $foundUserWallet['walletbal'];
                                $walletcurrency_tag=$foundUserWallet['currencytag'];
                                
                                // check if wallet is allowed to fund Virtual card
                                $canfund=1;
                                $sql = "SELECT id,producttrackid,cointype,name,crypto_covinence_fee FROM coinproducts WHERE subwallettag=? AND can_fund_vc=? AND status=?";
                                $getCoinProduct = $connect->prepare($sql);
                                $getCoinProduct->bind_param('ssi',$coinsystemtag,$canfund,$canfund);
                                $getCoinProduct->execute();
                                $Coinpresult = $getCoinProduct->get_result();
                                if($Coinpresult->num_rows>0){
                                        // coin details
                                        $coinPData= $Coinpresult->fetch_assoc();
                                        $coinprodtrackidis =  $coinPData['producttrackid'];
                                        $cointype=$coinPData['cointype'];
                                        $fundMethod=$coinPData['name'];
                                        $cryptoFeePercent=$coinPData['crypto_covinence_fee'];
                                         $cryptofeetotake=($cryptoFeePercent/100) * $amount;
                                         $fundfee =$fundfee+$cryptofeetotake;
                                         $totalfeetodeduct=$totalfeetodeduct+$cryptofeetotake;
                                          // check if user hv  the fund
                                         $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis); 
                                         if($getlivevalu>0){
                                             // get total price to pay from card
                                             //  get user balance
                                                            //  get live rate and use to divide above to get coin value to pay, then check user balnce for d pay
                                                 $feetodeductincoinvalue=round($totalfeetodeduct/$getlivevalu,2);
                                                 $feedeductedamountincoin=round($amountofundganagn/$getlivevalu,2);
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
                            }
                            else if($itsnairafund==1){
                                $fundMethod="Naira Wallet";
                                $feetodeductincoinvalue= $totalfeetodeduct * $coinrate;
                                $feedeductedamountincoin=$amountofundganagn* $coinrate;
                            }
                            
                            // count how many VC user have already generated for that plan
                            $zero=0;
                            $checkVcCount =  $connect->prepare("SELECT id FROM  vc_customer_card WHERE user_id=? AND vc_type_tid=? AND disabled=?");
                            $checkVcCount->bind_param("ssi",$user_id,$cardptid, $zero);
                            $checkVcCount->execute();
                            $vc_count_result = $checkVcCount->get_result();
                            $total_generated_vc=$vc_count_result->num_rows;
                            
                          
                            
                            // check if user hv reach max virtual card generate
                            if($total_generated_vc>=$cardmax_vc_generate){
                                    $errordesc="Bad request";
                                    $linktosolve="htps://";
                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    $text="Opps, Sorry you have gotten to the maximum number of cards you can generate for this card type";
                                    $method=getenv('REQUEST_METHOD');
                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    respondBadRequest($data);
                            // check if wallet is allowed to fund Virtual card
                            } else if($userwalletbal>=$feetodeductincoinvalue){
                                        $customerid="";
                                        
                                        $check_vc_customers =  $connect->prepare("SELECT customer_id FROM vc_customers WHERE user_id=? AND supplier=?");
                                        $check_vc_customers->bind_param("si",$user_id,$cardsupplier);
                                        $check_vc_customers->execute();
                                        $vc_customer_result = $check_vc_customers->get_result();
                                        $total_customervc=$vc_customer_result->num_rows;
                                        // check if user have customer account
                                        if($total_customervc>0){
                                             // if yes get customer id
                                            $vc_customer_data= $vc_customer_result->fetch_assoc();
                                            $customerid = $vc_customer_data['customer_id'];
                                        }else{
                                                $customerCreated=false;
                                                if($cardsupplier==1){
                                                     $customerCreated= createVC_customer($user_id,$cardCurrency);  
                                                }else if($cardsupplier==2){
                                                    // BC card supplier
                                                    //  customer data would have been created from level 3 form user should not get here
                                                    $customerCreated= createBCVC_customer($user_id,$cardCurrency);  
                                                }
                                               // if no call create customer   
                                               if($customerCreated){
                                                    $check_vc_customers =  $connect->prepare("SELECT customer_id FROM vc_customers WHERE user_id=? AND supplier=? ");
                                                    $check_vc_customers->bind_param("si",$user_id,$cardsupplier);
                                                    $check_vc_customers->execute();
                                                    $vc_customer_result = $check_vc_customers->get_result();
                                                    $total_customervc=$vc_customer_result->num_rows;
                                                    $vc_customer_data= $vc_customer_result->fetch_assoc();
                                                    $customerid = $vc_customer_data['customer_id'];
                                               } else{
                                                   
                                                   
                                                        $zero=0;
                                $sysgetdata =  $connect->prepare("SELECT telegramvc_sys_code FROM admin WHERE telegramvc_sys_code!=?");
                                $sysgetdata->bind_param("s", $zero);
                                $sysgetdata->execute();
                                $dsysresult7 = $sysgetdata->get_result();
                                // check if user is sending to himself
                                if($dsysresult7->num_rows>0){
                                        $datais=$dsysresult7->fetch_assoc();
                                        $finalchatid=$datais['telegramvc_sys_code'];
                                        $response="*CUSTOMER CARD DATA FAIL*%0A%0A@habnarm1 \n User id:$user_id Username:$mainusernameisis ";
                                        $finalbotid=$mainCardify_CARD_noti_bot;
                                        $keyboard = [];
                                        replyuser($finalchatid, "0", $response, false, $keyboard,$finalbotid,"markdown"); 
                                }
                                
                                                    $errordesc="Bad request";
                                                    $linktosolve="htps://";
                                                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Error creating Customer Virtual card data";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);   
                                               }
                                        }
                                        
                                         if(!empty($customerid)&&strlen($customerid)>3){
                                            //  activate below if one user should have just 1 wallet
                                                    // $walletid="";
                                                    //     exit;
                                                    // $check_vc_customers =  $connect->prepare("SELECT wallet_id FROM vc_customer_wallets WHERE user_id=? AND customer_id=?");
                                                    // $check_vc_customers->bind_param("ss",$user_id,$customerid);
                                                    // $check_vc_customers->execute();
                                                    // $vc_customer_result = $check_vc_customers->get_result();
                                                    // $total_customervc=$vc_customer_result->num_rows;
                                                    // // check if user have wallet account
                                                    // if($total_customervc>0){
                                                    //   // if yes get wallet id
                                                    //     $vc_customer_data= $vc_customer_result->fetch_assoc();
                                                    //     $walletid = $vc_customer_data['wallet_id'];
                                                    // }else{
                                                    //         // if no call create wallet
                                                    //       $walletype=2;
                                                    //       if(generateVC_MainAndSubWallet($walletype,$customerid,$cardCurrency,$user_id)){
                                                    //             $check_vc_customers =  $connect->prepare("SELECT wallet_id FROM vc_customer_wallets WHERE user_id=? AND customer_id=?");
                                                    //             $check_vc_customers->bind_param("ss",$user_id,$customerid);
                                                    //             $check_vc_customers->execute();
                                                    //             $vc_customer_result = $check_vc_customers->get_result();
                                                    //             $total_customervc=$vc_customer_result->num_rows;
                                                    //             $vc_customer_data= $vc_customer_result->fetch_assoc();
                                                    //             $customerid = $vc_customer_data['wallet_id'];
                                                    //       }else{
                                                    //             $errordesc="Bad request";
                                                    //             $linktosolve="htps://";
                                                    //             $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                    //             $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    //             $text="Error creating Customer Wallet data";
                                                    //             $method=getenv('REQUEST_METHOD');
                                                    //             $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    //             respondBadRequest($data);   
                                                    //       }
                                                    // }
                                        
                                                //   if(!empty($walletid)&&strlen($walletid)>3){
                                               
                                                      $funddeducted=false;
                                                       $iscryptotrans=0;
                                                      if($itsnairafund==1){
                                                          $funddeducted=payDeductUserBalance($user_id,$feetodeductincoinvalue,$walletcurrency_tag,$currencytid);
                                                               $iscryptotrans=0;
                                                      }else if($itsnairafund==0){
                                                          $funddeducted=payRemoveUserSubBalance($user_id,$feetodeductincoinvalue,$walletcurrency_tag,$currencytid);
                                                           $iscryptotrans=1;
                                                      }
                                                             
                                                    //   decuct user fund
                                                      if($funddeducted){
                                                            // $cardtid="USD3HMSR";
                                                            if($cardsupplier==1){
                                                                $cardtid=generate_User_VC($user_id,$cardCurrency,$cardptid,$customerid,$amount);
                                                            }else if($cardsupplier==2){
                                                                // bc card generator
                                                                $cardtid=generate_User_BcVC($user_id,$cardCurrency,$cardptid,$customerid,$amount);
                                                            }
                                                            //   call create virtual card
                                                            if($cardtid){
                                                           
                                        
                                                                $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
                                                                $transhash = '';
                                                                // generating  order ref
                                                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","VC",true,true,true);
                                                                $reference=$orderId = createTransUniqueToken("CVC", $user_id);
                                                                $ordertime = date("h:ia, d M");
                                                                $confirmtime = '';
                                                                $status = 1; 
                                                                $username="";
                                                                $accountsentto="";
                                                                $amttopay = $feetodeductincoinvalue;
                                                                $addresssentto = '';
                                                                $manualstatus = 0;
                                                                $currencytag = $walletcurrency_tag;
                                                                $approvaltype = 1;
                                                                $message1 = "Created Virtual Card for $feetodeductincoinvalue";
                                                                // insert the values to the transation for recieve
                                                                $transtype1 = 1;
                                                                $yes=0;
                                                                $systemsendwith=6;
                                                                $empty=" ";
                                                                $virtual_card_trans=0;
                                                                $prfiteis=0;
                                                                $query1 = "INSERT INTO userwallettrans (bill_profit_loose,vc_creationfee,vc_maintainfee,fund_fee,virtual_card_trans,ourrrate,payapiresponse,swapto,cointrackid,livecointype,systemsendwith,iscrypto,theusdval,btcvalue,bankaccsentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                $addTransaction1 = $connect->prepare($query1);
                                                                $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssss",$prfiteis,$cardCreationFee,$cardmaintanace_fee,$fundfee,$virtual_card_trans,$coinrate,$empty,$empty,$coinprodtrackidis,$cointype,$systemsendwith,$iscryptotrans,$amount,$feetodeductincoinvalue,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$currencytid,$username);
                                                                $addTransaction1->execute();
                                                                //   START HERE
                                                                //store swap history
                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","VC",true,true,true);
                                                                $reference=$orderId = createTransUniqueToken("CVC", $user_id);
                                                                $transtype1 = 2;
                                                                $currencytid=$cardtid;
                                                                 $currencytag="USD256";
                                                                $virtual_card_trans=1;
                                                                // $amttopay = $feedeductedamountincoin;
                                                                $message1 = "Fund Virtual Card with $feedeductedamountincoin";
                                                                //  below is total fee deducted for funding
                                                                // $feedeductedamountincoin
                                                                
                                                                // $iscryptotrans
                                                                $prfiteis=$cardCreationFee+$profitfundfee; 
                                                                $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssss",$prfiteis,$cardCreationFee,$cardmaintanace_fee,$fundfee,$virtual_card_trans,$coinrate,$empty,$empty,$coinprodtrackidis,$cointype,$systemsendwith,$iscryptotrans,$amount,$feetodeductincoinvalue,$accountsentto,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$currencytid,$username);
                                                                $addTransaction1->execute();
                                                                        
                                                                
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
                                                                            
                                                                                $subject = virtualCardCreatedSubject($user_id,$cardtid); 
                                                                                $to =$ussernamesenttomail;
                                                                                $messageText = virtualCardCreatedText($user_id,$cardtid);
                                                                                $messageHTML = virtualCardCreatedHTML($user_id,$cardtid);
                                                                                sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                sendUserSMS($usersenttophone,$messageText);
                                                                                // $userid,$message,$type,$ref,$status
                                                                                create_vc_user_noti($user_id,$cardtid);
                                                                                
                                                                                $userid=$user_id;
                                                                                $subject = fundVirtualCardSuccessSubject($userid,$reference); 
                                                                                $to = $ussernamesenttomail;
                                                                                $messageText =fundVirtualCardSuccessText($userid, $reference);
                                                                                $messageHTML = fundVirtualCardSuccessHTML($userid, $reference);
                                                                                sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                sendUserSMS($usersenttophone,$messageText);
                                                                                // $userid,$message,$type,$ref,$status
                                                                                fund_vc_user_noti($userid,$reference);
                                                                                notify_admin_noti_b_bot($messageText,$userid);
                                                                        
                                                                              //  store admin profit table data
                                                                                    //  id, amount,profit,profittype,
                                                                                    $pforittype=2;
                                                                                    $orderId=" ";
                                                                                    $query1 = "INSERT INTO virtualcard_profit (amount, profit, profittype,orderid) VALUES (?,?,?,?)";
                                                                                    $addTransaction1 = $connect->prepare($query1);
                                                                                    $addTransaction1 ->bind_param("ssss",$totalfeetodeduct,$adminProfit,$pforittype,$orderId);
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
                                                                                    $response="*CREATED CARD*%0A%0AA User with following detail created a virtual card.%0A%0AUsername-$userusername%0AWallet-$fundMethod%0ACreation-`\$$cardCreationFee`%0AFund-`\$$amount`%0ACard Name-`$cardname`%0ACard Type-`$cardsuppliername $cardbrand`%0AOrder ID-`$reference`%0A";
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
                                                                                $text = "Card creation under processing";
                                                                                $status = true;
                                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                respondOK($data);
                                                                                        
                                                                      
                                                                        
                                                            }else{
                                                                if($itsnairafund==1){
                                                                    payAddUserBalance($user_id,$feetodeductincoinvalue,$walletcurrency_tag,$currencytid);
                                                                }else if($itsnairafund==0){
                                                                      payAddUserSubBalance($user_id,$feetodeductincoinvalue,$walletcurrency_tag,$currencytid);
                                                                }
                                                              
                                                                
                                                                $errordesc="Our banking partners for virtual cards are currently experiencing a temporary interruption. Please note that such instances are typically short-lived. We kindly request you to try again shortly.";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Our banking partners for virtual cards are currently experiencing a temporary interruption. Please note that such instances are typically short-lived. We kindly request you to try again shortly.";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data); 
                                                            }
                                                            
                                                      }else{
                                                              $errordesc="Bad request";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Error deducting fund, try again later";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data); 
                                                      }
                                                            
                                                            
                                                //   }else{
                                                //         $errordesc="Bad request";
                                                //         $linktosolve="htps://";
                                                //         $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                //         $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                //         $text="Customer Wallet data not found";
                                                //         $method=getenv('REQUEST_METHOD');
                                                //         $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                //         respondBadRequest($data);  
                                                //   }
                
                                         }else{
                                            $errordesc="Bad request";
                                            $linktosolve="htps://";
                                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Customer Virtual card data not found";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data); 
                                         }
                                    
                            }else{
                                $errordesc="Bad request";
                                $linktosolve="htps://";
                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Insufficient balance";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data); 
                            }  
                    }else{
                                $errordesc="Bad request";
                                $linktosolve="htps://";
                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Minimum you can fund is $ $cardmin_fund";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data); 
                    } 
            }else{
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="You need to be in level $cardlevel_needed before you can create this card type";
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




