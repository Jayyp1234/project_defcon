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
        
        
        if (!isset ($_GET['currency']) ) {  
            $currencyClause = "";
        } else {  
            $currecncytagis=cleanme($_GET['currency']);
            $currencyClause = "AND usersubwallet.currencytag = ?";
        }
        
        
        $active=0;
        $sqlQuery = "SELECT * FROM usersubwallet WHERE userid=? ".$currencyClause;
        $stmt= $connect->prepare($sqlQuery);
        if (isset ($_GET['currency'])) { 
            $stmt->bind_param("ss",$userid,$currecncytagis);
        }else{
            $stmt->bind_param("s",$userid);
        }
 
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
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
                                                                $coindata=getCoinDetails($coinprodtrackidis);
                                                                $coinname=$coindata['name'];
                                                                $coinrate=$coindata['rate'];
                                                                 $livecoinvalue=$coindata['livecoinvalue'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
        $getlivevalu=0;    
        $paxliverate=0;
        $coinbaseliverate=0;
        $livevale =  $coindata['liveratefunctions'];  
        $coinplatform = $coindata['coinplatform'];
        $cointype =$coindata['cointype'];
        if($livecoinvalue==0){
        if($coinplatform==3){
             $getlivevalu=$livevale($cointype);
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
        
        $walletbal=round(sprintf('%.8f',floatval($walletbal)),8);
        $walletpendbal=round(sprintf('%.8f',floatval($walletpendbal)),8);
        
                                                                array_push( $allResponse,array("livevalue"=>$getlivevalu,"cbusdbalance20"=>$coinbaseusd-20,"pxusdbalance"=>$paxfulusd,"cbusdbalance"=>$coinbaseusd,"coinrate"=>$coinrate,"currencytag"=>$currencytagis,"currency"=>$currencyname,"coinname"=>$coinname,"usdbalance"=>$thusdval,"usdpendbalance"=>$thpendusdval,"balance"=>$walletbal,"pendbal"=>$walletpendbal,"trackid"=>$trackid));
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