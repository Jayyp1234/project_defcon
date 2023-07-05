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
                    if (!isset ($_POST['wallettid']) ) {  
                        $currencyClause = "";
                    } else {  
                        $currecncytagis=cleanme($_POST['wallettid']);
                        $currencyClause = " AND usersubwallet.trackid = ?";
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
                    if (!isset ($_POST['amttopay'])||empty($_POST['amttopay'])) {  
                        $amttopay = 0;
                    } else {  
                        $amttopay=cleanme($_POST['amttopay']);
                    }
                    
                    
                    
                    $active=0;
                    $sqlQuery = "SELECT coinsystemtag FROM usersubwallet WHERE userid=? ".$currencyClause." ".$searchClause." ".$subtidClause;
                    $stmt= $connect->prepare($sqlQuery);
                    $stmt->bind_param("$bindparamss",...$bindparams);
                    $stmt->execute();
                    $result= $stmt->get_result();
                    $numRow = $result->num_rows;
                    if($numRow > 0){
                        $allResponse = [];
                        $users = $result->fetch_assoc();
                        $systag =$users['coinsystemtag'];
                        $active=1;
                        $sqlQuery = "SELECT producttrackid,name,shortname,priceapiname FROM  coinproducts WHERE 	subwallettag=? AND status=?";
                        $stmt= $connect->prepare($sqlQuery);
                        $stmt->bind_param("ss", $systag,$active);
                        $stmt->execute();
                        $result= $stmt->get_result();
                        $numRow = $result->num_rows;
                                if($numRow > 0){
                                    while( $users = $result->fetch_assoc()){
                                          $ptid =$users['producttrackid'];
                                          $netname=$users['name'];
                                          $shortname=$users['shortname'];
                                          $priceapiname =$users['priceapiname'];
                                          $fee=0;
                                          
                                          
                                        $coindata=getCoinDetails($ptid);
                                        $coinsubwallettag=$coindata['coin_send_sys_tid'];
                                        $sendcoindata=getSendCoinDetailsByTid($coinsubwallettag);
                                        $getlivevalu=0;  
                                        $coinname=$coindata['name'];
                                        $coinrate=$coindata['rate'];
                                        $livevale =  $coindata['liveratefunctions'];  
                                        $coinplatform = $coindata['coinplatform'];
                                        $cointype =$coindata['cointype'];
                                        // get currency details $currency 
                       
                                       $getlivevalu=getMeCoinLiveUSdValue($ptid); 
                            // check if balance reach
                           if( $getlivevalu!=0){
                                    $amttopayusd=$amttopay * $getlivevalu;
                                    $productTid=$sendcoindata['producttrackid'];
                                    $sendcoinplatform=$sendcoindata['coinplatform'];
                                    $sendcointype =$sendcoindata['cointype'];
                              
                                            $active=1;
                                            $coinfee=0;
                                            // calculate fee,
                                            $getdata =  $connect->prepare("SELECT generalfee FROM coinsend_fee WHERE cointrackid=? AND status=? AND max >=? AND min <=?");
                                            $getdata->bind_param("siss",$productTid, $active,$amttopayusd,$amttopayusd);
                                            $getdata->execute();
                                            $dresult = $getdata->get_result();
                                            if ($dresult->num_rows > 0) {
                                            $ddetails = $dresult->fetch_assoc();
                                                    // extend api expire date
                                                    $fee=$ddetails['generalfee'];
                                            }
                                    
                           }
                                    
                                    
                                          
                                          
                                                if($sendcoindata['status']==1){
                                        array_push( $allResponse,array("name"=>$netname,"trackid"=>$ptid,"shortname"=>$shortname,"mainnetname"=>$priceapiname,"fee"=>$fee));
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
                                }    else{
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
                    }  else{
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
}else {
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