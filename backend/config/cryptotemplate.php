<?php
require('coinpaymentClassCode.inc.php');


function CryptoGetSyssrttings(){
      global $connect;
  $alldata=[];
  $active=0;
  $getdataemail =  $connect->prepare("SELECT * FROM systemsettings WHERE id>?");
  $getdataemail->bind_param("s",$active);
  $getdataemail->execute();
  $getresultemail = $getdataemail->get_result();
  if( $getresultemail->num_rows> 0){
      $getthedata= $getresultemail->fetch_assoc();
      $alldata=$getthedata;
  }
  return $alldata;
}
function  GetActiveBgToken($coinplatform){
  global $connect;
  $alldata=[];
  $active=1;
  $getdataemail =  $connect->prepare("SELECT * FROM coinactivators WHERE status=? AND 	coinplatform=?");
  $getdataemail->bind_param("ss",$active,$coinplatform);
  $getdataemail->execute();
  $getresultemail = $getdataemail->get_result();
  if( $getresultemail->num_rows> 0){
      $getthedata= $getresultemail->fetch_assoc();
      $alldata=$getthedata;
  }
  return $alldata;
}
// FUNCTIONS functions related to the users
function cryptogetUserData($userid)
{
    //input type checks if its from post request or just normal function call
    global $connect;
    $alldata = [];

    $checkdata = $connect->prepare("SELECT  * FROM users  WHERE id=? || email = ?");
    $checkdata->bind_param("ss",$userid,$userid);
    $checkdata->execute();
    $getresultemail = $checkdata->get_result();
    if ($getresultemail->num_rows > 0) {
        $getthedata = $getresultemail->fetch_assoc();
        $alldata = $getthedata;
    }
    return $alldata;
}
function addsafepack($livevalue){
    $livevalueremove = $livevalue * 0.001;
    $livevalue=$livevalue-$livevalueremove;
    return $livevalue;
}
function getMeCoinLiveUSdValue($coinprodtrackidis){
        $coindata=getCoinDetails($coinprodtrackidis);
        $getlivevalu=0;   
        if(isset($coindata['livecoinvalue'])){
        $coinname=$coindata['name'];
        $coinrate=$coindata['rate'];
        $livecoinvalue=$coindata['livecoinvalue'];
                                                                
 
        $livevale =  $coindata['liveratefunctions'];  
        $coinplatform = $coindata['coinplatform'];
        $cointype =$coindata['cointype'];
        $liverate_cointype =$coindata['liverate_cointype'];
        
         $getlivevalu=0;
        if($livecoinvalue==0){
                if($coinplatform==3){
                     $getlivevalu=$livevale($liverate_cointype);
                }else{
                    if($cointype=="btc"){
                        $getlivevalu=addsafepack(cbcbtcrate());//$livevale();
                        // $getlivevalu=cbcbtcrate();
                       //$paxliverate=bcbtcrate();
                       // $coinbaseliverate=cbcbtcrate();
                    }else{
                        $getlivevalu=$livevale();
                    }                           
                }
        }else{
            $getlivevalu= $livecoinvalue;
        }
        }
        
        return $getlivevalu;
}
//api from BC- ETH RATE From Paxful.. Live Rate
function cbcbtcrate()
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=BTC';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}
    
function bcethrate()
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=ETH';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}
    
    
function bcltcrate()
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=LTC';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}

function bcusdcrate()
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=USDC';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}

function bcdairate()
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=DAI';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}

function getanycbliverate($coinname)
{
    $url= 'https://api.coinbase.com/v2/exchange-rates?currency=$coinname';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        $res=json_decode($result);
        $usdbtc= $res->data->rates->USD;
        // Will dump a beauty json :3
        return($usdbtc);
    }
}
    
function bcbchrate()
    {
        $url= 'https://api.coinbase.com/v2/exchange-rates?currency=BCH';
        $curl = curl_init();
        curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return 0;
        } else {
            $res=json_decode($result);
            $usdbtc= $res->data->rates->USD;
            // Will dump a beauty json :3
            return($usdbtc);
        }
    }
    
//Functions For Blockchain Transactions
//api from PAXFUL- BTC RATE From Paxful.. Live Rate
function bcbtcrate()
{
    $url= 'https://blockchain.info/tobtc?currency=USD&value=1';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
    ),
));
    $result = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return 0;
    } else {
        if ($result==0) {
            return 0;
        } else {
            $convertedresult = 1 / $result;
            $convertedresult= round($convertedresult, 0);
            // Will dump a beauty json :3
            return(json_decode($convertedresult, true));
        }
    }
}

// function bgbtliverate()
// {
//     $token="";
//       global $connect;
//     // get bittoken function
//     $done=false;
//     $token=CryptoGetSyssrttings()['bitavgtoken'];
    
//     $url= 'https://apiv2.bitcoinaverage.com/convert/global?from=BTC&to=USD&amount=1';
//     $curl = curl_init();
//     curl_setopt_array($curl, array(
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 60,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "GET",
//     CURLOPT_HTTPHEADER => array(
//         "x-ba-key: $token",
//     ),
// ));
//         $result = curl_exec($curl);
//         $err = curl_error($curl);
//         curl_close($curl);
//         if ($err) {
//             return 0;
//         } else {
//             $res=json_decode($result);
//             if(isset($res->price)){
//                  $usdbtc= $res->price;
//             }else{
//                 $usdbtc=0;
//             }
           
//             // Will dump a beauty json :3
//             return($usdbtc);
//         }
// }

function getCPcoins($coinname){
    global $connect;
    $finalusd=0;
    $done=false;
    $coinplatform=3;
    $servercall=GetActiveBgToken($coinplatform);
    $syscoinpaypriv =  $servercall['apisecrete'];
    $syscoinpaypub =  $servercall['apikey'];
    
    
    $cps = new CoinPaymentsAPI();
    $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
    $dtimein=time();
    $result = $cps->GetRates();//$ipn_url =
    //print_r(json_encode($result));  
    $usdrate=$result['result']['USD']['rate_btc'];
    $coinbtcrate=$result['result'][$coinname]['rate_btc'];
    $finalusd=$coinbtcrate/$usdrate;
    // print_r($finalusd);  
    //   result->USD[0]->rate_btc
    return  $finalusd;
}


function updateAllCPcoins(){
    global $connect;
    $finalusd=0;
    $done=false;
    $coinplatform=3;
    $servercall=GetActiveBgToken($coinplatform);
    $syscoinpaypriv =  $servercall['apisecrete'];
    $syscoinpaypub =  $servercall['apikey'];
    
    $cps = new CoinPaymentsAPI();
    $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
    $dtimein=time();
    $coinplatforms=3;
    $status=1;
    $sqlQuery = "SELECT id,	liverate_cointype FROM coinproducts WHERE liverate_coinplatform=? AND status=?";
    $stmt2= $connect->prepare($sqlQuery);
    $stmt2->bind_param("ss",$coinplatforms,$status);
    $stmt2->execute();
    $result2= $stmt2->get_result();
    $numRow2 = $result2->num_rows;
    if($numRow2 > 0){
        while($users2 = $result2->fetch_assoc()){
            
            $coinname = $users2['liverate_cointype'];
            $id = $users2['id'];
            
            $result = $cps->GetRates();//$ipn_url =
            //print_r(json_encode($result)); 
            if(isset($result['result']['USD']['rate_btc'])){
                $usdrate=$result['result']['USD']['rate_btc'];
                $coinbtcrate=$result['result'][$coinname]['rate_btc'];
                if($usdrate>0){
                    $finalusd=$coinbtcrate/$usdrate;
                    
                    $checkdata = $connect->prepare("UPDATE coinproducts SET livecoinvalue=? WHERE id=?");
                    $checkdata->bind_param("ss",$finalusd, $id);
                    $checkdata->execute();
                }
            }
        } 
    }
}
//
//api from PAXFUL- BTC RATE From Paxful.. Live Rate
function btcrate()
{

// // Payload which is sent to server
//     $payload = [
// // 'apikey' => PAXFULAPIKEY,
// 'nonce' => time(),
// ];

//     // Generation of apiseal
//     // Please note the PHP_QUERY_RFC3986 enc_type
//     $apiseal = hash_hmac('sha256', http_build_query($payload, "", '&', PHP_QUERY_RFC3986), PAXFULSECRET);

//     // Append the generated apiseal to payload
//     $payload['apiseal'] = $apiseal;

//     // Set request URL (in this case we check your balance)
//     $btc = curl_init('https://paxful.com/api/currency/btc');

//     // NOTICE that we send the payload as a string instead of POST parameters
//     curl_setopt($btc, CURLOPT_POSTFIELDS, http_build_query($payload, "", '&', PHP_QUERY_RFC3986));
//     curl_setopt($btc, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($btc, CURLOPT_HTTPHEADER, [
// 'Accept: application/json; version=1',
// 'Content-Type: text/plain',
// ]);

    
    
//     // fetch response
//     $response = curl_exec($btc);
//     // convert json response into array
//     $data = json_decode($response);
//     $err = curl_error($btc);
//     if ($err) {
//         $btcdata=0;
//     } else {
//         $btcdata=  $data->price;
//     }
//     curl_close($btc);
//     return $btcdata;
    //$response->status = 7
}
// bitgo api confirm
function bgtransverify($cointype, $walletid, $transferrefid, $authorizerkey)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://app.bitgo.com/api/v2/$cointype/wallet/$walletid/transfer/$transferrefid",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $authorizerkey"
    ),
 ));
    $resp= curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $joindata="$resp||$err";
    // $resp = file_get_contents($url, false, $context);
    return $joindata;
}

