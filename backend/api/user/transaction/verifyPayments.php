<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    // API for verufying if transaction from 1app or paystack is successu

    
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if ($method  == 'POST') {
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
            $getUser = $connect->prepare("SELECT * FROM users WHERE id = ?");
            $getUser->bind_param("s",$userid);
            $getUser->execute();
            $result = $getUser->get_result();
    
            if($result->num_rows > 0){
                //user exist
                $row = $result->fetch_assoc();
                $email = $row['email'];
                $username = $row['username'];
            }else{
                $errordesc="USer is invalid";
                $linktosolve="https://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User not found";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }   
        }
        if (!isset($_POST['ref']) && !isset($_POST['type'])){
            $errordesc="All fields must be passed";
            $linktosolve="https://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required accountnumber field in this endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $reference = cleanme($_POST['ref']);
            $type = cleanme($_POST['type']);
            $pending=0;
            $getUser = $connect->prepare("SELECT amttopay,currencytag,paymentstatus,wallettrackid FROM userwallettrans WHERE orderid = ? AND status=?");
            $getUser->bind_param("ss",$reference,$pending);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $amount = $row['amttopay'];
                $currency = $row['currencytag'];
                $status = $row['paymentstatus'];
                $wallettrackid= $row['wallettrackid'];
            }else{
                $errordesc="Payment is invalid";
                $linktosolve="https://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Payment already done";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
        }

        if (empty($userid) || empty($reference) || empty($email) || empty($username)){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the account name, account number field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
         // preventing the use of another payment method to pay for another currency
            $sysgetdata =  $connect->prepare("SELECT currencytag FROM currencyreceivemethods WHERE currencytag=? AND systemtouseid=?");
            $sysgetdata->bind_param("ss", $currency,$type);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys = $dsysresult7->num_rows;
             // preventing funding with out the currecncy trackid
            $sysgetdata =  $connect->prepare("SELECT currencytag FROM userwallet WHERE currencytag=? AND wallettrackid=?");
            $sysgetdata->bind_param("ss", $currency,$wallettrackid);
            $sysgetdata->execute();
            $dsysresult7 = $sysgetdata->get_result();
            $getsys2 = $dsysresult7->num_rows;
            if($getsys>0&&$getsys2>0){
            
            if ($type == 3){
                $verify = verify1appcardpay($reference,$email,$username, $userid,$reference);
                // think  // fubd with wallet trackid, chnaging wallet track id to fund another currency,
                if ($verify){
                    if ($status == 0 ){
                       $add =payAddUserBalance($userid,$amount,$currency,$wallettrackid);
                    }
                    // sms mail noti for who receive
                    $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                    $sysgetdata->bind_param("s",$userid);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    // check if user is sending to himself
                    $datais=$dsysresult7->fetch_assoc();
                    $ussernamesenttomail=$datais['email'];
                    $usersenttophone=$datais['phoneno'];
            
                    $subject = depositPaySuccessSubject($userid,$reference); 
                    $to = $ussernamesenttomail;
                    $messageText = depositPaySuccessfullText($userid, $reference);
                    $messageHTML = depositPaySuccessfullHTML($userid, $reference);
                    sendUserMail($subject,$to,$messageText, $messageHTML);
                    sendUserSMS($usersenttophone,$messageText);
                    // $userid,$message,$type,$ref,$status
                    bankdeposit_NGN_success_user_noti($userid,$reference);
                    
                    
                      giveMarketerPointForEachUsers($userid,1,$reference);
                    $maindata['userdata']= '';
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text = "Wallet Successfully Credited";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                }
                else{
                           $errordesc="BAD PAY METHOD";
                    $linktosolve="https://";
                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Payment not valid";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            } else if($type==1){
                $verify = verifypaystackcardpay($reference,$email,$username,$userid);
                 if ($verify){
                    if ($status == 0 ){
                       $add =payAddUserBalance($userid,$amount,$currency,$wallettrackid);
                    }
                    // sms mail noti for who receive
                    $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                    $sysgetdata->bind_param("s",$userid);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    // check if user is sending to himself
                    $datais=$dsysresult7->fetch_assoc();
                    $ussernamesenttomail=$datais['email'];
                    $usersenttophone=$datais['phoneno'];
            
                    $subject = depositPaySuccessSubject($userid,$reference); 
                    $to = $ussernamesenttomail;
                    $messageText = depositPaySuccessfullText($userid, $reference);
                    $messageHTML = depositPaySuccessfullHTML($userid, $reference);
                    sendUserMail($subject,$to,$messageText, $messageHTML);
                    sendUserSMS($usersenttophone,$messageText);
                    // $userid,$message,$type,$ref,$status
                    bankdeposit_NGN_success_user_noti($userid,$reference);
                    
                    giveMarketerPointForEachUsers($userid,1,$reference);
                    $maindata['userdata']= '';
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text = "Wallet Successfully Credited";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                }
                else{
                    $errordesc="BAD PAY METHOD";
                    $linktosolve="https://";
                    $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Payment not valid";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            }else{
                $errordesc="BAD PAY METHOD";
                $linktosolve="https://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Payment method passed not available";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }  
            }  else{
                $errordesc="BAD PAY METHOD";
                $linktosolve="https://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Payment method passed not available";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }   
            
        }
        
}else{
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