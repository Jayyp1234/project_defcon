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
                    if (isset($_POST['search'])) {
                            $search = cleanme($_POST['search']);
                    } else {
                         $search = "";
                    }
                    if (isset($_POST['statussort'])) {
                            $statussort = cleanme($_POST['statussort']);
                    } else {
                         $statussort = "";
                    }
                    if (isset($_POST['statustype'])) {
                            $statustype = cleanme($_POST['statustype']);
                    } else {
                         $statustype = "";
                    }
                    if (isset($_POST['sortpeerstack'])) {
                         $sortpeerstack= cleanme($_POST['sortpeerstack']);
                    } else {
                         $sortpeerstack = "";
                    }
                    if (isset($_POST['sorttrackid'])) {
                         $sorttrackid= cleanme($_POST['sorttrackid']);
                    } else {
                         $sorttrackid = "";
                    }
                    if (isset($_POST['limittext'])) {
                         $limittext= 1;
                    } else {
                         $limittext = 0;
                    }
              
                    
                    
                    
                    $pages=0;
                    $startm = ($pagem - 1) * $per_page;
                    $currecncytagis="";
                    $statussortis="";
                    $bindparam[]=$userid;
                    $bindparamcount="s";
                    $currencyClause = "";  
                    if (isset ($_POST['currency']) &&!empty(trim($_POST['currency']))) {  
                        $currecncytagis=cleanme($_POST['currency']);
                        $currencyClause .= " AND userwallettrans.currencytag = ? ";
                        $bindparam[]="$currecncytagis";
                        $bindparamcount.="s";
                    }
                    
                    if (isset ($_POST['billtrans'])&&$_POST['billtrans']!='' ) {  
                        $currencyClause .= " AND userwallettrans.bills_trans = ? ";
                        $bindparam[]=1;
                        $bindparamcount.="s";
                    }
                    if (isset ($_POST['bill_top_up_type'])&&$_POST['bill_top_up_type']!='' ) {  
                        $topuptype=cleanme($_POST['bill_top_up_type']);
                        $currencyClause .= " AND userwallettrans.billtypeis = ? ";
                        $bindparam[]=$topuptype;
                        $bindparamcount.="s";
                    }
                    
                     if (isset($_POST['statussort'])&&$_POST['statussort']!='') {
                        $currencyClause .= " AND userwallettrans.status = ? ";
                        $bindparam[]="$statussort";
                        $bindparamcount.="s";
                    }
                    
                    if (isset($_POST['statustype'])&&$_POST['statustype']!='') {
                        $currencyClause .=" AND userwallettrans.transtype = ? ";
                        $bindparam[]="$statustype";
                        $bindparamcount.="s";
                    } 
                    
                    if (isset($_POST['sortpeerstack'])&&$_POST['sortpeerstack']!=0&&!empty(trim($_POST['sortpeerstack']))) {
                        $currencyClause .= "AND userwallettrans.systempaidwith = ?";
                        $bindparam[]="$sortpeerstack";
                        $bindparamcount.="s";
                    } 
                    
                    if (isset($_POST['sorttrackid'])&&$_POST['sorttrackid']!=0&&!empty(trim($_POST['sorttrackid']))) {
                        $currencyClause .= "AND userwallettrans.wallettrackid = ?";
                        $bindparam[]="$sorttrackid";
                        $bindparamcount.="s";
                    } 
                    
                    
                    
                    // $statustype
                    
                     if (!empty($search) && $search!=" ") {
                                $s = "%{$search}%";
                                
                                for($i=1;$i<=8;$i++){
                                    $bindparam[]=$s;
                                }
                                $sqlQuery = "SELECT *,userwallettrans.id as id FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ? ".$currencyClause."  AND (userwallettrans.addresssentto LIKE ? || userwallettrans.transhash  LIKE ? || userwallettrans.orderid  LIKE ? || userwallettrans.theusdval  LIKE ? ||  userwallettrans.ourrrate  LIKE ? || userwallettrans.amttopay  LIKE ? || userwallettrans.currencytag  LIKE ? ||  userwallettrans.paymentref  LIKE ?) ORDER BY userwallettrans.id DESC";
                                $stmt= $connect->prepare($sqlQuery);
                                $stmt->bind_param("$bindparamcount"."ssssssss",...$bindparam);
                                $stmt->execute();
                                $result= $stmt->get_result();
                                $allnum = $result->num_rows;
                                $num = $allnum;
                                $pages = ceil($num / $per_page);
                                
                                $bindparam[]=$startm;
                                $bindparam[]=$per_page;
                                $sqlQuery = "SELECT *,userwallettrans.id as id FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ? ".$currencyClause."   AND (userwallettrans.addresssentto LIKE ? || userwallettrans.transhash  LIKE ? || userwallettrans.orderid  LIKE ? || userwallettrans.theusdval  LIKE ? ||  userwallettrans.ourrrate  LIKE ? || userwallettrans.amttopay  LIKE ? || userwallettrans.currencytag  LIKE ? ||  userwallettrans.paymentref  LIKE ?) ORDER BY userwallettrans.id DESC LIMIT ?,? ";
                                $stmt= $connect->prepare($sqlQuery);
                                $stmt->bind_param("$bindparamcount"."ssssssssss",...$bindparam);
                                $stmt->execute();
                                $result= $stmt->get_result();
                                $numRow = $result->num_rows;
                                
                     }else{
                            $sqlQuery = "SELECT *,userwallettrans.id as id FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ? ".$currencyClause."   ";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->bind_param("$bindparamcount",...$bindparam);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                            $pages = ceil($numRow / $per_page);
                            
                            $bindparam[]=$startm;
                            $bindparam[]=$per_page;
                            $sqlQuery = "SELECT *,userwallettrans.id as id FROM userwallettrans  INNER JOIN `currencysystem` ON `userwallettrans`.`currencytag`= `currencysystem`.`currencytag` WHERE userwallettrans.userid = ? ".$currencyClause."   ORDER BY userwallettrans.id DESC LIMIT ?,? ";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->bind_param("$bindparamcount"."ss",...$bindparam);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                     }
                    
                    if($numRow > 0){
                        $allResponse = [];
                        while($users = $result->fetch_assoc()){
                                   $users['exchangeconfirmed']=0;
                                   $users['coinname']='NGN';
                                   $users['swaptoname']='';
                                   $users['hashtop']='';
                                   $thusdval=0;
                                   $swaptext="";
                                   if($users['systemsendwith']==3||$users['systemsendwith']==4){
                                        if(isset(getCurrencyDetails($users['swapto'])['name'])){
                                            $users['swaptoname']=getCurrencyDetails($users['swapto'])['name'];
                                        }else{
                                            $swaptext=$users['swaptoname']=$users['swapto'];
                                        }
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
                                         if($users['swaptonametxt']=="NGN" && $users['transtype']==3){
                                            $users['btcvalue']=$users['amttopay'];
                                        }
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
                                    }else{
                                        $users['btcvalue']=number_format((float)$users['btcvalue']); 
                                    }
                                    $users['amttopay']=number_format((float)$users['amttopay'], 2, '.', '');//number_format(round($users['amttopay'],2));
                                
                                   
                    
                                    // checking if exchange transaction is confirmed on blockchain
                                    if($users['status']==2&&$users['transtype']==4 && $users['confirmation']>0){
                                        $users['exchangeconfirmed']=1;
                                    }else if($users['send_type']==2){
                                        $users['exchangeconfirmed']=1;
                                    }else if( $users['virtual_card_trans']==1){
                                        $users['exchangeconfirmed']=1;
                                    }
                                    // to make change status text
                                    if($users['status']==2 && $users['virtual_card_trans']==1){
                                        $users['status']=0;
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
                                        if(isset($seprated[1])){
                                        $users['useraccno']= $seprated[1];
                                        }
                                    }
                                    
                                        $users['maintransname']="";
                                        $users['basemaintransname']="";
                                        $currencyname=$users['name'];
                                        $users['basemaintransname']=$currencyname;
                                        
                                        $cardlast4="****";
                                        if($users['virtual_card_trans']==1){
                                          $cardtidis=  $users['wallettrackid'];
                                          $userid=$users['userid'];
                                        $gttransdata=mailgetVirtualCardData($userid,$cardtidis);
                                        //`balance`, `vc_type_tid`, `json_response`, `brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, 
                                        //  if you need to pick any data of the user , check above for the data field name and call it as seen below, more data would be added as the system grows
                                        $cardbrand = $gttransdata['brand'];
                                         $cardlast4= $gttransdata['last4'];
                                        $expireMonth = $gttransdata['expireMonth'];
                                        $expireyear = $gttransdata['expireyear'];
                                            
                                        }
                                    $vctext="Virtual Card";
                                     if($limittext==1){
                                           $vctext="VC";
                                     }
                                       $users['bill_image']='';
                                       $users['bill_transname']='';
                                       $users['bill_paymethod']='NGN wallet';
                                       $users['bill_type']='';
                                       $users['bill_amt']=''; 
                                    // withdrawal
                                    if($users['transtype']==1||$users['transtype']==4||$users['transtype']==3){
                                         if($users['systemsendwith']==6 && $users['vc_creationfee']==0.00){
                                              $users['maintransname']="Fund $vctext";  
                                              $users['basemaintransname']="Spent $currencyname";
                                        }else if($users['systemsendwith']==6 && $users['vc_creationfee']!=0.00){
                                              $users['maintransname']="Create $vctext";   
                                              $users['basemaintransname']="Spent $currencyname";
                                        }else  if($users['systemsendwith']==5){
                                              $users['maintransname']="Withdraw with peerstack";   
                                              $users['basemaintransname']="Sent $currencyname";
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==0){
                                            $users['maintransname']="Internal Transfer Send(NGN)";   
                                            $users['basemaintransname']="Sent $currencyname";
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==1){
                                            $users['maintransname']="Internal Transfer Send(Crypto)";   
                                            $users['basemaintransname']="Sent $currencyname";
                                        }else  if($users['systemsendwith']==2){
                                            $users['maintransname']="Withdraw to a bank account";  
                                            $users['basemaintransname']="Sent $currencyname";
                                        }else  if($users['systemsendwith']==9){
                                            $users['maintransname']="Chargeback";  
                                            $users['basemaintransname']="$currencyname Chargeback";
                                        }else  if(($users['systemsendwith']==3 && $users['iscrypto']==1&& $users['isexchange']==0)||($users['systemsendwith']==4 && strlen($swaptext)>3)){
                                            $cmae=$users['coinname'];
                                            if(empty($swaptext)){
                                                $users['maintransname']="Swap $cmae to NGN";   
                                                $users['basemaintransname']="Swap $cmae to NGN";
                                            }else{
                                                $users['maintransname']="Swap $swaptext";   
                                                $users['basemaintransname']="Swap $swaptext";
                                            }
                                        }else  if($users['systemsendwith']==3 && $users['iscrypto']==0&& $users['isexchange']==0){
                                            $users['coinname']=$users['swaptonametxt'];
                                                $users['maintransname']="Swap $swaptext";   
                                                $users['basemaintransname']="Swap $swaptext";
                                        }else  if($users['systemsendwith']==3 && $users['iscrypto']==1&& $users['isexchange']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Exchanged $cmae to NGN"; 
                                             $users['basemaintransname']="Exchanged $cmae";
                                        }else  if($users['systemsendwith']==4 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="External $cmae pay out";   
                                            $users['basemaintransname']="Sent $cmae";
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            if($users['deleted_card']==1){
                                                $users['maintransname']="Unload to $cmae wallet"; 
                                                $users['basemaintransname']="Delete $vctext";
                                            }else  if($users['deleted_card']==2){
                                                $users['maintransname']="Refunded from $vctext to $cmae wallet"; 
                                                $users['basemaintransname']="Refund and deactivated  ";
                                            }else{
                                                $users['maintransname']="Unload to $cmae wallet"; 
                                                $users['basemaintransname']="Unload $vctext";
                                            }
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                              if($users['deleted_card']==1){
                                                    $users['maintransname']="Unload to NGN wallet";   
                                                    $users['basemaintransname']="Delete $vctext [$cardlast4]";
                                              }else  if($users['deleted_card']==2){
                                                $users['maintransname']="Refunded from $vctext to NGN wallet"; 
                                                $users['basemaintransname']="Refund and deactivated [$cardlast4]";
                                             }else{
                                                    $users['maintransname']="Unload to NGN wallet";   
                                                    $users['basemaintransname']="Unload $vctext [$cardlast4]";
                                              }
                                        }else if( $users['virtual_card_trans']==1 && $users['systemsendwith']==0){
                                             $users['maintransname']=$users['vc_transname'];
                                             if(strlen($users['maintransname'])>20){
                                                 $users['basemaintransname']=substr($users['vc_transname'],0,20)."...";
                                             }else{
                                                $users['basemaintransname']=$users['vc_transname'];
                                             }
                                        }else if($users['systemsendwith']==8){
                                            // BILLS TOP UP
                                            $prodname="";
                                            $prodtypetext="";
                                            $prodimage="";
                                            $bill_main_prodtid=$users['bill_main_prodtid'];
                                            // get bills prod
                                            $getBillsProd = $connect->prepare("SELECT shortname,type,image FROM bill_top_up_main_products WHERE product_trackid= ?");
                                            $getBillsProd->bind_param("s",$bill_main_prodtid);
                                            $getBillsProd->execute();
                                            $billprodresult =$getBillsProd->get_result();
                                            //bank exist
                                            if($billprodresult->num_rows >0){
                                                $billrow = $billprodresult->fetch_assoc();
                                                $prodname=$billrow['shortname'];
                                                $thetypeis=$billrow['type'];
                                                $prodimage=$billrow['image'];
                                                if($thetypeis==1){
                                                     $prodtypetext="Data";
                                                }else if($thetypeis==2){
                                                     $prodtypetext="Airtime";
                                                }
                                            }
                                            $users['bill_type']= $prodtypetext;
                                            $users['maintransname']="Bought $prodname $prodtypetext on ".$users['bill_product_no'];
                                            $users['basemaintransname']="Spent NGN";
                                            $users['bill_image']=$prodimage;
                                            $users['bill_transname']="$prodname $prodtypetext ".$users['bill_product_no'];
                                            $users['bill_amt']="₦".remove_pointzero($users['amttopay']);
                                            $users['bill_cashback']="₦".$users['bill_cashback'];
                                            
                                            
                                        }else if($users['systemsendwith']==10){
                                            // BILLS TOP UP
                                            $prodname="";
                                            $prodtypetext="";
                                            $prodimage="";
                                            $bill_main_prodtid=$users['bill_main_prodtid'];
                                            // get bills prod
                                            $getBillsProd = $connect->prepare("SELECT name,imglink FROM bill_voucher_main_prod WHERE voucher_tid= ?");
                                            $getBillsProd->bind_param("s",$bill_main_prodtid);
                                            $getBillsProd->execute();
                                            $billprodresult =$getBillsProd->get_result();
                                            //bank exist
                                            if($billprodresult->num_rows >0){
                                                $billrow = $billprodresult->fetch_assoc();
                                                $prodtypetext=$prodname=$billrow['name'];
                                                $prodimage=$billrow['imglink'];
                                            }
                                            $users['bill_type']= $prodtypetext." Voucher";
                                            $users['maintransname']="Bought $prodname on ".$users['bill_product_no'];
                                            $users['basemaintransname']="Spent NGN";
                                            $users['bill_image']=$prodimage;
                                            $users['bill_transname']="$prodname $prodtypetext ".$users['bill_product_no'];
                                            $users['bill_amt']="₦".remove_pointzero($users['amttopay']);
                                            $users['bill_cashback']="₦".$users['bill_cashback'];
                                            
                                            
                                        }
                                        // deposit
                                    } else if($users['transtype']==2){
                                        if($users['systemsendwith']==6 && $users['vc_creationfee']==0.00){
                                              $users['maintransname']="Fund $vctext [$cardlast4]";  
                                              $users['basemaintransname']="Fund $vctext [$cardlast4]";
                                        }else if($users['systemsendwith']==6 && $users['vc_creationfee']!=0.00){
                                              $users['maintransname']="Create $vctext [$cardlast4]"; 
                                              $users['basemaintransname']="Create $vctext [$cardlast4]";
                                        }else  if($users['systempaidwith']==5){
                                              $users['maintransname']="Deposit with peerstack";   
                                               $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==4||$users['systemsendwith']==4){
                                           
                                           if(strlen($users['addresssentto'])>4){
                                                    if($users['iscrypto']!=1){
                                                        $users['maintransname']="Received $currencyname";
                                                    }else{
                                                        $cmae=$users['coinname'];
                                                          $users['maintransname']="Received $cmae";
                                                    }
                                           }  else{       
                                                if(empty($swaptext)){ 
                                                     $cmae=$users['coinname'];
                                                    $users['maintransname']="Deposit via swap $cmae to NGN";  
                                                }else{
                                                    $users['maintransname']="Deposit via swap $swaptext";  
                                                }
                                           }

                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systemsendwith']==3){
                                         
                                            if(strlen($users['addresssentto'])>4){
                                                    if($users['iscrypto']!=1){
                                                  $users['maintransname']="Received $currencyname";
                                                    }else{
                                                        $cmae=$users['coinname'];
                                                          $users['maintransname']="Received $cmae";
                                                    }
                                           }  else{ 
                                                if(empty($swaptext)){
                                                    $cmae=$users['coinname'];
                                                    $users['maintransname']="Deposit via swap $cmae to NGN";  
                                                }else{
                                                    $users['maintransname']="Deposit via swap $swaptext";  
                                                }
                                           }
                                               $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==6 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit from $vctext to NGN";   
                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==7){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Refund to $vctext [$cardlast4]";  
                                                if(strlen($users['maintransname'])>20){
                                                 $users['basemaintransname']=substr($users['vc_transname'],0,20)."...";
                                             }else{
                                                $users['basemaintransname']=$users['vc_transname'];
                                             }
                                        }
                                        else  if($users['systempaidwith']==2 || $users['systempaidwith']==3){
                                            $users['maintransname']="Deposit via bank transfer";   
                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==1){
                                            $users['maintransname']="Deposit via direct funding(PS)"; 
                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==9){
                                            $users['maintransname']="Cashback bonus"; 
                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systempaidwith']==4 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit $cmae";   
                                            $users['basemaintransname']="Received $cmae";
                                        }else  if($users['systempaidwith']==6 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Deposit from $vctext to $cmae"; 
                                            $users['basemaintransname']="Received $cmae";
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==0){
                                            $users['maintransname']="Internal transfer receive(NGN)";  
                                            $users['basemaintransname']="Received $currencyname";
                                        }else  if($users['systemsendwith']==1 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            $users['maintransname']="Internal transfer receive(Crypto)";  
                                            $users['basemaintransname']="Received $cmae";
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==1){
                                            $cmae=$users['coinname'];
                                            if($users['deleted_card']==1){
                                                $users['maintransname']="Unload from virtual card"; 
                                                $users['basemaintransname']="Delete $vctext";
                                            }else  if($users['deleted_card']==2){
                                                $users['maintransname']="Refunded from $vctext to $cmae wallet"; 
                                                $users['basemaintransname']="Refund and Deactivated  ";
                                            }else{
                                                $users['maintransname']="Unload from virtual card"; 
                                                $users['basemaintransname']="Unload $vctext";
                                            }
                                        }else  if($users['systemsendwith']==7 && $users['iscrypto']==0){
                                            $cmae=$users['coinname'];
                                              if($users['deleted_card']==1){
                                                    $users['maintransname']="Unload from virtual card";   
                                                    $users['basemaintransname']="Delete $vctext";
                                              }else  if($users['deleted_card']==2){
                                                $users['maintransname']="Refunded from $vctext to NGN wallet"; 
                                                $users['basemaintransname']="Refund and deactivated";
                                             }else{
                                                    $users['maintransname']="Unload from virtual card";   
                                                    $users['basemaintransname']="Unload $vctext";
                                              }
                                        }
                                    }

                                // ALL DATA NEEDED TO SHOW TO UI IS WRITTEN DOWN HERE
                                $statusoftransis=strval($users['status']);
                                $transiscrypto=$users['iscrypto'];
                                $transexchangeconfirm=$users['exchangeconfirmed'];
                                $transmaintypeis=$users['transtype'];
                                
                                $transcoinmain_value=$users['btcvalue'];
                                $transmainamt_value=$users['amttopay'];
                                // for swap
                                if($users['swaptonametxt']=="NGN"&&$transmaintypeis!=3){
                                    $users['amttopay']=$transmainamt_value= $users['btcvalue'];
                                }
                            
                                
                                $transmainamt_valueusd=$users['theusdval'];
                                $transcoinmain_name=$users['coinname'];
                                $transmain_name=$users['name'];
                                $istrans_virtual_card_trans=$users['virtual_card_trans'];
                                $trans_systemsendwith=$users['systemsendwith'];
                                // REMOVE ALL USLESS DATA NOT NEEDED IN UI
                                
                                unset($users['main_sfee']); 
                                   unset($users['bill_main_prodtid']);
                                      unset($users['bill_data_prodtid']);
                                         unset($users['bill_product_no']);
                                unset($users['cointrackid']);  unset($users['livecointype']);  unset($users['livetransid']);  unset($users['payapiresponse']);  unset($users['syslivewallet']);  unset($users['apiorderid']);  unset($users['apipayref']);
                       
                                $statustextis="";
                                $transmainamt="";
                                $transcryptomainamt="";
                                //<!-- status 0- pending, 1- successful, 2- in wallet, 3- Cancled , 4- Scam flagged-->
                                if($statusoftransis==0){
                                    $statustextis="Pending";
                                }else if($statusoftransis=="1"){
                                    if($transiscrypto==1){
                                        $statustextis="Confirmed";
                                    }else{
                                        $statustextis="Successful";
                                    }
                                }else if($statusoftransis==2){
                                    if($transiscrypto==1&&$transexchangeconfirm==0){
                                        $statustextis="Incoming";
                                    }else{
                                        $statustextis="Processing";
                                    }
                                }else if($statusoftransis==3){
                                    $statustextis="Canceled";
                                }else if($statusoftransis==4){
                                    $statustextis="Funds Locked";
                                }else if($statusoftransis==5){
                                    $statustextis="Awaiting Approval";
                                }else if($statusoftransis==6){
                                    $statustextis="Reversed";
                                }

                    
                                if($transmaintypeis==2||$transmaintypeis==4){
                                    if($istrans_virtual_card_trans==1){
                                        if($trans_systemsendwith==6){
                                            $transcryptomainamt="+ $transcoinmain_value $transcoinmain_name";
                                            if($transmain_name=="NGN"){
                                                  $transmainamt="+ $transmainamt_valueusd USD";
                                            }else{
                                            $transmainamt="+ $transmainamt_value $transmain_name";
                                            }
                                        }else{
                                            $transmainamt="+ $transmainamt_valueusd USD";
                                        }
                                    }else{
                                        
                                        $transcryptomainamt="+ $transcoinmain_value $transcoinmain_name";
                                       
                                        $transmainamt="+ $transmainamt_value $transmain_name";
                                        
                                        
                                    }
                                }else{
                                    if($istrans_virtual_card_trans==1){
                                        if($trans_systemsendwith==6){
                                            $transcryptomainamt="- $transcoinmain_value $transcoinmain_name";
                                        }else{
                                            $transmainamt="- $transmainamt_valueusd USD";
                                        }
                                    }else{
                                        $transcryptomainamt="- $transcoinmain_value $transcoinmain_name";
                                        $transmainamt="- $transmainamt_value $transmain_name";
                                    }
                                }

                                $users['overview_transname']=$users['basemaintransname'];    
                                $users['overview_transstatus']=$statustextis;    
                                $users['overview_transstatus_no']=$statusoftransis;    
                                $users['overview_transorder_time']=$users['ordertime'];    
                                $users['overview_trans_amt']=$transmainamt;    
                                $users['overview_trans_crypto_amt']=$transcryptomainamt;    
                                $users['overview_trans_img']=$users['imglink'];  
                                $users['status']=strval($users['status']);
                                if($users['confirmation']==null){
                                    $users['confirmation']="";
                                }
                           

                                // overview transname
                                // over view status text
                                // overview status
                                //  order time
                                // over view amount to show
                                                                     
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