function bggenerateaddresss($cointype, $walletid, $username, $accesstoken)
{
    $curl = curl_init();
    $arr =  array(
            "chain"=> 1,
            "label"=> "$username",
            "lowPriority"=> false,
            "gasPrice"=> 0,
            "onToken"=> "ofcbtc"
      );
    //below is the base url
    $url ="https://app.bitgo.com/api/v2/$cointype/wallet/$walletid/address";
    $params =  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
          //u change the url infront based on the request u want
          CURLOPT_URL => $url,
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          //change this based on what u need post,get etc
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $accesstoken"
          ),
      ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $joindata="$response||$err";
    return $joindata;
}

function bgMaskgenerateaddresss($cointype, $walletid, $username, $accesstoken)
{
    $curl = curl_init();
    $arr =  array(
            "accesstoken"=> $accesstoken,
            "cointype"=> "$cointype",
            "walletid"=>$walletid,
            "username"=> $username,
      );
    //below is the base url
    $url ="https://usdt.africa/home/maskGenerate";
    $params =  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
          //u change the url infront based on the request u want
          CURLOPT_URL => $url,
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          //change this based on what u need post,get etc
          CURLOPT_CUSTOMREQUEST => "POST",
      ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $joindata="$response||$err";
    exit;
    return $joindata;
}

// exchange
function generateExchangeCPWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangesystem,$exchangetype){
    global $connect;
    $done=false;
    $servercall=GetActiveBgToken($coinplatform);
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        $senttypeproductname=$ddetails['name'];
        $typeprodrate=$ddetails['rate'];
        $typeprodtrackid=$ddetails['producttrackid'];
        $typeprodcoin=$ddetails['cointype'];
        // $systypetag = $ddetails['typetag'];

        $syscoinpaypriv =  $servercall['apisecrete'];
        $syscoinpaypub =  $servercall['apikey'];
        $istype=0;
        if($istype==1){
            // genarte all type coin
            $done=0;
            $notdone=0;
            $empty="";//so as not to pick main class of the types
            $empty2="null";
            $sysgetdata2 =  $connect->prepare("SELECT * FROM coinproducts WHERE  status=? AND typetag=? AND cointype!=? AND cointype!=? AND coinplatform=?");
            $sysgetdata2->bind_param("sssss",$active,$systypetag,$empty,$empty2,$coinplatform);
            $sysgetdata2->execute();
            $dsysresult2 = $sysgetdata2->get_result();
            while ($getsys2 = $dsysresult2->fetch_assoc()) {
                $senttypeproductname=$getsys2['name'];
                $typeprodrate=$getsys2['rate'];
                $typeprodtrackid=$getsys2['producttrackid'];
                $typeprodcoin=$getsys2['cointype'];

                $cps = new CoinPaymentsAPI();
                $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
                $dtimein=time();
                $result = $cps->GetCallbackAddress("$typeprodcoin", $label="$dashuname");//$ipn_url =
                if ($result['error'] == 'ok') {
                    // print_r($result['result']);
                    $btcaddress=$result['result']['address'];
                    $dmemosent="";
                    if(isset($result['result']['dest_tag'])){
                        $dmemosent=$result['result']['dest_tag'];
                    }
                    
                    $typeprodaddress="";
                    $addressid="";
                    $reddedmscript="";
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $trackcode=createUniqueToken(5,"exchangegenaddress","trackid","",true,true,false);
                    $insert_data4 = $connect->prepare("INSERT INTO  exchangegenaddress(userid, address, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,userselectedsyspay_id,exchangesytem) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data4->bind_param("sssssssssss",$userid,$btcaddress,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$dmemosent,$trackcode, $typeprodtrackid,$paymentid,$exchangesystem);
                    if ($insert_data4->execute()) {
                    $insert_data4->close();
                    
                                    // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$senttypeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$senttypeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$senttypeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                 // $userid,$message,$type,$ref,$status
                success_generate_address_user_noti($userid,$senttypeproductname);
                
                            // geerate transaction
                            $getExcsys = $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid = ?");
                            $getExcsys->bind_param("s",$exchangesystem);
                            $getExcsys->execute();
                            $result2 = $getExcsys->get_result();
                            if($result2->num_rows > 0){
                                    //bank exist
                                    $row2 = $result2->fetch_assoc();
                                    $currencytag=$row2['currencytag'];
                                    $exchangesystemtype=$row2['exchangesystem'];
                                    // create transaction
                                    if($exchangesystemtype==1){// this is needed for futire payment systems, if cedis or anything
                                                // add new transaction
                                                // this is checked since its only ofr naira payment, once other payment is added this has to be removed and check against the system type in exchange table
                                                $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                                $getUser->bind_param("ss",$paymentid,$userid);
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
            
                      
                                    
                                                    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype =$wallettrackid= $ourrrate = $username='';
                                                    $transhash ='';
                                                    // generating  order ref
                                                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                    // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","EXC",true,true,true);
                                                    $reference=$orderId = createTransUniqueToken("EXC", $userid);

                                                    $ordertime = date("h:ia, d M");
                                                    $confirmtime = '';
                                                    $status = 0; 
                                                    $username="";
                                                    $accountsentto="$bankname/$acctosendto";
                                                    $amttopay = 0;
                                                    $daddresssent = $btcaddress;
                                                    $manualstatus = 0;
                                                    $approvaltype = 1;
                                                    $message1 = "";
                                                    // insert the values to the transation for recieve
                                                    $transtype1 = 4;
                                                    $systemsendwith=3;
                                                    $iscrypto=1;
                                                    $none="";
                                                    $theusdval=0;
                                                    $seton=1;
                                                    $getPeeruser = $connect->prepare("SELECT * FROM peerstackmerchants WHERE dafualtagent = ?");
                                                    $getPeeruser->bind_param("s",$seton);
                                                    $getPeeruser->execute();
                                                    $peerresult = $getPeeruser->get_result();
                                                    //bank exist
                                                    $peerrow = $peerresult->fetch_assoc();
                                                    $peeragents =$peerrow['merchant_trackid'];
                                                    $theconf='';
                                                    $transwallet='';
                                                    $transfersentid='';
                                                    $maincoinval=0;
                                                    $user_id=$userid;
                                                    $transhash='';
                                                    $query1 = "INSERT INTO userwallettrans (externalexchange,peerstack_agent,isexchange,confirmation,addresssentto,syslivewallet,livetransid,payapiresponse,swapto,cointrackid,livecointype,iscrypto,theusdval,btcvalue,     systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                    $addTransaction1 = $connect->prepare($query1);
                                                    $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssssss",$exchangetype,$peeragents,$iscrypto,$theconf,$daddresssent,$transwallet,$transfersentid,$none,$currencytag,$typeprodtrackid, $typeprodcoin,$iscrypto, $theusdval, $maincoinval,  $systemsendwith,$accountsentto,$accbnkcode,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                    if ($addTransaction1->execute()){
                                                    
                                                    }
                                                }
                                    }
                            }
    
                
                        $done++;
                    }
            
                } else {
                    $notdone++;
                    // print 'Error: '.$result['error']."\n";
                }
            }
            if($done>0){
                 $done=true;
            }
        }else{
            $cps = new CoinPaymentsAPI();
            $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
            $dtimein=time();
            $result = $cps->GetCallbackAddress("$typeprodcoin", $label="$dashuname");//$ipn_url =
            if ($result['error'] == 'ok') {
                // print_r($result['result']);
                $btcaddress=$result['result']['address'];
                      $dmemosent="";
                    if(isset($result['result']['dest_tag'])){
                        $dmemosent=$result['result']['dest_tag'];
                    }
                 
                    $typeprodaddress="";
                    $addressid="";
                    $reddedmscript="";
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $trackcode=createUniqueToken(5,"usergenaddress","trackid","",true,true,false);
                    $insert_data4 = $connect->prepare("INSERT INTO  exchangegenaddress(userid, address, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,userselectedsyspay_id,exchangesytem) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data4->bind_param("sssssssssss",$userid,$btcaddress,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$dmemosent,$trackcode, $typeprodtrackid,$paymentid,$exchangesystem);
                    if ($insert_data4->execute()) {
                    $insert_data4->close();
                                    // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$senttypeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$senttypeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$senttypeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                // $userid,$message,$type,$ref,$status
                success_generate_address_user_noti($userid,$senttypeproductname);
                
                
                // geerate transaction
                            $getExcsys = $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid = ?");
                            $getExcsys->bind_param("s",$exchangesystem);
                            $getExcsys->execute();
                            $result2 = $getExcsys->get_result();
                            if($result2->num_rows > 0){
                                    //bank exist
                                    $row2 = $result2->fetch_assoc();
                                    $currencytag=$row2['currencytag'];
                                    $exchangesystemtype=$row2['exchangesystem'];
                                    // create transaction
                                    if($exchangesystemtype==1){// this is needed for futire payment systems, if cedis or anything
                                                // add new transaction
                                                // this is checked since its only ofr naira payment, once other payment is added this has to be removed and check against the system type in exchange table
                                                $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                                $getUser->bind_param("ss",$paymentid,$userid);
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
            
                      
                                    
                                                    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype =$wallettrackid= $ourrrate = $username='';
                                                    $transhash ='';
                                                    // generating  order ref
                                                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                    // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","EXC",true,true,true);
                                                    $reference=$orderId = createTransUniqueToken("EXC", $userid);

                                                    $ordertime = date("h:ia, d M");
                                                    $confirmtime = '';
                                                    $status = 0; 
                                                    $username="";
                                                    $accountsentto="$bankname/$acctosendto";
                                                    $amttopay = 0;
                                                    $daddresssent = $btcaddress;
                                                    $manualstatus = 0;
                                                    $approvaltype = 1;
                                                    $message1 = "";
                                                    // insert the values to the transation for recieve
                                                    $transtype1 = 4;
                                                    $systemsendwith=3;
                                                    $iscrypto=1;
                                                    $none="";
                                                    $theusdval=0;
                                                    $seton=1;
                                                    $getPeeruser = $connect->prepare("SELECT * FROM peerstackmerchants WHERE dafualtagent = ?");
                                                    $getPeeruser->bind_param("s",$seton);
                                                    $getPeeruser->execute();
                                                    $peerresult = $getPeeruser->get_result();
                                                    //bank exist
                                                    $peerrow = $peerresult->fetch_assoc();
                                                    $peeragents =$peerrow['merchant_trackid'];
                                                    $theconf='';
                                                    $transwallet='';
                                                    $transfersentid='';
                                                    $maincoinval=0;
                                                    $user_id=$userid;
                                                    $transhash='';
                                                    $query1 = "INSERT INTO userwallettrans (externalexchange,peerstack_agent,isexchange,confirmation,addresssentto,syslivewallet,livetransid,payapiresponse,swapto,cointrackid,livecointype,iscrypto,theusdval,btcvalue,     systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                    $addTransaction1 = $connect->prepare($query1);
                                                    $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssssss",$exchangetype,$peeragents,$iscrypto,$theconf,$daddresssent,$transwallet,$transfersentid,$none,$currencytag,$typeprodtrackid, $typeprodcoin,$iscrypto, $theusdval, $maincoinval,  $systemsendwith,$accountsentto,$accbnkcode,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                    if ($addTransaction1->execute()){
                                                    
                                                    }
                                                }
                                    }
                            }
                   $done=true;
                    }
            } else {
                // print 'Error: '.$result['error']."\n";
            }
        }


        
    }
    
    return $done;
}

function generateExchangeBGWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangesystem,$exchangetype){
     global $connect;
    // get bittoken function
    $done=false;
    $dbittoken=GetActiveBgToken($coinplatform)['apisecrete'];
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        $typeproductname=$ddetails['name'];
        $typeprodrate=$ddetails['rate'];
        $typeprodaddress=$ddetails['merchantid'];
        $typeprodtrackid=$ddetails['producttrackid'];
        $typeprodcoin=$ddetails['cointype'];
        
     $getmeaddress=bggenerateaddresss($typeprodcoin,$typeprodaddress,$dashuname,$dbittoken);
        $balverify = explode("||",$getmeaddress);
        $response = $balverify[0];
        $err = $balverify[1];
        //$dbittoken;
// print_r($response );
        if($err){
            // printerr
        } else {
            $balresp = json_decode($response);
            if (isset($balresp->address)) {
            $btcaddress=$balresp->address;
            $addressid=$balresp->id;
            $reddedmscript=$balresp->coinSpecific->redeemScript;
            $memo=" ";
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $trackcode=createUniqueToken(5,"exchangegenaddress","trackid","",true,true,false);
            $insert_data4 = $connect->prepare("INSERT INTO exchangegenaddress(userid, address, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,userselectedsyspay_id,exchangesytem) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $insert_data4->bind_param("sssssssssss",$userid,$btcaddress,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$memo,$trackcode, $typeprodtrackid,$paymentid,$exchangesystem);
            if ($insert_data4->execute()) {
                $insert_data4->close();
                // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$typeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$typeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$typeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
               // $userid,$message,$type,$ref,$status
               success_generate_address_user_noti($userid,$typeproductname);
                    // generate transaction
                                        $getExcsys = $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid = ?");
                                        $getExcsys->bind_param("s",$exchangesystem);
                                        $getExcsys->execute();
                                        $result2 = $getExcsys->get_result();
                                        if($result2->num_rows > 0){
                                                //bank exist
                                                $row2 = $result2->fetch_assoc();
                                                $currencytag=$row2['currencytag'];
                                                $exchangesystemtype=$row2['exchangesystem'];
                                                // create transaction
                                                if($exchangesystemtype==1){
                                                            // add new transaction
                                                            // this is checked since its only ofr naira payment, once other payment is added this has to be removed and check against the system type in exchange table
                                                            $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                                            $getUser->bind_param("ss",$paymentid,$userid);
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

                                  
                                                
                                                                $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype =$wallettrackid= $ourrrate = $username='';
                                                                $transhash ='';
                                                                // generating  order ref
                                                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                                // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","EXC",true,true,true);
                                                                $reference=$orderId = createTransUniqueToken("EXC", $userid);
                                                                $ordertime = date("h:ia, d M");
                                                                $confirmtime = '';
                                                                $status = 0; 
                                                                $username="";
                                                                $accountsentto="$bankname/$acctosendto";
                                                                $amttopay = 0;
                                                                $daddresssent = $btcaddress;
                                                                $manualstatus = 0;
                                                                $approvaltype = 1;
                                                                $message1 = "";
                                                                // insert the values to the transation for recieve
                                                                $transtype1 = 4;
                                                                $systemsendwith=3;
                                                                $iscrypto=1;
                                                                $none="";
                                                                $theusdval=0;
                                                                $seton=1;
                                                                $getPeeruser = $connect->prepare("SELECT * FROM peerstackmerchants WHERE dafualtagent = ?");
                                                                $getPeeruser->bind_param("s",$seton);
                                                                $getPeeruser->execute();
                                                                $peerresult = $getPeeruser->get_result();
                                                                //bank exist
                                                                $peerrow = $peerresult->fetch_assoc();
                                                                $peeragents =$peerrow['merchant_trackid'];
                                                                $theconf='';
                                                                $transwallet='';
                                                                $transfersentid='';
                                                                $maincoinval=0;
                                                                $user_id=$userid;
                                                                $transhash='';
                                                                $query1 = "INSERT INTO userwallettrans (externalexchange,peerstack_agent,isexchange,confirmation,addresssentto,syslivewallet,livetransid,payapiresponse,swapto,cointrackid,livecointype,iscrypto,theusdval,btcvalue,     systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                                                $addTransaction1 = $connect->prepare($query1);
                                                                $addTransaction1 ->bind_param("sssssssssssssssssssssssssssssssss",$exchangetype,$peeragents,$iscrypto,$theconf,$daddresssent,$transwallet,$transfersentid,$none,$currencytag,$typeprodtrackid, $typeprodcoin,$iscrypto, $theusdval, $maincoinval,  $systemsendwith,$accountsentto,$accbnkcode,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                                                if ($addTransaction1->execute()){
                                                                
                                                                        $done=true;
                                                                }
                
            }
            
                                                }
                                            
                                            
                                        }
                }
            }
        }
    }
    return $done;
}

