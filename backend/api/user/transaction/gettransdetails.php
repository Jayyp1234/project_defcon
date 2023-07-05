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
                    $userid = getUserWithPubKey($connect, $user_pubkey);
                    
                    
                    if (isset($_POST['pg'])) {
                         $pagem = cleanme($_POST['pg']);
                    } else {
                         $pagem = 1;
                    }
                    if (isset($_POST['perpage'])) {
                         $per_page = cleanme($_POST['perpage']);
                    } else {
                          $per_page = 15;
                    }
                    if (isset($_POST['transid'])) {
                            $transid = cleanme($_POST['transid']);
                    } else {
                         $transid = "";
                    }
                    
                    $pages=0;
                    $startm = ($pagem - 1) * $per_page;
                    $currecncytagis="";
                    $statussortis="";
                    $bindparam[]=$userid;
                    $bindparamcount="s";
                    
                     if (isset($_POST['transid'])&&$_POST['transid']!='') {
                        $statusClause = "AND userwallettrans.orderid = ?";
                        $bindparam[]="$transid";
                        $bindparamcount.="s";
                    } else {
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Transid is needeed";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }
                    
                    
                    // $statustype
                    
                            $sqlQuery = "SELECT * FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ? ".$statusClause." ";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->bind_param("$bindparamcount",...$bindparam);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                            $pages = ceil($numRow / $per_page);
                            
                            $bindparam[]=$startm;
                            $bindparam[]=$per_page;
                            $sqlQuery = "SELECT * FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ?  ".$statusClause."   ORDER BY userwallettrans.id DESC LIMIT ?,? ";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->bind_param("$bindparamcount"."ss",...$bindparam);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                     
                    
                    if($numRow > 0){
                        $allResponse = [];
                         while($users = $result->fetch_assoc()){
                                   $users['exchangeconfirmed']=0;
                                   $users['coinname']='';
                                   $users['swaptoname']='';
                                   $users['hashtop']='';
                                   $thusdval=0;
                                   if($users['systemsendwith']==3){
                                       $users['swaptoname']=getCurrencyDetails($users['swapto'])['name'];
                                   }
                                      $users['totalsenddeducted']=0;
                                    if($users['iscrypto']==1){
                                        $coinprodtrackidis=$users['cointrackid'];
                                        $coindata=getCoinDetails($coinprodtrackidis);
                                        $users['coinname']=$coindata['priceapiname'];
                                        $livecoinvalue=$coindata['livecoinvalue'];
                                        $users['hashtop']=$coindata['hashlink'];
                                        $livevale =  $coindata['liveratefunctions'];  
                                        $coinplatform = $coindata['coinplatform'];
                                        $cointype =$coindata['cointype'];
                                        $coindecimal=$coindata['roundto_dp'];
                                        $users['btcvalue']=number_format((float)$users['btcvalue'], $coindecimal, '.', ''); 
                                        $walletbal=$users['btcvalue'];
                                        $getlivevalu=0;  
                                        $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis); 
                                    
                                    
                                        if ($getlivevalu!=0) {
                                            // if($users['send_type']==2){
                                            //          $walletbal=number_format((float)($users['btcvalue']+$users['send_fee']), $coindecimal, '.', '');
                                            //          $thusdval=$walletbal*$getlivevalu;
                                            //         // $thusdval=floor($thusdval * 100) / 100;
                                            //         $thusdval= number_format((float)$thusdval, 2, '.', '');
                                            // }else{
                                                    $thusdval=$walletbal*$getlivevalu;
                                                    // $thusdval=floor($thusdval * 100) / 100;
                                                    $thusdval=number_format((float)$thusdval, 2, '.', '');
                                            // }
                                        }
                                        if($users['send_type']==2){
                                            $users['totalsenddeducted']= number_format((float)($users['btcvalue']+$users['send_fee']), $coindecimal, '.', '');
                                        }
                                        // if transatype is not exchnage chnage amount to pay
                                        if($users['transtype']!=4){
                                        $users['amttopay']=$thusdval;
                                        }
                                      $users['totalsenddeducted']=$users['totalsenddeducted']+0;  
                                      $users['btcvalue']=$users['btcvalue']+0;
                                      $users['send_fee']=$users['send_fee']+0;
                                    }
                                    $users['amttopay']=number_format((float)$users['amttopay'], 2, '.', '');//number_format(round($users['amttopay'],2));
                                
                                   
                    
                                    // checking if exchange transaction is confirmed on blockchain
                                    if($users['status']==2&&$users['transtype']==4 && $users['confirmation']>0){
                                        $users['exchangeconfirmed']=1;
                                    }else if($users['send_type']==2){
                                        $users['exchangeconfirmed']=1;
                                    }
                                    
                                    $users['peerstack_agentname']="";
                                    $users['peerstack_accountno']="";
                                    $users['peerstack_bankname']="";
                                    $agentcode=$users['peerstack_agent'];
                                    $getPeeruser = $connect->prepare("SELECT * FROM peerstackmerchants WHERE merchant_trackid= ?");
                                    $getPeeruser->bind_param("s",$agentcode);
                                    $getPeeruser->execute();
                                    $peerresult = $getPeeruser->get_result();
                                    //bank exist
                                    if( $peerresult->num_rows >0){
                                            $peerrow = $peerresult->fetch_assoc();
                                            $users['peerstack_agentname'] =$peerrow['fname'].' '.$peerrow['lname'];
                                            $users['peerstack_accountno'] =$peerrow['accountno'];
                                            $users['peerstack_bankname'] =$peerrow['bankname'];
                                    }
                                    
                                    $users['userbankname']="";
                                    $users['useraccno']="";
                                    if(strlen($users['bankaccsentto'])>2&& $users['bankaccsentto']!="" && ! empty($users['bankaccsentto'])){
                                        $seprated= explode("/",$users['bankaccsentto']);
                                        $users['userbankname']= $seprated[0];
                                        $users['useraccno']= $seprated[1];
                                    }
                                    
                                        $users['maintransname']="";
                                    // withdrawal
                                    if($users['transtype']==1||$users['transtype']==4||$users['transtype']==3){
                                         if($users['systemsendwith']==6){
                                              $users['maintransname']="Fund Virtual Card";   
                                        }else  if($users['systemsendwith']==5){
                                              $users['maintransname']="Withdraw with peerstack";   
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==0){
                                            $users['maintransname']="Internal Transfer Send(NGN)";   
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==1){
                                            $users['maintransname']="Internal Transfer Send(Crypto)";   
                                        }else  if($users['systemsendwith']==2){
                                            $users['maintransname']="Withdraw to a Bank account";   
                                        }else  if($users['systemsendwith']==3 && $users['iscrypto']==1&& $users['isexchange']==0){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Swap $cmae to NGN";   
                                        }else  if($users['systemsendwith']==3 && $users['iscrypto']==1&& $users['isexchange']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Exchanged $cmae to NGN";   
                                        }else  if($users['systemsendwith']==4 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="External $cmae pay out";   
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Unload to $cmae wallet";   
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Unload to NGN wallet";   
                                        }else if( $users['virtual_card_trans']==1 && $users['systemsendwith']==0){
                                             $users['maintransname']=$users['vc_transname'];
                                        }
                                        // deposit
                                    } else if($users['transtype']==2){
                                        if($users['systemsendwith']==6){
                                              $users['maintransname']="Fund Virtual Card";   
                                        }else  if($users['systempaidwith']==5){
                                              $users['maintransname']="Deposit with peerstack";   
                                        }else  if($users['systempaidwith']==4 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit Via Swap $cmae To NGN";   
                                        }else  if($users['systempaidwith']==6 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit From virtual card To NGN";   
                                        }
                                        else  if($users['systempaidwith']==2 || $users['systempaidwith']==3){
                                            $users['maintransname']="Deposit Via Bank transfer";   
                                        }else  if($users['systempaidwith']==1){
                                            $users['maintransname']="Deposit Via direct funding(PS)";   
                                        }else  if($users['systempaidwith']==4 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit $cmae";   
                                        }else  if($users['systempaidwith']==6 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit from virtual card to $cmae";   
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==0){
                                            $users['maintransname']="Internal Transfer Receive(NGN)";   
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==1){
                                            $users['maintransname']="Internal Transfer Receive(Crypto)";   
                                        }
                                    }
                                    
                                unset($users['main_sfee']); 
                                unset($users['cointrackid']);  unset($users['livecointype']);  unset($users['livetransid']);  unset($users['payapiresponse']);  unset($users['syslivewallet']);  unset($users['apiorderid']);  unset($users['apipayref']);
                                                                     
                            array_push($allResponse,json_decode(json_encode($users), true));
                        }
                        $maindata['userdata']= $allResponse;
                        $maindata['total'] = $numRow;
                        $maindata['currentpage'] = $pagem;
                        $maindata['totalpage'] = $pages;
                        
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