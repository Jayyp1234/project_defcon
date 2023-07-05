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
        
        $bindparams=[];
        $bindparamss="";
        
        $bindparams[]=$userid;
        $bindparamss.="s";
        if (!isset ($_POST['currency']) ) {  
            $currencyClause = "";
        } else {  
            $currecncytagis=cleanme($_POST['currency']);
            $currencyClause = " AND usersubwallet.currencytag = ?";
            $bindparams[]=$currecncytagis;
            $bindparamss.="s";
        }
        
        if (!isset ($_POST['search'])||empty($_POST['search'])) {  
            $searchClause = "";
        } else {  
            $searchtagis=cleanme($_POST['search']);
            $searchtagis="%$searchtagis%";
            $searchClause = " AND usersubwallet.coinsystemtag LIKE ?";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        
        if (!isset ($_POST['subtid'])||empty($_POST['subtid'])) {  
            $subtidClause = "";
        } else {  
            $subtid=cleanme($_POST['subtid']);
            $subtidClause = " AND usersubwallet.trackid = ?";
            $bindparams[]=$subtid;
            $bindparamss.="s";
        }
        
        
        
        $active=0;
        $sqlQuery = "SELECT * FROM usersubwallet WHERE userid=? ".$currencyClause." ".$searchClause." ".$subtidClause." ORDER BY walletbal DESC";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindparamss",...$bindparams);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            $thusdval=0;
            while($users = $result->fetch_assoc()){
                $coinminimum_to_send=0;
                $thusdval=0;
                $thpendusdval=0;
                $walletbal =$users['walletbal'];
                
                $walletpendbal=$users['walletpendbal'];
                $trackid=$users['trackid'];
                                                                $currencytagis=$users['currencytag'];
                                                                     $coinprodtrackidis=$users['coinsystrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $subtag=$users['coinsystemtag'];
                                                                $coindata=getCoinDetailsWithSubTag($subtag);
                                                                          if(isset($coindata['status'])&&$coindata['status']==1){
                                                                $coinprodtrackidis=	$coindata['producttrackid'];
                                                                $subwallettag=$coindata['subwallettag'];
                                                                $securitydata=getSendCoin_Security($subwallettag);
                                                                if(isset($securitydata['minimum_to_send'])){
                                                                $coinminimum_to_send=$securitydata['minimum_to_send']+0;
                                                                }
                                                                $coinname=$coindata['priceapiname'];
                                                                $coinrate=$coindata['rate'];
                                                                   $imglink=$coindata['img'];
                                                                 $livecoinvalue=$coindata['livecoinvalue'];
                                                                      $need_memo=$coindata['need_memo'];
                                                                $allow_send=$coindata['allow_send'];
                                                                 $coinsubwallettag=$coindata['coin_send_sys_tid'];
                                                                $allow_deposit=$coindata['allow_deposit'];
                                                                $allow_swap=$coindata['allow_swap'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
             $getlivevalu=0;                                                     
        $livevale =  $coindata['liveratefunctions'];  
        $coinplatform = $coindata['coinplatform'];
        $cointype =$coindata['cointype'];
          $coindecimal=$coindata['roundto_dp'];
     
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
                $sendcoindata=getSendCoinDetailsByTid($coinsubwallettag);
        $allowexternal=1;
        if(isset($sendcoindata['status'])){
            $allowexternal=$sendcoindata['status'];
        }
        
                                                                array_push( $allResponse,array("minimum_to_send"=>$coinminimum_to_send,"livevalue"=>$getlivevalu,"memo"=>$need_memo,"send"=>$allow_send,"deposit"=>$allow_deposit,"swap"=>$allow_swap,"img"=>$imglink,"coinrate"=>$coinrate,"currencytag"=>$currencytagis,"currency"=>$currencyname,"coinname"=>$coinname,"usdbalance"=>$thusdval,"usdpendbalance"=>$thpendusdval,"balance"=>$walletbal,"pendbal"=>$walletpendbal,"trackid"=>$trackid));
                                                                     }
                                                                         
                                                                     }
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
        }
        else{
            $allResponse = [];
            $maindata['userdata']= $allResponse;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data not found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
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