function generateBGWallet($userid,$producttrackid,$currencytag,$subcurrencytrackid,$coinplatform,$addressname){
     global $connect;
    // get bittoken function
    $done=false;
    $dbittoken=GetActiveBgToken($coinplatform)['apisecrete'];
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        $typeproductname=$ddetails['name'];
        $typeprodrate=$ddetails['rate'];
        $typeprodaddress=$ddetails['merchantid'];
        $typeprodtrackid=$ddetails['producttrackid'];
        $typeprodcoin=$ddetails['cointype'];
        
     $getmeaddress=bggenerateaddresss($typeprodcoin,$typeprodaddress,$dashuname,$dbittoken);
        $balverify = explode("||",$getmeaddress);
        $response = $balverify[0];
        $err = $balverify[1];
        //$dbittoken;
// print_r($response );
        if($err){
            // printerr
        } else {
            $balresp = json_decode($response);
            if (isset($balresp->address)) {
            $btcaddress=$balresp->address;
            $addressid=$balresp->id;
            $reddedmscript=$balresp->coinSpecific->redeemScript;
            $memo=" ";
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $trackcode=createUniqueToken(5,"usergenaddress","trackid","",true,true,false);
            $insert_data4 = $connect->prepare("INSERT INTO  usergenaddress(userid, address, currencytag, subcurrencytrackid, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $insert_data4->bind_param("ssssssssssss",$userid,$btcaddress,$currencytag,$subcurrencytrackid,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$memo,$trackcode, $typeprodtrackid,$addressname);
            if ($insert_data4->execute()) {
                $insert_data4->close();
                
                

                // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$typeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$typeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$typeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
               // $userid,$message,$type,$ref,$status
               success_generate_address_user_noti($userid,$typeproductname);

                    
                    
                $done=true;
            }
            
}}
}
    
    return $done;
}

function generateCPWallet($userid,$producttrackid,$currencytag,$subcurrencytrackid,$coinplatform,$addressname){
     global $connect;
   $done=false;
    $servercall=GetActiveBgToken($coinplatform);
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        $senttypeproductname=$ddetails['name'];
        $typeprodrate=$ddetails['rate'];
        $typeprodtrackid=$ddetails['producttrackid'];
        $typeprodcoin=$ddetails['cointype'];
        // $systypetag = $ddetails['typetag'];

        $syscoinpaypriv =  $servercall['apisecrete'];
        $syscoinpaypub =  $servercall['apikey'];
        $istype=0;
        if($istype==1){
            // genarte all type coin
            $done=0;
            $notdone=0;
            $empty="";//so as not to pick main class of the types
            $empty2="null";
            $sysgetdata2 =  $connect->prepare("SELECT * FROM coinproducts WHERE  status=? AND typetag=? AND cointype!=? AND cointype!=? AND coinplatform=?");
            $sysgetdata2->bind_param("sssss",$active,$systypetag,$empty,$empty2,$coinplatform);
            $sysgetdata2->execute();
            $dsysresult2 = $sysgetdata2->get_result();
            while ($getsys2 = $dsysresult2->fetch_assoc()) {
                $senttypeproductname=$getsys2['name'];
                $typeprodrate=$getsys2['rate'];
                $typeprodtrackid=$getsys2['producttrackid'];
                $typeprodcoin=$getsys2['cointype'];

                $cps = new CoinPaymentsAPI();
                $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
                $dtimein=time();
                $result = $cps->GetCallbackAddress("$typeprodcoin", $label="$dashuname");//$ipn_url =
                if ($result['error'] == 'ok') {
                    // print_r($result['result']);
                    $btcaddress=$result['result']['address'];
                    $dmemosent="";
                    if(isset($result['result']['dest_tag'])){
                        $dmemosent=$result['result']['dest_tag'];
                    }
                    
                    $typeprodaddress="";
                    $addressid="";
                    $reddedmscript="";
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $trackcode=createUniqueToken(5,"usergenaddress","trackid","",true,true,false);
                    $insert_data4 = $connect->prepare("INSERT INTO  usergenaddress(userid, address, currencytag, subcurrencytrackid, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data4->bind_param("ssssssssssss",$userid,$btcaddress,$currencytag,$subcurrencytrackid,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$dmemosent,$trackcode, $typeprodtrackid,$addressname);
                    if ($insert_data4->execute()) {
                    $insert_data4->close();
                    
                                    // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$senttypeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$senttypeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$senttypeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                 // $userid,$message,$type,$ref,$status
                 success_generate_address_user_noti($userid,$senttypeproductname);

                
                
                   $done++;
                    }
            
                } else {
                    $notdone++;
                    // print 'Error: '.$result['error']."\n";
                }
            }
            if($done>0){
                 $done=true;
            }
        }else{
            $cps = new CoinPaymentsAPI();
            $cps->Setup("$syscoinpaypriv", "$syscoinpaypub");
        $dtimein=time();
            $result = $cps->GetCallbackAddress("$typeprodcoin", $label="$dashuname");//$ipn_url =
            if ($result['error'] == 'ok') {
                // print_r($result['result']);
                $btcaddress=$result['result']['address'];
                      $dmemosent="";
                    if(isset($result['result']['dest_tag'])){
                        $dmemosent=$result['result']['dest_tag'];
                    }
                 
                    $typeprodaddress="";
                    $addressid="";
                    $reddedmscript="";
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $trackcode=createUniqueToken(5,"usergenaddress","trackid","",true,true,false);
                    $insert_data4 = $connect->prepare("INSERT INTO  usergenaddress(userid, address, currencytag, subcurrencytrackid, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                    $insert_data4->bind_param("ssssssssssss",$userid,$btcaddress,$currencytag,$subcurrencytrackid,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$dmemosent,$trackcode, $typeprodtrackid,$addressname );
                    if ($insert_data4->execute()) {
                    $insert_data4->close();
                                    // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$senttypeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$senttypeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$senttypeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                // $userid,$message,$type,$ref,$status
                success_generate_address_user_noti($userid,$senttypeproductname);

                
                   $done=true;
                    }
            } else {
                // print 'Error: '.$result['error']."\n";
            }
        }


        
    }
    
    return $done;
}

function generateNewCB_address($name,$description,$cointype){
    $coinplatform=1;
    $address="";
   $dbittoken=GetActiveBgToken($coinplatform)['apikey'];

    $postdatais=array (
    'name'=>"$name",
    'description'=>"$description",
    'pricing_type'=>"no_price",
    );
    $jsonpostdata=json_encode($postdatais);
    // print($jsonpostdata);
    $url ="https://api.commerce.coinbase.com/charges/";
    $curl = curl_init();
    curl_setopt_array(
        $curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => trim($jsonpostdata),
        CURLOPT_HTTPHEADER => array(
                "X-CC-Api-Key: $dbittoken",
                "content-type: application/json",
                'X-CC-Version: 2018-03-22',
                 
        ),
    ));
    $response = curl_exec($curl);
    // print_r($response);
    $getaddressdata=json_decode($response);
   if(isset($getaddressdata->data->addresses->$cointype)){
      $address= $getaddressdata->data->addresses->$cointype;
   }
  return  $address;  
}

