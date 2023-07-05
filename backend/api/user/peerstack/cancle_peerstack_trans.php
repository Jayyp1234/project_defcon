<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    Header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";
?>
<?php
$method = getenv('REQUEST_METHOD');
$endpoint = "/api/user/".basename($_SERVER['PHP_SELF']);
$maindata=[];
if (getenv('REQUEST_METHOD') == 'POST') {
    $fail="";
    $myloc=1;
    $sysgetdata =  $connect->prepare("SELECT * FROM apidatatable WHERE id=?");
    $sysgetdata->bind_param("s", $myloc);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    $getsys = $dsysresult7->fetch_assoc();
    $companyprivateKey=$getsys['privatekey'];
    $minutetoend=$getsys['tokenexpiremin'];
    $serverName=$getsys['servername'];
    $sysgetdata->close();

    $datasentin=ValidateAPITokenSentIN($serverName, $companyprivateKey, $method, $endpoint);
  
    $user_pubkey = $datasentin->usertoken;

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

    $user_id = getUserWithPubKey($connect, $user_pubkey);
    // check if the current password field was passed 
    if (isset($_POST['orderid'])) {//1 user,2 ADMIN
        $orderid = cleanme($_POST['orderid']);
    } else {
        $orderid = '';
    }

    $fail="";

    $query = 'SELECT * FROM users WHERE id = ?';
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_row = $result->num_rows;

    if ( $num_row < 1){
        $errordesc="User not found";
        $linktosolve="htps://";
        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="User is not in the database ensure the user is in the database";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }else{
            // give merchant back it money
             // check if status is 2
            $inwallet = 0;
            $proce=2;
            $ispeerit=5;
            $sqlQuery = "SELECT id,amttopay,peerstack_agent,orderid,created_at, isexchange,peerstack_fee,userid,currencytag,wallettrackid FROM userwallettrans  WHERE 	systempaidwith=? AND orderid=? AND (status=?||status=?)";
            $stmt = $connect->prepare($sqlQuery);
            $stmt->bind_param("ssss", $ispeerit,$orderid , $inwallet,$proce);
            $stmt->execute();
            $result = $stmt->get_result();
            $numRow = $result->num_rows;
            if ($numRow > 0) {
                $transdata = $result->fetch_assoc();
                // get trans details
                $amttopay = $transdata['amttopay'];
                $mainamttopay = $amttopay;
                
                
                if ($amttopay > 0 && is_numeric($amttopay)) {
                            $trackid =$transdata['id'];
                            $merchant_trackid=$peerstack_agent = $transdata['peerstack_agent'];
                            $isexchange = $transdata['isexchange'];
                            $peerstack_fee = $transdata['peerstack_fee'];
                            $userid = $transdata['userid'];
                            $currencytag = $transdata['currencytag'];
                            $wallettrackid = $transdata['wallettrackid'];
                            $reference = $transdata['orderid'];
                            $created_at = $transdata['created_at'];
                            $strtime = strtotime($created_at); //add 1 hour
                            // $strtime= strtotime($created_at) + (60*60*1);//add 1 hour
                        
                            $sqlQuery ="SELECT * FROM `systemsettings`";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                            $row = $result->fetch_assoc();
                            $peer_deposit_bonus = $row['peer_deposit_bonus'];
                            $peer_withdrawal_bonus = $row['peer_withdrawal_bonus'];
                
           
                       
                            // get admin level, check if its super admin
                            // get admin id merchant trac id and compare
                
                            $sqlQuery = "SELECT merchant_trackid FROM peerstackmerchants  WHERE merchant_trackid=? AND active_escrow_balance>=?";
                            $stmt = $connect->prepare($sqlQuery);
                            $stmt->bind_param("sd", $merchant_trackid,$amttopay);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $peercount = $result->num_rows;
                            if ($peercount > 0) {
                                        $datediff = time() - $strtime;
                                        $difference = round($datediff / 60); //getting minute btween
                                        $minleft = 30 - $difference;
                                        // echo $difference;
                                        // exit;
                
                                            // set details to sucess
                                            $done = 3;
                                            $sql = "UPDATE userwallettrans SET status=? WHERE id=? ";
                                            $stmt = $connect->prepare($sql);
                                            $stmt->bind_param('ss', $done,  $trackid);
                                            if ($stmt->execute()) {
                
                                                $sql = "UPDATE  peerstackmerchants SET active_escrow_balance=active_escrow_balance-?,active_balance=active_balance+? WHERE merchant_trackid=? ";
                                                $stmt = $connect->prepare($sql);
                                                $stmt->bind_param('sss',  $mainamttopay,$mainamttopay, $peerstack_agent);
                                                $stmt->execute();
                
                                                // save histoty of fund given
                                                $statusis=3;
                                                $sqlQuery = "INSERT INTO `peerstack_task_history` (`merchant_tid`, `amount_paid`, `trans_id`,`amount_paid_user`,`status`) VALUES (?,?,?,?,?)";
                                                $stmt = $connect->prepare($sqlQuery);
                                                $stmt->bind_param("sssss", $peerstack_agent,  $peer_deposit_bonus, $trackid,$mainamttopay,$statusis);
                                                $insertCoinProduct = $stmt->execute();
                
                                                
                                               
                                            }
                            }
                    
                }
    }
    
            $seen=1;
            $cancle=3;
            $checkdata =  $connect->prepare("UPDATE userwallettrans SET status=?  WHERE orderid=? AND status!=?");
            $checkdata->bind_param("sss", $cancle,$orderid , $seen);
            if ($checkdata->execute()) {
                 $errordesc = " ";
                                                        $linktosolve = "htps://";
                                                        $hint = [];
                                                        $errordata = [];
                                                        $text = "Transaction Canceled successful";
                                                        $method = getenv('REQUEST_METHOD');
                                                        $status = true;
                                                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                        respondOK($data);
                
            } else {
                $errordesc = " ";
                $linktosolve = "htps://";
                $hint = [];
                $errordata = [];
                $text = "Transaction not yet confirmed";
                $method = getenv('REQUEST_METHOD');
                $status = false;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
    }
} else {
    $errordesc = "Method not allowed";
    $linktosolve = "htps://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>