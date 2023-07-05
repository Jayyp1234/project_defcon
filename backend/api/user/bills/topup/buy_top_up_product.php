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


include "../../../../config/utilities.php";

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

        $paidwithcrypto=0;
        $ordertagis="NGN";
    
        //collect input and validate it 
        // check if the current password field was passed 
        // type 1 data 2-airtime
        $itsforrepeate=0;
        $billswap_tid_use=" ";
        if(isset($_POST['repeate_transid'])&&($_POST['repeate_transid']!='')&&!empty(trim($_POST['repeate_transid']))){
            $itsforrepeate=1;
            $on=1;
            $thetransidis=cleanme($_POST['repeate_transid']);
            $checkdata =  $connect->prepare("SELECT bill_data_prodtid,billtypeis,amttopay,bill_main_prodtid,bill_product_no FROM userwallettrans WHERE id=? AND bills_trans=? AND userid=?");
            $checkdata->bind_param("sii", $thetransidis,$on,$user_id);
            $checkdata->execute();
            $dresultUser = $checkdata->get_result();
            if($dresultUser->num_rows>0){
                $foundTrans= $dresultUser->fetch_assoc();
                $phoneno =$foundTrans['bill_product_no'] ;
                $productrackid =$foundTrans['bill_main_prodtid'] ;
                $amount = $foundTrans['amttopay'];// amount in usd
                $pin = "NULL";
                $type = $foundTrans['billtypeis'];
                $datatidis=$foundTrans['bill_data_prodtid'];
            }else{
                $errordesc="Transaction not found";
                $linktosolve="htps://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Transaction not found";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            if (!isset($_POST['pin'])) {
                $errordesc="All fields must be passed";
                $linktosolve="htps://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Kindly  fill all data";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else{
                 $pin = cleanme($_POST['pin']);
            }
        }else{
            if (!isset($_POST['phoneno']) || !isset($_POST['amount'])|| !isset($_POST['productrackid'])|| !isset($_POST['pin'])|| !isset($_POST['type'])|| !isset($_POST['paytype'])) {
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
            }else if ($_POST['type']==1 && !isset($_POST['data_tid'])){
                // Insert all fields
                $errordesc = "Invalid Data plan";
                $linktosolve = 'https://';
                $hint = "Invalid Data plan";
                $errorData = returnError7003($errordesc, $linktosolve, $hint);
                $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                respondBadRequest($data);
            } else{
                 $phoneno = cleanme($_POST['phoneno']);
                 $productrackid = cleanme($_POST['productrackid']);
                 $amount = cleanme($_POST['amount']);// amount in usd
                 $pin = cleanme($_POST['pin']);
                 $type = cleanme($_POST['type']); //1 data 2 airtime
                 $datatidis="";
                 $paytype= cleanme($_POST['paytype']);//1 is naira 2 is crypto
                 if($paytype==2){
                     $paidwithcrypto=1;
                 }
                  if (isset($_POST['data_tid'])){
                      $datatidis=cleanme($_POST['data_tid']);
                  }
            }
        }
        //PAYMENT WITH CRYPTO
        $cryptoprofit=0;
        $wallettype="NGN";
        if($paidwithcrypto==1){
            // swap it here
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
                $billswap_tid_use=$towallettrackid= cleanme($_POST['towallettrackid']);
            }
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
            
            // get the aamount to swap then 
            $amounttouse=0;
            if($type==2){
                $amounttouse=$amount;
            }else{
                    // get data amount from db
                    $active=1;
                    $checkdata =  $connect->prepare("SELECT type,product_trackid,shortname,name FROM  bill_top_up_main_products WHERE 	product_trackid=? AND status=? AND type=?");
                    $checkdata->bind_param("sii",$productrackid,$active,$type);
                    $checkdata->execute();
                    $dresult2 = $checkdata->get_result();
                    if($dresult2->num_rows==0){
                        $errordesc="Product is not active.";
                        $linktosolve="htps://";
                        $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Product is not active.";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }else{ 
                        $active=1;
                        $checkdata =  $connect->prepare("SELECT price FROM bill_data_provider WHERE bill_main_prod_tid=? AND status=? AND provider_tid=?");
                        $checkdata->bind_param("sis",$productrackid,$active,$datatidis);
                        $checkdata->execute();
                        $dresult4 = $checkdata->get_result();
                        if($dresult4->num_rows>0){
                            $vc_typedata= $dresult4->fetch_assoc();
                            $amounttouse= $vc_typedata['price'];
                        
                        }else{
                            $errordesc="Product is not active.";
                            $linktosolve="htps://";
                            $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Product is not active.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }
                    }
                
            }
            // do mathematics 5% and the convertion from naira
            // if($amounttouse<500){
            //         $errordesc="The minimum bill product you can use crypto to buy is 500 naira";
            //         $linktosolve="htps://";
            //         $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            //         $errordata=returnError7003($errordesc,$linktosolve,$hint);
            //         $text="The minimum bill product you can use crypto to buy is 500 naira";
            //         $method=getenv('REQUEST_METHOD');
            //         $data=returnErrorArray($text,$method,$endpoint,$errordata);
            //         respondBadRequest($data);
            // }
            
            $zeroni=0;
            $amon=1;
            
            $sysgetdata =  $connect->prepare("SELECT conversion_rate,bill_crypto_fee,coin_from_name FROM swap_system_settings WHERE trackid=? AND status=? AND to_is_crypto=?");
            $sysgetdata->bind_param("sii", $towallettrackid,$amon,$zeroni);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $num_foundis = $dsysresult7->num_rows;
            // check if user is sending to himself
            if($num_foundis>0){
                    $datais=$dsysresult7->fetch_assoc();
                    $conversion_rate=$datais['conversion_rate'];
                    $bill_crypto_fee=$datais['bill_crypto_fee'];
                    $ordertagis=$datais['coin_from_name'];
                    $wallettype=$ordertagis;
                    $finalamttoconvert=0;
                    $cryptoprofit=($amounttouse*(floatval($bill_crypto_fee)/100));
                    $finalamttoconvert =$amounttouse+$cryptoprofit;
                    $currencybasevalue=$finalamttoconvert/$conversion_rate;
                    // get crypto value,
                    // convert for airtime the naira value without 5%
                    // 
                    
                    
                    
                    
                    //  call swap API
                    $headerName = 'Authorization';
                    $headers = getallheaders();
                    $signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
                    if($signraturHeader==null){
                    $signraturHeader= isset($_SERVER['Authorization'])?$_SERVER['Authorization']:"";
                    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                    $signraturHeader = trim($_SERVER["HTTP_AUTHORIZATION"]);
                    }
                    $postdata = array('amttopay' => $currencybasevalue,'productamt'=>$amounttouse, 'currency' => $currency,"swapcheckmin"=>"0","towallettrackid"=>$towallettrackid,"wallettrackid"=>$wallettrackid);
                    $url="https://app.cardify.co/api/user/swap/swap_user_coin.php";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded',"Authorization: $signraturHeader"));
                    $result = curl_exec($ch);
                    $info = json_decode($result);
                    $responsetext=$info ->text;
                    $responsestatu=$info->status;
                    if($responsestatu==false||!$responsestatu){
                            $errordesc=$responsetext;
                            $linktosolve="htps://";
                            $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text=$responsetext;
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                    }
                    //  if success proceed
                  
            }else{
                $errordesc="Swap system not found";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Swap system not found";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
  
        }
        
        
   
        
 

        //  check if its naira funduning or crypto
        $currencytagis="NGNT55";
        $userwalletbal=0;
        $currencytid="";
        //  check if currency can fund wallet and check if naira fund is active
        $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("ss",$user_id, $currencytagis);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $users = $result->fetch_assoc();
            $itsnairafund=1;
            $userwalletbal=$users['walletbal']; 
            $currencytid=$users['wallettrackid'];
        }
        $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel FROM users WHERE id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
        
        // check amount here for limit if user has reach limit
        $maxperday=0;
        $checkdata =  $connect->prepare("SELECT usage_per_day FROM bill_limit_system WHERE level=? ");
        $checkdata->bind_param("s",$userlevel);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        if($dresultUser->num_rows>0){
            $foundUser= $dresultUser->fetch_assoc();
            $maxperday = $foundUser['usage_per_day'];
        }
        
               // count sum user sent a day
        $selectedmonth= date('n');//12
        $selectedyear= date('Y');//2022
        $todaydayis=date('d');
        $sendtypeis=1;
        $donesuccess=1;
        $totalcoinsent=0;
        $totaldone_adayis=0;
        // SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=2 AND MONTH(created_at) = 1 AND YEAR(created_at)=2023 AND DAY(created_at)=19 AND status=1 AND userid=7 GROUP BY userid
        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE bills_trans=?  AND  MONTH(created_at) = ? AND YEAR(created_at)=? AND DAY(created_at)=? AND status=? AND userid=?  GROUP BY userid");
        $getexactdata->bind_param("ssssss", $sendtypeis,$selectedmonth,$selectedyear,$todaydayis,$donesuccess,$user_id);
        $getexactdata->execute();
        $rresult2 = $getexactdata->get_result();
        $totaldone_adayis = $rresult2->num_rows;
        if ($totaldone_adayis>0) {
            $ddatasent=$rresult2->fetch_assoc();
            $totalcoinsent=$ddatasent['totalsent'];
        }
        
        if($totalcoinsent>=$maxperday){
            $messageis="";
            if($userlevel==1){
                $messageis="You have reached your daily bills limit of $maxperday NGN, kindly upgrade your account level.";
            }else if($userlevel==2){
                $messageis="You have reached your daily bills limit of $maxperday NGN, kindly upgrade your account level.";
            }else if($userlevel==3){
                $messageis="You have reached your daily bills limit of $maxperday NGN.";
            }
            $errordesc="$messageis";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="$messageis";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        
        
        //  verify pin
            $verifypass =check_pass($pin,$passpin);
        // validate product selected is active
        $active=1;
        $checkdata =  $connect->prepare("SELECT type,product_trackid,shortname,name FROM  bill_top_up_main_products WHERE 	product_trackid=? AND status=? AND type=?");
        $checkdata->bind_param("sii",$productrackid,$active,$type);
        $checkdata->execute();
        $dresult2 = $checkdata->get_result();
        

        if (empty($phoneno)||empty($productrackid)||empty($amount)||empty($pin)||empty($type)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Fill all data needed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else if ($type==1 && empty(trim($datatidis))){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Fill all data needed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }  else  if (!$verifypass) {
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Invalid pin.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else  if($dresult2->num_rows==0){
            $errordesc="Product is not active.";
            $linktosolve="htps://";
            $hint=["Fill all data needed","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Product is not active.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if($amount <=0 || !is_numeric($amount)){ 
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid amount to deduct";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        
        }else  if($type==2 && ($amount <100||$amount >10000)){ 
                $errordesc="Minimum you can buy is 100 NGN and maximum is 10,000NGN";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Minimum you can buy is 100 NGN and maximum is 10,000NGN";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }else if($userlevel<1){
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please upgrade your level to level 1";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }else{
                    


        // product details
        $cardTypeData= $dresult2->fetch_assoc();
        $maintype = $cardTypeData['type'];
        $product_trackid= $cardTypeData['product_trackid'];
        $shortname= $cardTypeData['shortname'];
        $name= $cardTypeData['name'];
        // use type to proceed 
        if($maintype==2){//airtime sell
            $active=1;
            $checkdata =  $connect->prepare("SELECT cashback,sale_percent,theenoapp,connec_lub,sh_network,provider_tid,crypto_cashback,pro_price FROM bill_airtime_provider WHERE bill_main_prod_tid=? AND status=?");
            $checkdata->bind_param("si",$product_trackid,$active);
            $checkdata->execute();
            $dresult4 = $checkdata->get_result();
            if($dresult4->num_rows>0){
                        $vc_typedata= $dresult4->fetch_assoc();
                        $prod_naira_cashback =$vc_typedata['cashback'];
                        $prod_crypto_cashback=$vc_typedata['crypto_cashback'];
                        $prod_sale_percent= $vc_typedata['sale_percent'];
                        $prod_theenoapp= $vc_typedata['theenoapp'];
                        $prod_connec_lub= $vc_typedata['connec_lub'];
                        $prod_sh_network= $vc_typedata['sh_network'];
                        $prod_provider_tid= $vc_typedata['provider_tid'];
                        $pro_price_percent=$vc_typedata['pro_price'];
                        
                        if($paidwithcrypto==1){
                            $prod_cashback =$prod_crypto_cashback;
                        }else{
                            $prod_cashback =$prod_naira_cashback;
                        }
                        
                        $networkid=0;
                        $query = 'SELECT activebillsystem FROM systemsettings';
                        $stmt = $connect->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row =  mysqli_fetch_assoc($result);
                        $activebillsystem = $row['activebillsystem'];
                        // 1 1app, 2 club , 3 SH 4 VToass
                        if($activebillsystem==1){
                            $networkid=$prod_theenoapp;
                        }else  if($activebillsystem==2){
                            $networkid=$prod_connec_lub;
                        }else  if($activebillsystem==3){
                            $networkid=$prod_sh_network;
                        }
                        
                        $amounttogveuser=($prod_sale_percent/100)*$amount;
                        $amounttodeduct = $amount-$amounttogveuser;
                        
                        $amountprovidergiveus=($pro_price_percent/100)*$amount;
                        $amountdeductedbyuser=$amount-$amountprovidergiveus;
                        
                        
                        
                        $cashbackis=round(($prod_cashback/100)*$amount,2);
                        if($cashbackis>100){
                            $cashbackis=100;
                        }
                        $profitemadeis=($amounttodeduct+$cryptoprofit)-$amountdeductedbyuser-$cashbackis;
                    //   check if user has balance
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
                                            // debit user
                                            $funddeducted=payDeductUserBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                
                                            if($funddeducted){
                                                        $reference=$orderId = createTransUniqueToken("BA$ordertagis$shortname", $user_id);
                                                        
                                                       
                                                            //   start here
                                                             $ordertime = date("h:ia, d M");
                                                              $confirmtime = date("h:ia, d M");
                                                              $status=1;
                                                              $auto=1;
                                                              $transtype=1;//1 spend 2 recive
                                                              $paymentstatus=1;
                                                              $systemsendwith=8;
                                                              $yes=1;
                                                               $billtype=1;//1 is top up, 2 is voucher, 3 is tickets
                                                            $message1 = "Bought $amount NGN airtime  on $phoneno for $amounttodeduct NGN";
                                                            $query1 = "INSERT INTO userwallettrans (bill_trans_type,bill_cashback,userid,message,orderid,ordertime,confirmtime,status,approvaltype,amttopay,amountsentin,currencytag,transtype,paymentstatus,systemsendwith,wallettrackid,bills_trans,bill_main_prodtid,bill_product_no,billtypeis,	bill_swap_tid_used,bill_profit_loose) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                            $addTransaction1 = $connect->prepare($query1);
                                                            $addTransaction1 ->bind_param("ssssssssssssssssssssss",$billtype,$cashbackis,$user_id,$message1,$orderId,$ordertime,$confirmtime,$status,$auto,$amount,$amounttodeduct,$currencytagis,$transtype,$paymentstatus,$systemsendwith,$currencytid,$yes,$product_trackid,$phoneno,$maintype,$billswap_tid_use,$profitemadeis);
                                                       
                                                            if ($addTransaction1->execute()){
                                                                
                                                                 $userproductgiven=false;
                                                        // vend frm api
                                                        $userproductgiven=buyUserAirtime($amount, $networkid,$reference,$phoneno);
                                                       if($userproductgiven){
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
                                                                                    $subject = billTopUpTransactionSubject($userid,$reference); 
                                                                                    $to = $ussernamesenttomail;
                                                                                    $messageText =billTopUpTransactionText($userid, $reference);
                                                                                    $messageHTML = billTopTransactionHTML($userid, $reference);
                                                                                    sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                    sendUserSMS($usersenttophone,$messageText);
                                                                                    // $userid,$message,$type,$ref,$status
                                                                                    bill_topup_success_user_noti($userid,$reference);
                                                                                    notify_admin_noti_b_bot($message1,$user_id);
                                                                                      notify_admin_bills_b_bot($message1,$user_id,$wallettype,"Top Up");
                                                                                    // CASHBACK
                                                                                    //  give user cashback
                                                                                    if (payAddUserCashbackBalance($user_id,$cashbackis)){
                                                                                        // store cashback history
                                                                                        $cashtranstype=2;
                                                                                        $cashbpaid=1;
                                                                                        $referaluserid=" ";
                                                                                        $track_id=createOrderidWIthTransData("CB-BILLS-",$user_id,"cashback_history","userid","cashbackorderid");//createUniqueToken(4,"cashback_history","cashbackorderid","CB-BILLS-",true,false,false);
                                                                                        $cashborderId = $track_id;//createTransUniqueToken("DATA_CB", $user_id);
                                                                                        $query1 = "INSERT INTO cashback_history (`userid`, `amount`, `trans_type`, `referaluserid`, `transorderid`,  `status`, `cashbackorderid`) VALUES (?,?,?,?,?,?,?)";
                                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                                        // echo $connect->error;
                                                                                        $addTransaction1 ->bind_param("sssssss",$user_id,$cashbackis,$cashtranstype,$referaluserid,$orderId, $cashbpaid,$cashborderId);
                                                                                        $addTransaction1->execute();
                                                                                        // echo $addTransaction1->error;
                                                                                    }
                                                                                     
                                                                              
                                                                                    
                                                                                    
                                                                                    $maindata['cashback']=$cashbackis;
                                                                                    $errordesc = " ";
                                                                                    $linktosolve = "https://";
                                                                                    $hint = [];
                                                                                    $errordata = [];
                                                                                    $method=getenv('REQUEST_METHOD');
                                                                                    $text = "Airtime purchased successfully";
                                                                                    $status = true;
                                                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                    respondOK($data);      
                                                                           
                                                      }else{
                                                          // return user fund
                                                          payTransCancled($reference);
                                                          payAddUserBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                                                $errordesc="Server error try again later";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Server error try again later";
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
                $errordesc="Product not active,try again later";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Product not active,try again later";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
        }
        else if($maintype==1){// data sale
        
            $datacodetid=$datatidis;
            // 
              $active=1;
            $checkdata =  $connect->prepare("SELECT name,theenoapp,connec_lub,sh_network,cashback,price,theenoapp_netid,connec_lub_netid,sh_network,provider_tid,sh_network_netid,crypto_cashback,pro_price FROM bill_data_provider WHERE bill_main_prod_tid=? AND status=? AND provider_tid=?");
            $checkdata->bind_param("sis",$product_trackid,$active,$datacodetid);
            $checkdata->execute();
            $dresult4 = $checkdata->get_result();
            if($dresult4->num_rows>0){
                        $vc_typedata= $dresult4->fetch_assoc();
                        $prod_nameis=$vc_typedata['name'];
                        $prod_naira_cashback =$vc_typedata['cashback'];
                        $prod_crypto_cashback=$vc_typedata['crypto_cashback'];
                        $prod_sale_percent= $vc_typedata['price'];
                        $prod_theenoapp= $vc_typedata['theenoapp_netid'];
                        $prod_connec_lub= $vc_typedata['connec_lub_netid'];
                        $prod_sh_network= $vc_typedata['sh_network_netid'];
                        $prod_provider_tid= $vc_typedata['provider_tid'];
                        $prod_datacode_theenoapp= $vc_typedata['theenoapp'];
                        $prod_datacode_connec_lub= $vc_typedata['connec_lub'];
                        $prod_datacode_sh_network= $vc_typedata['sh_network'];
                        $amountdeductedbyuser= $vc_typedata['pro_price'];
                        
                        if($paidwithcrypto==1){
                            $prod_cashback =$prod_crypto_cashback;
                        }else{
                            $prod_cashback =$prod_naira_cashback;
                        }
                     
                        $networkid=0;
                        $datacode=0;
                        $query = 'SELECT activebillsystem FROM systemsettings';
                        $stmt = $connect->prepare($query);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row =  mysqli_fetch_assoc($result);
                        $activebillsystem = $row['activebillsystem'];
                        // 1 1app, 2 club , 3 SH 4 VToass
                        if($activebillsystem==1){
                            $networkid=$prod_theenoapp;
                            $datacode=$prod_datacode_theenoapp;
                        }else  if($activebillsystem==2){
                            $networkid=$prod_connec_lub;
                            $datacode=$prod_datacode_connec_lub;
                        }else  if($activebillsystem==3){
                            $networkid=$prod_sh_network;
                            $datacode=$prod_datacode_sh_network;
                        }
                         
                        //$amounttogveuser=($prod_sale_percent/100)*$amount;
                        $amount=$amounttodeduct =$prod_sale_percent;// $amount-$amounttogveuser;
                        $cashbackis=round(($prod_cashback/100)*$amount,2);
                        if($cashbackis>100){
                            $cashbackis=100;
                        }
                        $profitemadeis=($amounttodeduct+$cryptoprofit)-$amountdeductedbyuser-$cashbackis;

                    //   check if user has balance
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
                                            // debit user
                                            $funddeducted=payDeductUserBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                
                                            if($funddeducted){
                                                        $reference=$orderId = createTransUniqueToken("BD$ordertagis$shortname", $user_id);
                                                        
                                                     
                                                            //   start here
                                                             $ordertime = date("h:ia, d M");
                                                              $confirmtime = date("h:ia, d M");
                                                              $status=1;
                                                              $auto=1;
                                                              $transtype=1;//1 spend 2 recive
                                                              $paymentstatus=1;
                                                              $systemsendwith=8;
                                                              $yes=1;
                                                              $billtype=1;//1 is top up, 2 is voucher, 3 is tickets
                                                            $message1 = "Bought $prod_nameis on $phoneno for $amounttodeduct NGN";
                                                            $query1 = "INSERT INTO userwallettrans (bill_trans_type,bill_cashback,userid,message,orderid,ordertime,confirmtime,status,approvaltype,amttopay,amountsentin,currencytag,transtype,paymentstatus,systemsendwith,wallettrackid,bills_trans,bill_main_prodtid,bill_product_no,bill_data_prodtid,billtypeis,bill_swap_tid_used,bill_profit_loose) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                            $addTransaction1 = $connect->prepare($query1);
                                                            $addTransaction1 ->bind_param("sssssssssssssssssssssss",$billtype,$cashbackis,$user_id,$message1,$orderId,$ordertime,$confirmtime,$status,$auto,$amount,$amounttodeduct,$currencytagis,$transtype,$paymentstatus,$systemsendwith,$currencytid,$yes,$product_trackid,$phoneno,$datacodetid,$maintype,$billswap_tid_use,$profitemadeis);
                                                       
                                                            if ($addTransaction1->execute()){
                                                                
                                                                   $userproductgiven=false;
                                                        // vend frm api
                                                        $userproductgiven=buyUserData($datacode,$networkid, $phoneno, $reference);
                                                       if($userproductgiven){
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
                                                                                    $subject = billTopUpTransactionSubject($userid,$reference); 
                                                                                    $to = $ussernamesenttomail;
                                                                                    $messageText =billTopUpTransactionText($userid, $reference);
                                                                                    $messageHTML = billTopTransactionHTML($userid, $reference);
                                                                                    sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                                    sendUserSMS($usersenttophone,$messageText);
                                                                                    // $userid,$message,$type,$ref,$status
                                                                                    bill_topup_success_user_noti($userid,$reference);
                                        notify_admin_noti_b_bot($message1,$user_id);
                                        notify_admin_bills_b_bot($message1,$user_id,$wallettype,"Top Up");
                                                                                       //  give user cashback
                                                                                    if (payAddUserCashbackBalance($user_id,$cashbackis)){
                                                                                        // store cashback history
                                                                                        $cashtranstype=2;
                                                                                        $cashbpaid=1;
                                                                                        $referaluserid=" ";
                                                                                        $track_id=createOrderidWIthTransData("CB-BILLS-",$user_id,"cashback_history","userid","cashbackorderid");//createUniqueToken(4,"cashback_history","cashbackorderid","CB-BILLS-",true,false,false);
                                                                                        $cashborderId = $track_id;//createTransUniqueToken("DATA_CB", $user_id);
                                                                                        $query1 = "INSERT INTO cashback_history (`userid`, `amount`, `trans_type`, `referaluserid`, `transorderid`,  `status`, `cashbackorderid`) VALUES (?,?,?,?,?,?,?)";
                                                                                        $addTransaction1 = $connect->prepare($query1);
                                                                                        // echo $connect->error;
                                                                                        $addTransaction1 ->bind_param("sssssss",$user_id,$cashbackis,$cashtranstype,$referaluserid,$orderId, $cashbpaid,$cashborderId);
                                                                                        $addTransaction1->execute();
                                                                                        // echo $addTransaction1->error;
                                                                                    }
                                                                                    
                                                                                    
                                                                                    $maindata['cashback']=$cashbackis;
                                                                                    $errordesc = " ";
                                                                                    $linktosolve = "https://";
                                                                                    $hint = [];
                                                                                    $errordata = [];
                                                                                    $method=getenv('REQUEST_METHOD');
                                                                                    $text = "Data purchased successfully";
                                                                                    $status = true;
                                                                                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                                    respondOK($data);      
                                                                  
                                                       }else{
                                                          // return user fund
                                                          payTransCancled($reference);
                                                          payAddUserBalance($user_id,$amounttodeduct,$currencytagis,$currencytid);
                                                                $errordesc="Server error try again later";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Server error try again later";
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
                $errordesc="Product not active,try again later";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Product not active,try again later";
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