function verifyCB_PingSignature($payload, $sigHeader, $secret){
        $computedSignature = hash_hmac('sha256', $payload, $secret);
        // echo $computedSignature."____________";
        if (!verifyCbhashEqualTest($sigHeader, $computedSignature)) {
             http_response_code(400);
             echo "failed";
            exit;
        }else{
             http_response_code(200);
        }
}
function verifyCbhashEqualTest($str1, $str2){

        if (strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
}

function generateCBWallet($userid,$producttrackid,$currencytag,$subcurrencytrackid,$coinplatform){
     global $connect;
        // get bittoken function
    $done=false;
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        $typeproductname=$ddetails['name'];
        $typeprodrate=$ddetails['rate'];
        $typeprodaddress=$ddetails['merchantid'];
        $typeprodtrackid=$ddetails['producttrackid'];
        $typeprodcoin=$ddetails['cointype'];
        
    //End of coin base code 
    //Start of coin base code fetch        
    $addresstosendto= generateNewCB_address($userid,$subcurrencytrackid,$typeprodcoin);
    
    $senttowallettrackid=$subcurrencytrackid;
     // add new transction for user sent to
    // add new transaction
    $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype = $theusdval = $ourrrate = '';
    $transhash = '';
    // generating  order ref
    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
    // $orderId = createUniqueToken(18,"userwallettrans","orderid","CBT",true,true,true);
    $orderId = createTransUniqueToken("CBT", $userid);
    // generating  token
    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
    $companypayref = "";
    $ordertime = date("h:ia, d M");
    $confirmtime = "";
    $status = 0; 
    $amttopay =0;
    $manualstatus = 0;
    $approvaltype = 0;
    $message2 = " ";
    // insert the values to the transation for recieve
    $transtype1 = 2;
    $approvedby=" ";
    $usernamesentfrm="";
    $none="";
    $yes=1;
        $coinbase_crypto_amount="";       
    $coinbase_value_amount="";
    $yes=1;
    $systempaidwith=4;
    $coinbase_status="";
    $query1 = "INSERT INTO userwallettrans (systempaidwith,iscrypto,cointrackid,livecointype, btcvalue,theusdval,confirmation, subcurrency,addresssentto,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto,approvedby,usernamesentfrm) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $addTransaction1 = $connect->prepare($query1);
    $addTransaction1 ->bind_param("sssssssssssssssssssssssssss",$systempaidwith,$yes,$producttrackid,$typeprodcoin, $coinbase_crypto_amount,$coinbase_value_amount,$coinbase_status,$yes,$addresstosendto,$userid,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message2,$ordertime,$confirmtime,$companypayref,$status,$senttowallettrackid,$none,$approvedby,$usernamesentfrm);
    $addTransaction1->execute();
                    
                    
                                    // sms mail noti for who receive
                                  $btcaddress=  $addresstosendto;
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];
                
                
                $subject = generateAddressSubject($userid,$btcaddress,$typeproductname); 
                $to = $ussernamesenttomail;
                $messageText = generateAddressText($userid,$btcaddress,$typeproductname);
                $messageHTML = generateAddressHTML($userid,$btcaddress,$typeproductname);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                // $userid,$message,$type,$ref,$status
                  // $userid,$message,$type,$ref,$status
                  success_generate_address_user_noti($userid,$typeproductname);

                
                
      $done=$addresstosendto;                                                                  
    }
    return $done;
}

// HT FUNCTIONS
function base64url_encode($data) {
    return strtr(base64_encode($data), '+/', '-_');
} 

function build_callback_checksum($payload) {
    return base64url_encode(hash('sha256', $payload, true));
}

function build_request_checksum($params, $secret, $t, $r) {
    array_push($params, 't='.$t, 'r='.$r);
    sort($params);
    array_push($params, 'secret='.$secret);
    return hash('sha256', implode('&', $params));
}

function check_update_HT_expire_api($productTid){
    global $connect;
    $baseurl="https://vault.thresh0ld.com";
    $coinplatform=4;
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$productTid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        // extend api expire date
        $secret=$ddetails['apisecrete'];
        $token=$ddetails['apikey'];//api code
        $walletid=$ddetails['merchantid'];
        $ht_expire_token_time=$ddetails['ht_expire_token_time'];
        $refreshtoken=$ddetails['refreshtoken'];
        $dependon_net_conf=$ddetails['dependon_net_conf'];
        
        
        $subsecret=$ddetails['subapisecrete'];
        $subrefreshtoken=$ddetails['subrefreshtoken'];
        $subtoken=$ddetails['subapikey'];//api code
        $subwalletid=$ddetails['submerchantid'];
        $subht_expire_token_time =$ddetails['subht_expire_token_time'];
        
        $difference= round(($ht_expire_token_time-time())/60);
        $difference2= round(($subht_expire_token_time-time())/60);
        if($difference<=30){
              // refresh token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'refresh_code'=>$refreshtoken,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$walletid/refreshsecret?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $token",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $oldtoken=$token;
            $oldsecret=$secret;
            $token=$breakdata->api_code;
            $secret=$breakdata->api_secret;
            $refreshtoken=$breakdata->refresh_code;

            $updatePassQuery = "UPDATE coinproducts SET apisecrete = ?,refreshtoken=?,apikey=? WHERE apikey=? AND apisecrete=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sssss',$secret,$refreshtoken,$token,$oldtoken,$oldsecret);
            $updateStmt->execute();
            
            // Activate token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'wallet_id'=>$walletid,
                'api_code'=>$token,
                'api_secret' => $secret,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/apisecret/activate?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $token",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $expiredate=$breakdata->exp;
            $updatePassQuery = "UPDATE coinproducts SET ht_expire_token_time = ? WHERE producttrackid=? AND coinplatform=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sss',$expiredate,$productTid,$coinplatform);
            $updateStmt->execute();
        }
        if($difference2<=30&&$dependon_net_conf==1){
            // refresh token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'refresh_code'=>$subrefreshtoken,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $subsecret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$subwalletid/refreshsecret?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $subtoken",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $subtoken=$breakdata->api_code;
            $subsecret=$breakdata->api_secret;
            $subrefreshtoken=$breakdata->refresh_code;

            $updatePassQuery = "UPDATE coinproducts SET subapisecrete = ?,subrefreshtoken=?,subapikey=? WHERE producttrackid=? AND coinplatform=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sssss',$subsecret,$subrefreshtoken,$subtoken,$productTid,$coinplatform);
            $updateStmt->execute();
      
            // Activate token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'wallet_id'=>$subwalletid,
                'api_code'=>$subtoken,
                'api_secret' => $subsecret,
            ];
            $jsonpostdata=json_encode($postdatais);
     
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $subsecret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$subwalletid/apisecret/activate?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $subtoken",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $expiredate=$breakdata->exp;
            $updatePassQuery = "UPDATE coinproducts SET subht_expire_token_time = ? WHERE producttrackid=? AND coinplatform=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sss',$expiredate,$productTid,$coinplatform);
            $updateStmt->execute();
        }
    }
}

