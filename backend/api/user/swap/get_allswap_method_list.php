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
if ($method == 'POST') {
        $cointrackid = isset($_POST['cointrackid']) ? cleanme($_POST['cointrackid']) : '';

               
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
            
        }  else{
            $allResponse=[];
                $userid = getUserWithPubKey($connect, $user_pubkey);
                
                            $bindParams=array();
            $active=0;
            $on1=1;
            $bindParamTags="s";
            $bindParams[]=$on1;
            $searchclause="";
            $typeClause="";
            if(isset($_POST['search'])&&$_POST['search']!=''){
              $search= cleanme($_POST['search']);
              $search="%{$search}%";
              $searchclause=" AND (coin_to_name LIKE ? || coin_to_trackid LIKE ?)";
              for($i=1;$i<=2;$i++){
                 $bindParamTags.="s";
                 $bindParams[]=$search;
              }
            }
            
            
                        //   check if user has the sub wallet
                        $active=1;
                        $checksubwall =  $connect->prepare("SELECT * FROM swap_system_settings WHERE status=? $searchclause");
                        $checksubwall->bind_param("$bindParamTags",...$bindParams);
                        $checksubwall->execute();
                        $dsubwallresult= $checksubwall->get_result();
                        if ($dsubwallresult->num_rows >0) {
                            while($coinsdata =$dsubwallresult->fetch_assoc()){
                                $wallettrackid="";
                                $from_is_crypto=$coinsdata['from_is_crypto'];
                                $currency_from_tag=$coinsdata['currency_from_tag'];
                                $coin_frm_trackid=$coinsdata['coin_frm_trackid'];
                                
                                $to_is_crypto=$coinsdata['to_is_crypto'];
                                $currency_to_tag=$coinsdata['currency_to_tag'];
                                $coin_to_trackid=$coinsdata['coin_to_trackid'];
                                
                                
                                	    $fromwalletbalancis="0";
                                	    $towalletbalancis="0";
                                	    
                                       if($from_is_crypto==1){
                                            $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal,coinsystrackid,trackid,coinsystemtag FROM usersubwallet WHERE currencytag=? AND coinsystemtag=? AND userid=?");
                                            $sysgetdata->bind_param("sss", $currency_from_tag,$coin_frm_trackid,$userid);
                                            $sysgetdata->execute();
                                            $dsysresult7 = $sysgetdata->get_result();
                                            $getsys2 = $dsysresult7->num_rows;
                                            if($getsys2 > 0){
                                                $getuserdata= $dsysresult7->fetch_assoc();
                                                $wallettrackid=$getuserdata['trackid']; 
                                                 $fromwalletbalancis=round($getuserdata['walletbal'],2);
                                            }
                                       }else{
                                                            $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=?";
                                                            $stmt= $connect->prepare($sqlQuery);
                                                            $stmt->bind_param("ss",$userid, $currency_from_tag);
                                                            $stmt->execute();
                                                            $result= $stmt->get_result();
                                                            $getsys2 = $result->num_rows;
                                                            if($getsys2 > 0){
                                                                $users = $result->fetch_assoc();
                                                                $wallettrackid=$users['wallettrackid'];
                                                                $fromwalletbalancis=round($users['walletbal'],2);
                                                            }
                                        }
                                        
                                           if($to_is_crypto==1){
                                            $sysgetdata =  $connect->prepare("SELECT currencytag,walletbal,coinsystrackid,trackid,coinsystemtag FROM usersubwallet WHERE currencytag=? AND coinsystemtag=? AND userid=?");
                                            $sysgetdata->bind_param("sss", $currency_to_tag,$coin_to_trackid,$userid);
                                            $sysgetdata->execute();
                                            $dsysresult7 = $sysgetdata->get_result();
                                            $getsys2 = $dsysresult7->num_rows;
                                            if($getsys2 > 0){
                                                $getuserdata= $dsysresult7->fetch_assoc();
                                                 $towalletbalancis=round($getuserdata['walletbal'],2);
                                            }
                                       }else{
                                                            $sqlQuery = "SELECT wallettrackid,walletbal FROM userwallet WHERE userid=? AND currencytag=?";
                                                            $stmt= $connect->prepare($sqlQuery);
                                                            $stmt->bind_param("ss",$userid, $currency_to_tag);
                                                            $stmt->execute();
                                                            $result= $stmt->get_result();
                                                            $getsys2 = $result->num_rows;
                                                            if($getsys2 > 0){
                                                                $users = $result->fetch_assoc();
                                                                $towalletbalancis=round($users['walletbal'],2);
                                                            }
                                        }
                                                        
                                $coinsdata['wallettrackid']=$wallettrackid;
                                $coinsdata['fromwalletbalance']="$fromwalletbalancis";
                                $coinsdata['towalletbalance']="$towalletbalancis";
                              array_push($allResponse,json_decode(json_encode($coinsdata), true));
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
                        }else{
                            $errordesc="Bad request";
                            $linktosolve="https://";
                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="System to convert to is not available";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
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