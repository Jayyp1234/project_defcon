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
        $user_pubkey = $decodedToken->usertoken;
             // send error if ur is not in the database
            if (!getUserWithPubKey($connect, $user_pubkey)){
                $errordesc="Bad request";
                $linktosolve="https://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User is not in the database ensure the user is in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
                
            }
            else{
                $userid = getUserWithPubKey($connect, $user_pubkey);
            }
            
    
            $sqlQuery = "SELECT * FROM `userwallet` INNER JOIN `currencysystem` ON `userwallet`.`currencytag`= `currencysystem`.`currencytag` WHERE currency_status = 1 AND userid = '$userid' ORDER BY currencysystem.id ASC";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){
                $allResponse = [];
                while($users = $result->fetch_assoc()){
                    $totalbalanceis=0;
                        $subbalanceis=0;
                        $subpendbalanceis=0;
                        $dcurrencytag=$users['currencytag'];
                        $sqlQuery = "SELECT walletbal,coinsystrackid,walletpendbal,coinsystemtag FROM usersubwallet WHERE userid=? AND currencytag = ? ";
                        $stmt2= $connect->prepare($sqlQuery);
                        $stmt2->bind_param("ss",$userid,$dcurrencytag);
                        $stmt2->execute();
                        $result2= $stmt2->get_result();
                        $numRow2 = $result2->num_rows;
                        if($numRow2 > 0){
                     
                              
                                while($users2 = $result2->fetch_assoc()){
                                    $thusdval=0;
                                    $thpendusdval=0;
                                    $walletbal =$users2['walletbal'];
                                    $walletpendbal=$users2['walletpendbal'];
                                    $coinprodtrackidis=$users2['coinsystrackid'];
                                    //   get all addresses
                                     // get coin detail $producttrackid
                                      $subtag=$users2['coinsystemtag'];
                                    $coindata=getCoinDetailsWithSubTag($subtag);
                                    if(isset($coindata['producttrackid'])){
                                        $coinprodtrackidis=	$coindata['producttrackid'];
                                        $coinname=$coindata['name'];
                                        $coinrate=$coindata['rate'];
                                        $livevale =  $coindata['liveratefunctions'];  
                                        $coinplatform = $coindata['coinplatform'];
                                        $cointype =$coindata['cointype'];
                                        $livecoinvalue=$coindata['livecoinvalue'];
                                                                        
                                        $getlivevalu=0;   
                                        if($walletbal>0||$walletpendbal>0){
                                            $getlivevalu=getMeCoinLiveUSdValue($coinprodtrackidis); 
                                            if ($getlivevalu!=0) {
                                                $thusdval=$walletbal*$getlivevalu;
                                                $thusdval=floor($thusdval * 100) / 100;
                                                
                                                $thpendusdval=$walletpendbal*$getlivevalu;
                                                $thpendusdval=floor($thpendusdval * 100) / 100;
                                            }
                                        }
                                        
                                        
                                        $subbalanceis=$subbalanceis+$thusdval;
                                        $subpendbalanceis=$subpendbalanceis+$thpendusdval;
                                    }
                                }
                        }
                    
                            $totalbalanceis=$users['walletbal']+$subbalanceis;
                            $totalpendbal=$users['walletpendbal']+$subpendbalanceis;
                            $totalbalanceis=round($totalbalanceis,2);
                            $totalpendbal =round( $totalpendbal,2);
                            
                                array_push($allResponse, array("totalbal"=>$totalbalanceis,"totalpendbal"=>$totalpendbal,"id"=>$users['id'], "userid"=>$users['userid'], "currencytag"=>$users['userid'], "wallettrackid"=>$users['wallettrackid'], "walletbal"=>$users['walletbal'], "walletpendbal"=>$users['walletpendbal'], "walletescrowbal"=>$users['walletescrowbal'],
                                "name"=>$users['name'], "sign"=>$users['sign'], "currency_status"=>$users['currency_status'], "currencytag"=>$users['currencytag'], "imglink"=>$users['imglink'], "activatesend"=>$users['activatesend'], "activatereceive"=>$users['activatereceive'], "maxsendamtauto"=>$users['maxsendamtauto']));//json_decode(json_encode($users), true);
                            
                            
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
                $text = "Data found";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
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