// // generate wallet for deposiit
function generate_HT_deposit_wallet($productTid,$userid,$currencytag,$subcurrencytrackid,$coinplatform,$addressname){
        global $connect;
        check_update_HT_expire_api($productTid);
        $baseurl="https://vault.thresh0ld.com";
        $done=false;
        $coinplatform=4;
        $active=1;
        $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
        $getdata->bind_param("sis",$productTid, $active,$coinplatform);
        $getdata->execute();
        $dresult = $getdata->get_result();
        if ($dresult->num_rows > 0) {
            $ddetails = $dresult->fetch_assoc();
            // extend api expire date
            $secret=$ddetails['apisecrete'];
            $token=$ddetails['apikey'];//api code
            $typeprodaddress=$walletid=$ddetails['merchantid'];
            $typeprodcoin=$ddetails['cointype'];
            $typeproductname=$ddetails['name'];
            $typeprodneed_memo=$ddetails['need_memo'];
            $dafualt_address=$ddetails['dafualt_address'];
            $trackcode=createUniqueToken(5,"usergenaddress","trackid","",true,true,false);
            $memo=" ";
            // generate address
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            if($typeprodneed_memo==0){
                $postdatais= [ 
                    'count'=>1,
                    'labels'=>array("$trackcode"),
                ];
            }else{
                $memo=createUniqueToken(5,"usergenaddress","memo","Cardify_",true,true,false);
                 $postdatais= [ 
                    'count'=>1,
                    'memos'=>array("$memo"),
                    'labels'=>array("$trackcode"),
                    
                ]; 
            }
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/addresses?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonpostdata,
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
                ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $addressGenerated="";
            if(isset($breakdata->addresses)){
              $addressGenerated=$breakdata->addresses[0];  
            }else if(isset($breakdata->txids)){
                 $addressGenerated=$breakdata->txids[0]; 
            }
            if($typeprodneed_memo==1){
                $addressGenerated=$dafualt_address;
            }
            
            $btcaddress=$addressGenerated;
            // $typeprodaddress=" ";
            $addressid=" ";
            $reddedmscript=" ";
           
            // echo $btcaddress;
            // VERIFY ADDRESS
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'addresses'=>array("$btcaddress"),
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$walletid/receiver/addresses/verify?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonpostdata,
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
                ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            if(isset($breakdata->addresses->$btcaddress)){
           
                 //SAVE GENERATED ADDRESS
                $insert_data4 = $connect->prepare("INSERT INTO  usergenaddress(userid, address, currencytag, subcurrencytrackid, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $insert_data4->bind_param("ssssssssssss",$userid,$btcaddress,$currencytag,$subcurrencytrackid,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$memo,$trackcode, $productTid,$addressname);
                if ($insert_data4->execute()) {
                    $insert_data4->close();
                    
                    // sms mail noti for who receive
                    $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                    $sysgetdata->bind_param("s",$userid);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    // check if user is sending to himself
                    $datais=$dsysresult7->fetch_assoc();
                    $ussernamesenttomail=$datais['email'];
                    $usersenttophone=$datais['phoneno'];
                    
                    
                    $subject = generateAddressSubject($userid,$btcaddress,$typeproductname); 
                    $to = $ussernamesenttomail;
                    $messageText = generateAddressText($userid,$btcaddress,$typeproductname);
                    $messageHTML = generateAddressHTML($userid,$btcaddress,$typeproductname);
                    sendUserMail($subject,$to,$messageText, $messageHTML);
                    sendUserSMS($usersenttophone,$messageText);
                  // $userid,$message,$type,$ref,$status
                  success_generate_address_user_noti($userid,$typeproductname);
                        
                        
                    $done=true;
            }
            }else{
                // generate_HT_deposit_wallet($productTid,$userid,$currencytag,$subcurrencytrackid,$coinplatform,$addressname);
            }
        } 
        return $done;  
}

function generateHTWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangesystem,$exchangetype){
     global $connect;
    // get bittoken function
    $done=false;
    check_update_HT_expire_api($producttrackid);
    $baseurl="https://vault.thresh0ld.com";
    $userdsatas= cryptogetUserData($userid);
    $dashuname=$userdsatas['username'];
    
    $coinplatform=4;
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$producttrackid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {            
            $ddetails = $dresult->fetch_assoc();
            // extend api expire date
            $typeprodneed_memo=$ddetails['need_memo'];
            $dafualt_address=$ddetails['dafualt_address'];
            $typeproductname=$ddetails['name'];
            $typeprodrate=$ddetails['rate'];
            $typeprodaddress=$walletid=$ddetails['merchantid'];
            $typeprodtrackid=$ddetails['producttrackid'];
            $typeprodcoin=$ddetails['cointype'];
            $secret=$ddetails['apisecrete'];
            $token=$ddetails['apikey'];//api code
               $memo=" ";
              // generate address
            $trackcode=createUniqueToken(5,"exchangegenaddress","trackid","",true,true,false);
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            if($typeprodneed_memo==0){
                $postdatais= [ 
                    'count'=>1,
                    'labels'=>array("$trackcode"),
                ];
            }else{
                $memo=createUniqueToken(5,"exchangegenaddress","memo","Cardify_",true,true,false);
                 $postdatais= [ 
                    'count'=>1,
                    'memos'=>array("$memo"),
                    'labels'=>array("$trackcode"),
                    
                ]; 
            }
            
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/addresses?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonpostdata,
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
            ));
            
                $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $addressGenerated="";
            if(isset($breakdata->addresses)){
              $addressGenerated=$breakdata->addresses[0];  
            }else if(isset($breakdata->txids)){
                 $addressGenerated=$breakdata->txids[0]; 
            }
            if($typeprodneed_memo==1){
                $addressGenerated=$dafualt_address;
            }
            
            $btcaddress=$addressGenerated;
            // $typeprodaddress=" ";
            $addressid=" ";
            $reddedmscript=" ";
            
            

           
            // echo $btcaddress;
            // VERIFY ADDRESS
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'addresses'=>array("$btcaddress"),
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$walletid/receiver/addresses/verify?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonpostdata,
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
                ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            if(isset($breakdata->addresses->$btcaddress)){
                
                     //SAVE GENERATED ADDRESS
                $insert_data4 = $connect->prepare("INSERT INTO exchangegenaddress(userid, address, cointype, livewallet, liveaddressid, redeemscript, memo, trackid,coinprodtrackid,userselectedsyspay_id,exchangesytem) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $insert_data4->bind_param("sssssssssss",$userid,$btcaddress,$typeprodcoin,$typeprodaddress,$addressid,$reddedmscript,$memo,$trackcode, $typeprodtrackid,$paymentid,$exchangesystem);
                if ($insert_data4->execute()) {
                    $insert_data4->close();
                    // sms mail noti for who receive
                    $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                    $sysgetdata->bind_param("s",$userid);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    // check if user is sending to himself
                    $datais=$dsysresult7->fetch_assoc();
                    $ussernamesenttomail=$datais['email'];
                    $usersenttophone=$datais['phoneno'];
                    
                    
                    $subject = generateAddressSubject($userid,$btcaddress,$typeproductname); 
                    $to = $ussernamesenttomail;
                    $messageText = generateAddressText($userid,$btcaddress,$typeproductname);
                    $messageHTML = generateAddressHTML($userid,$btcaddress,$typeproductname);
                    sendUserMail($subject,$to,$messageText, $messageHTML);
                    sendUserSMS($usersenttophone,$messageText);
                   // $userid,$message,$type,$ref,$status
                   success_generate_address_user_noti($userid,$typeproductname);
                    // generate transaction
                    $getExcsys = $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid = ?");
                    $getExcsys->bind_param("s",$exchangesystem);
                    $getExcsys->execute();
                    $result2 = $getExcsys->get_result();
                    if($result2->num_rows > 0){
                        //bank exist
                        $row2 = $result2->fetch_assoc();
                        $currencytag=$row2['currencytag'];
                        $exchangesystemtype=$row2['exchangesystem'];
                        // create transaction
                        if($exchangesystemtype==1){
                                    // add new transaction
                                    // this is checked since its only ofr naira payment, once other payment is added this has to be removed and check against the system type in exchange table
                                    $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
                                    $getUser->bind_param("ss",$paymentid,$userid);
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
                    
                    
                        
                                        $addresssentto = $livetransid = $btcvalue = $syslivewallet = $liveusdrate = $cointrackid =  $livecointype =$wallettrackid= $ourrrate = $username='';
                                        $transhash ='';
                                        // generating  order ref
                                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                        // $reference=$orderId = createUniqueToken(18,"userwallettrans","orderid","EXC",true,true,true);
                                        $reference=$orderId = createTransUniqueToken("EXC", $userid);
                                        $ordertime = date("h:ia, d M");
                                        $confirmtime = '';
                                        $status = 0; 
                                        $username="";
                                        $accountsentto="$bankname/$acctosendto";
                                        $amttopay = 0;
                                        $daddresssent = $btcaddress;
                                        $manualstatus = 0;
                                        $approvaltype = 1;
                                        $message1 = "";
                                        // insert the values to the transation for recieve
                                        $transtype1 = 4;
                                        $systemsendwith=3;
                                        $iscrypto=1;
                                        $none="";
                                        $theusdval=0;
                                        $seton=1;
                                        $getPeeruser = $connect->prepare("SELECT * FROM peerstackmerchants WHERE dafualtagent = ?");
                                        $getPeeruser->bind_param("s",$seton);
                                        $getPeeruser->execute();
                                        $peerresult = $getPeeruser->get_result();
                                        //bank exist
                                        $peerrow = $peerresult->fetch_assoc();
                                        $peeragents =$peerrow['merchant_trackid'];
                                        $theconf='';
                                        $transwallet='';
                                        $transfersentid='';
                                        $maincoinval=0;
                                        $user_id=$userid;
                                        $transhash='';
                                        $query1 = "INSERT INTO userwallettrans (memo,externalexchange,peerstack_agent,isexchange,confirmation,addresssentto,syslivewallet,livetransid,payapiresponse,swapto,cointrackid,livecointype,iscrypto,theusdval,btcvalue,     systemsendwith,bankaccsentto,bankacccode,userid,transhash,orderid,status,manualstatus,approvaltype,amttopay,currencytag,transtype,message,ordertime,confirmtime,paymentref,paymentstatus,wallettrackid,usernamesentto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                                        $addTransaction1 = $connect->prepare($query1);
                                        $addTransaction1 ->bind_param("ssssssssssssssssssssssssssssssssss",$memo,$exchangetype,$peeragents,$iscrypto,$theconf,$daddresssent,$transwallet,$transfersentid,$none,$currencytag,$typeprodtrackid, $typeprodcoin,$iscrypto, $theusdval, $maincoinval,  $systemsendwith,$accountsentto,$accbnkcode,$user_id,$transhash, $orderId, $status,$manualstatus,$approvaltype,$amttopay,$currencytag,$transtype1,$message1,$ordertime,$confirmtime,$transhash,$status,$wallettrackid,$username);
                                        if ($addTransaction1->execute()){
                                        
                                                $done=true;
                                        }
                    
                    }
                    
                        }
                    
                    
                    }
                    }
                    else{
                        // echo $insert_data4->error;
                    }
            }else{
            //   generateHTWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangesystem,$exchangetype); 
            }
        }
    
    return $done;
}

