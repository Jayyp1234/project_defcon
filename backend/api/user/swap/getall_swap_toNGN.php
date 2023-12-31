<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/currency/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if ($method == 'GET') {
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
            
            $bindParams=array();
            $active=0;
            $on1=1;
            $bindParamTags="ss";
            $bindParams[]=$on1;
             $bindParams[]=$on1;
            $searchclause="";
            $typeClause="";
            if(isset($_GET['search'])&&$_GET['search']!=''){
              $search= cleanme($_GET['search']);
              $search="%{$search}%";
              $searchclause=" AND (name LIKE ? || shortname LIKE ?)";
              for($i=1;$i<=2;$i++){
                 $bindParamTags.="s";
                 $bindParams[]=$search;
              }
            }
            
            $allResponse = [];
            
                   $ngnsubwallet="NGNT55";
            $active=1;
            $systemtouseid=8;
            $sqlQuery = "SELECT id FROM currencywithdrawmethods WHERE status=? AND currencytag=? AND systemtouseid=?";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("sss",$active, $ngnsubwallet,$systemtouseid);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){  
                $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=?";
                $stmt= $connect->prepare($sqlQuery);
                $stmt->bind_param("ss",$userid, $ngnsubwallet);
                $stmt->execute();
                $result= $stmt->get_result();
                $numRow = $result->num_rows;
                if($numRow > 0){
                    while($users = $result->fetch_assoc()){
                        $currency="USD";
                        $fund=0;
                        $coinrate=getNGNtoUSDRate($currency,$fund);
                        $active=1;
                        $checkdata =  $connect->prepare("SELECT naira_fund_exhange_rate FROM  vc_type WHERE status=? LIMIT 1");
                        $checkdata->bind_param("i",$active);
                        $checkdata->execute();
                        $dresult4 = $checkdata->get_result();
                        if($dresult4->num_rows>0){
                                    $vc_typedata= $dresult4->fetch_assoc();
                                    $naira_fund_exhange_rate =$vc_typedata['naira_fund_exhange_rate'];
                                     $coinrate=$naira_fund_exhange_rate;
                        }
                        
                         
                        
                        
                        $thusdval=0;//$users['walletbal'] / $coinrate;
                        $walletbal=$users['walletbal'];
                        $trackid=$users['wallettrackid'];
                        
                      array_push( $allResponse,array("bill_crypto_fee"=>"0","towallettrackid"=>$trackid,"currency_from_tag"=>"NGNT55","cointrackid"=>$ngnsubwallet,"livevalue"=>$coinrate,"img"=>"https://app.cardify.co/assets/images/nigeria.png","coinrate"=>1,"name"=>"NGN","shortname"=>"NGN","usdbalance"=>round($thusdval,2),"balance"=>$walletbal,"trackid"=>$trackid,"crypto"=>0));
                    }
                }
            }
            $sqlQuery = "SELECT subwallettag FROM coinproducts WHERE status=? AND allow_swap=? $searchclause GROUP BY subwallettag";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("$bindParamTags",...$bindParams);
            $stmt->execute();
            $result1= $stmt->get_result();
            $numRow1 = $result1->num_rows;
            if($numRow1 > 0){
                while($users1 = $result1->fetch_assoc()){
                    $wallettag=$users1['subwallettag'];
                    
                    
                    
                    $active=0;
                    $sqlQuery = "SELECT * FROM usersubwallet WHERE userid=? AND coinsystemtag=? ORDER BY walletbal DESC";
                    $stmt= $connect->prepare($sqlQuery);
                    $stmt->bind_param("ss",$userid, $wallettag);
                    $stmt->execute();
                    $result= $stmt->get_result();
                    $numRow = $result->num_rows;
                    if($numRow > 0){
                        $thusdval=0;
                        while($users = $result->fetch_assoc()){
                            $thusdval=0;
                            $thpendusdval=0;
                            $paxfulusd=0;
                            $coinbaseusd=0;
                            $walletbal =$users['walletbal'];
                             
                            $walletpendbal=$users['walletpendbal'];
                            $trackid=$users['trackid'];
                            $currencytagis=$users['currencytag'];
                            $coinprodtrackidis=$users['coinsystrackid'];
                            //   get all addresses
                            // get coin detail $producttrackid
                            $subtag=$users['coinsystemtag'];
                            $coindata=getCoinDetailsWithSubTag($subtag);
                            $coinprodtrackidis=	$coindata['producttrackid'];
                            
                            
                                                    $active=1;    $ngnsubwallet="NGNT55";
                        $checksubwall =  $connect->prepare("SELECT conversion_rate,	currency_from_tag,currency_to_tag,trackid,bill_crypto_fee FROM swap_system_settings WHERE currency_to_tag=?  AND status=? AND 	coin_from_name=?");
                        $checksubwall->bind_param("sss",$ngnsubwallet,$active,$subtag);
                        $checksubwall->execute();
                        $dsubwallresult= $checksubwall->get_result();
                        if ($dsubwallresult->num_rows >0) {
                            $swapdata =$dsubwallresult->fetch_assoc();
                              $coinrate=$swapdata['conversion_rate'];
                              $currency_from_tag =$swapdata['currency_from_tag'];
                              $currency_to_tag =$swapdata['currency_to_tag'];
                              $bill_crypto_fee=$swapdata['bill_crypto_fee'];
                              
                              $towallettrackid =$swapdata['trackid'];
                            
                            $coinname=$coindata['priceapiname'];
                            $shortname=$coindata['priceapiname'];
                            $subwallettag=$coindata['subwallettag'];
                            $crypto_covinence_fee=$coindata['crypto_covinence_fee'];
                            $securitydata=getSendCoin_Security($subwallettag);
                            $coinminimum_to_send=$securitydata['minimum_to_send']+0;
                          
                            $imglink=$coindata['img'];
                            $livecoinvalue=$coindata['livecoinvalue'];
                            $need_memo=$coindata['need_memo'];
                            $allow_send=$coindata['allow_send'];
                            $allow_deposit=$coindata['allow_deposit'];
                            $allow_swap=$coindata['allow_swap'];
                            // get currency details $currency
                            $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                        
                            $getlivevalu=0;    
                            $paxliverate=0;
                            $coinbaseliverate=0;
                            $livevale =  $coindata['liveratefunctions'];  
                            $coinplatform = $coindata['coinplatform'];
                            $cointype =$coindata['cointype'];
                            $coindecimal=$coindata['roundto_dp'];
                            $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis);  
                                            
                                            
                            if ($getlivevalu!=0) {
                                $thusdval=$walletbal*$getlivevalu;
                                $thusdval=floor($thusdval * 100) / 100;
                                
                                $thpendusdval=$walletpendbal*$getlivevalu;
                                $thpendusdval=floor($thpendusdval * 100) / 100;
                            }
                    
                            // if($cointype=="btc"){
                            //     $paxfulusd=floor((($walletbal*$paxliverate) * 100)/100);
                            //     $coinbaseusd=floor((($walletbal*$coinbaseliverate) * 100)/100);;
                            // }
                            
                            // $walletbal=strval(round(sprintf('%.8f',floatval($walletbal)),$coindecimal));
                            // $walletpendbal=strval(round(sprintf('%.8f',floatval($walletpendbal)),$coindecimal));
                            $walletbal=number_format(floorp((float)$walletbal,$coindecimal), $coindecimal, '.', '');
                            $walletbal= $walletbal+0;
                            $walletpendbal=number_format(floorp((float)$walletpendbal,$coindecimal), $coindecimal, '.', '');
                            $walletpendbal =$walletpendbal +0;
                            $thpendusdval=number_format((float)$thpendusdval, 2, '.', '');
                            $thusdval=number_format((float)$thusdval, 2, '.', '');
                            
                            if($walletbal=="0"){
                                $walletbal="0.00";
                            }
                            if($walletpendbal=="0"){
                                $walletpendbal="0.00";
                            }
                            // if($thusdval==0){
                            //     ="0.00";
                            // }
                            // if($thpendusdval==0){
                            //     $thpendusdval="0.00";
                            // }
                            // "cbusdbalance20"=>$coinbaseusd-20,"pxusdbalance"=>$paxfulusd,"cbusdbalance"=>$coinbaseusd,"livevalue"=>$getlivevalu
                            $walletbal=" $walletbal";
                            
                            array_push( $allResponse,array("bill_crypto_fee"=>$bill_crypto_fee,"towallettrackid"=>$towallettrackid,"currency_to_tag"=>$currency_to_tag,"currency_from_tag"=>$currency_from_tag,"cointrackid"=>$subwallettag,"crypto"=>1,"livevalue"=>round($getlivevalu),"img"=>$imglink,"coinrate"=>$coinrate,"name"=>$coinname,"shortname"=>$shortname,"usdbalance"=>$thusdval,"balance"=>$walletbal,"trackid"=>$trackid));
                        }
                        }
                    }
                }
          
                        
            } 
            
                  if(count($allResponse)>0){
                    
                    
                    $maindata['userdata']= $allResponse;
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text = "Data found";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                }else{
                            $allResponse = [];
                        $maindata['userdata']= $allResponse;
                        $errordesc = "";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $text = "Data not found";
                        $method = getenv('REQUEST_METHOD');
                        $status = false;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                }
        }
    
}
else {
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