function verify_HT_deposit_ping($productTid,$txid,$voutindex){
        global $connect;
        check_update_HT_expire_api($productTid);
        $baseurl="https://vault.thresh0ld.com";
        $done=false;
        $userdetails="";
        $coinplatform=4;
        $active=1;
        $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=?  AND coinplatform=?");
        $getdata->bind_param("ss",$productTid,$coinplatform);
        $getdata->execute();
        $dresult = $getdata->get_result();
        if ($dresult->num_rows > 0) {
            $ddetails = $dresult->fetch_assoc();
            // extend api expire date
            $secret=$ddetails['apisecrete'];
            $token=$ddetails['apikey'];//api code
            $walletid=$ddetails['merchantid'];
            $dependon_net_conf=$ddetails['dependon_net_conf'];
            if($dependon_net_conf==1){
              $walletid=$ddetails['submerchantid']; 
                $token=$ddetails['subapikey'];//api code
                 $secret=$ddetails['subapisecrete'];
            }
            $typeprodcoin=$ddetails['cointype'];
            $typeproductname=$ddetails['name'];
            
            // generate address
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            // params contains all query strings and post body if any
            $params = [];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/receiver/notifications/txid/$txid/$voutindex?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
                ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            // $breakdata = json_decode($userdetails);
            
        }
        return $userdetails;  
}

function check_update_HTSender_expire_api($productTid){
    global $connect;
    $baseurl="https://vault.thresh0ld.com";
    $coinplatform=4;
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$productTid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
       if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        // extend api expire date
        $secret=$ddetails['apisecrete'];
        $token=$ddetails['apikey'];//api code
        $walletid=$ddetails['merchantid'];
        $ht_expire_token_time=$ddetails['ht_expire_token_time'];
        $refreshtoken=$ddetails['refreshtoken'];
        $dependon_net_conf=$ddetails['dependon_net_conf'];
        
        
        $subsecret=$ddetails['subapisecrete'];
        $subrefreshtoken=$ddetails['subrefreshtoken'];
        $subtoken=$ddetails['subapikey'];//api code
        $subwalletid=$ddetails['submerchantid'];
        $subht_expire_token_time =$ddetails['subht_expire_token_time'];
        
        $difference= round(($ht_expire_token_time-time())/60);
        $difference2= round(($subht_expire_token_time-time())/60);
        if($difference<=30){
              // refresh token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'refresh_code'=>$refreshtoken,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$walletid/refreshsecret?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $token",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $oldtoken=$token;
            $oldsecret=$secret;
            $token=$breakdata->api_code;
            $secret=$breakdata->api_secret;
            $refreshtoken=$breakdata->refresh_code;

            $updatePassQuery = "UPDATE coinproducts_send SET apisecrete = ?,refreshtoken=?,apikey=? WHERE apikey=? AND apisecrete=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sssss',$secret,$refreshtoken,$token,$oldtoken,$oldsecret);
            $updateStmt->execute();
            
            // Activate token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'wallet_id'=>$walletid,
                'api_code'=>$token,
                'api_secret' => $secret,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/apisecret/activate?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $token",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $expiredate=$breakdata->exp;
            // $updatePassQuery = "UPDATE coinproducts_send SET ht_expire_token_time = ? WHERE producttrackid=? AND coinplatform=?";
            // $updateStmt = $connect->prepare($updatePassQuery);
            // $updateStmt->bind_param('sss',$expiredate,$productTid,$coinplatform);
            // $updateStmt->execute();
            $updatePassQuery = "UPDATE coinproducts_send SET ht_expire_token_time = ?  WHERE apikey=? AND apisecrete=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sss',$expiredate,$token,$secret);
            $updateStmt->execute();
        }
        if($difference2<=30&&$dependon_net_conf==1){
            // refresh token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'refresh_code'=>$subrefreshtoken,
            ];
            $jsonpostdata=json_encode($postdatais);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $subsecret, $dtime, $rndom);
            $url ="$baseurl/v1/sofa/wallets/$subwalletid/refreshsecret?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $subtoken",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $subtoken=$breakdata->api_code;
            $subsecret=$breakdata->api_secret;
            $subrefreshtoken=$breakdata->refresh_code;

            $updatePassQuery = "UPDATE coinproducts_send SET subapisecrete = ?,subrefreshtoken=?,subapikey=? WHERE producttrackid=? AND coinplatform=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sssss',$subsecret,$subrefreshtoken,$subtoken,$productTid,$coinplatform);
            $updateStmt->execute();
      
            // Activate token
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            $postdatais= [ 
                'wallet_id'=>$subwalletid,
                'api_code'=>$subtoken,
                'api_secret' => $subsecret,
            ];
            $jsonpostdata=json_encode($postdatais);
     
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $subsecret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$subwalletid/apisecret/activate?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $jsonpostdata,
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $subtoken",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
            $userdetails = curl_exec($curl);
            // print_r($userdetails);
            // exit;
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
            $expiredate=$breakdata->exp;
            $updatePassQuery = "UPDATE coinproducts_send SET subht_expire_token_time = ? WHERE producttrackid=? AND coinplatform=?";
            $updateStmt = $connect->prepare($updatePassQuery);
            $updateStmt->bind_param('sss',$expiredate,$productTid,$coinplatform);
            $updateStmt->execute();
        }
    }
}

function check_SenderBal_api($productTid){
    global $connect;
    check_update_HTSender_expire_api($productTid);
    $baseurl="https://vault.thresh0ld.com";
    $coinplatform=4;
    $active=1;
    $balance=0;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE producttrackid=? AND status=? AND coinplatform=?");
    $getdata->bind_param("sis",$productTid, $active,$coinplatform);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $ddetails = $dresult->fetch_assoc();
        // extend api expire date
        $secret=$ddetails['apisecrete'];
        $token=$ddetails['apikey'];//api code
        $walletid=$ddetails['merchantid'];
        $ht_expire_token_time=$ddetails['ht_expire_token_time'];
        $refreshtoken=$ddetails['refreshtoken'];
        $dependon_net_conf=$ddetails['dependon_net_conf'];
        $subsecret=$ddetails['subapisecrete'];
        $subrefreshtoken=$ddetails['subrefreshtoken'];
        $subtoken=$ddetails['subapikey'];//api code
        $subwalletid=$ddetails['submerchantid'];
        $subht_expire_token_time =$ddetails['subht_expire_token_time'];
        if($dependon_net_conf==1){
            $secret=$subsecret;
            $token=$subtoken;//api code
            $typeprodaddress=$walletid=$subwalletid;
        }
        
          // refresh token
        $dtime=time();//round(microtime(true)*1000);
        $rndom=$dtime.mt_rand(1111,9999);
        // params contains all query strings and post body if any
        $params = [];
        $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
        $url ="$baseurl/v1/sofa/wallets/$walletid/sender/balance?t=$dtime&r=$rndom";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-API-CODE: $token",
                    "X-CHECKSUM: $apichecsum",
                    "content-type: application/json",
                    "User-Agent: php"
                ),
            ));
        $userdetails = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if($dependon_net_conf==1){
            $balance=$breakdata->token_balance;   
        }else{
            $balance=$breakdata->balance;  
        }
    }
    
    return $balance;
}

function sendFrom_HT_sender_wallet($productTid,$userid,$currencytag,$coinplatform,$address,$amount,$memo,$message,$realorderid,$coinprodtrackidis){
        global $connect;
        check_update_HTSender_expire_api($productTid);
        $baseurl="https://vault.thresh0ld.com";
        $done=false;
        $coinplatform=4;
        $active=1;
        $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE producttrackid=? AND status=? AND coinplatform=?");
        $getdata->bind_param("sis",$productTid, $active,$coinplatform);
        $getdata->execute();
        $dresult = $getdata->get_result();
        if ($dresult->num_rows > 0) {
            $ddetails = $dresult->fetch_assoc();
            // extend api expire date
            $dependon_net_conf=$ddetails['dependon_net_conf'];
            $secret=$ddetails['apisecrete'];
            
            $token=$ddetails['apikey'];//api code
            $typeprodaddress=$walletid=$ddetails['merchantid'];
            $cointype=$typeprodcoin=$ddetails['cointype'];
            $typeproductname=$ddetails['name'];
            $orderprefix=$ddetails['orderprefix']; 
            $coinplatform = $ddetails['coinplatform'];
            if($dependon_net_conf==1){
             $secret=$ddetails['subapisecrete'];
             $token=$ddetails['subapikey'];//api code
              $typeprodaddress=$walletid=$ddetails['submerchantid'];
            }
            // convert fund to USD
             $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis);
            
            $theusdval=$amount*$getlivevalu;
            $active=1;
            $getdata =  $connect->prepare("SELECT fee,block FROM coinsend_fee WHERE cointrackid=? AND status=? AND max >=? AND min <=?");
            $getdata->bind_param("siss",$productTid, $active,$theusdval,$theusdval);
            $getdata->execute();
            $dresult = $getdata->get_result();
            if ($dresult->num_rows > 0) {
            $ddetails = $dresult->fetch_assoc();
                // extend api expire date
                $coinblock=$ddetails['block'];
                $coinfee=$ddetails['fee'];
            }
            
            // calculate and know hwat fee to use here
            // High priority - 2 Blocks
            // Medium priority - 12 Blocks
            // Low priority - 60 Blocks
            // Very Low Priority - 300 Blocks
            // manual fee dont work for below
            // XRP, XLM, BNB, DOGE, EOS, TRX, ADA, DOT and SOL
            $manualfeeallowed=false;
            $usemanualfee=0;
            $useblock=0;
            $manual_notallowedcoin=array("XRP", "XLM", "BNB", "DOGE", "EOS", "TRX", "ADA", "DOT","SOL");
            $manual_block_notallowedcoin=array("USDT TRC20",);
            //only us block if manual fee is not allowed
            if(!in_array($typeprodcoin,$manual_notallowedcoin)&&!in_array($typeprodcoin,$manual_block_notallowedcoin)){
              $manualfeeallowed=true;  
              $usemanualfee=$coinfee;
            }
            if(!$manualfeeallowed&&!in_array($typeprodcoin,$manual_block_notallowedcoin)){
                $useblock=$coinblock;
            }
             // calculate and know hwat fee to use here
            $orderid="$orderprefix"."$realorderid";
            // generate address
            $dtime=time();//round(microtime(true)*1000);
            $rndom=$dtime.mt_rand(1111,9999);
            // https://wtools.io/convert-json-to-php-array
            if($usemanualfee!=0 && $manualfeeallowed){
                $usemanualfee=intval($usemanualfee);
                $postdatais= array ('requests' => array (0 => array ("order_id"=>"$orderid","address"=>"$address","amount"=>"$amount","memo"=>"$memo","user_id"=>"$userid","message"=>"$message", "manual_fee"=>$usemanualfee),),
                'ignore_black_list' => false,);
            }else if( $useblock!=0){
                $useblock=intval($useblock);
                $postdatais= array ('requests' => array (0 => array ("order_id"=>"$orderid","address"=>"$address","amount"=>"$amount","memo"=>"$memo","user_id"=>"$userid","message"=>"$message", "block_average_fee"=>$useblock),),
                'ignore_black_list' => false,);
            }else{
               $postdatais= array ('requests' => array (0 => array ("order_id"=>"$orderid","address"=>"$address","amount"=>"$amount","memo"=>"$memo","user_id"=>"$userid","message"=>"$message"),),
                'ignore_black_list' => false,);  
            }
            

            $jsonpostdata=json_encode($postdatais);
            // print($jsonpostdata);
            // params contains all query strings and post body if any
            $params = [$jsonpostdata];
            $apichecsum = build_request_checksum($params, $secret, $dtime, $rndom);
    
            $url ="$baseurl/v1/sofa/wallets/$walletid/sender/transactions?t=$dtime&r=$rndom";
            $curl = curl_init();
            curl_setopt_array(
                    $curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $jsonpostdata,
                    CURLOPT_HTTPHEADER => array(
                        "X-API-CODE: $token",
                        "X-CHECKSUM: $apichecsum",
                        "content-type: application/json",
                        "User-Agent: php"
                    ),
                ));
            $userdetails = curl_exec($curl);
            // echo $token;
            // print_r($userdetails); 
            $err = curl_error($curl);
            curl_close($curl);
            $breakdata = json_decode($userdetails);
           
            if(isset($breakdata->results->$orderid)){
                 //SAVE GENERATED ADDRESS
                $livetransidis=$breakdata->results->$orderid;
                $processing=2;
                $insert_data4 = $connect->prepare("UPDATE userwallettrans SET livetransid=?,status=? WHERE orderid=?");
                $insert_data4->bind_param("sss",$livetransidis,$processing,$realorderid);
                    if ($insert_data4->execute()) {
                        $insert_data4->close();
                        
                        // sms mail noti for who receive
                        $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                        $sysgetdata->bind_param("s",$userid);
                        $sysgetdata->execute();
                        $dsysresult7 = $sysgetdata->get_result();
                        // check if user is sending to himself
                        $datais=$dsysresult7->fetch_assoc();
                        $ussernamesenttomail=$datais['email'];
                        $usersenttophone=$datais['phoneno'];
                        
                        
                        $subject = sendToAddressInitiatedSubject($userid,$realorderid); 
                        $to = $ussernamesenttomail;
                        $messageText = sendToAddressInitiatedText($userid,$realorderid);
                        $messageHTML = sendToAddressInitiatedHTML($userid,$realorderid);
                        sendUserMail($subject,$to,$messageText, $messageHTML);
                        sendUserSMS($usersenttophone,$messageText);
                        // $userid,$message,$type,$ref,$status
                        crypto_sendout_pend_user_noti($userid,$realorderid);
                        $done=true;
                    }
            }
        } 
        return $done;  
}
?>