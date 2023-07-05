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
        
           // check if the current password field was passed 
        if (!isset($_POST['vctid'])|| !isset($_POST['pin'])|| !isset($_POST['type']) ) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
             $vctid = cleanme($_POST['vctid']);
                $pin = cleanme($_POST['pin']);
                 $type = cleanme($_POST['type']);// 1 freeze, 2 unfreeze
        }
        
        
        $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel FROM users WHERE id=? ");
        $checkdata->bind_param("s", $userid);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
    
    
            // check user pin
        $verifypass =check_pass($pin,$passpin);
        
        if (empty($vctid)||empty($pin)||empty($type)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the track id";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if (!$verifypass) {
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid pin.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }
        


           $notdeleted=0;      
        $sqlQuery = "SELECT `id`,`vc_card_id`,`vc_type_tid`,`status`, `trackid`, `balance`,`brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, `freeze` FROM vc_customer_card WHERE user_id=? AND trackid=?  AND deleted=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sss", $userid,$vctid,$notdeleted);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            $users = $result->fetch_assoc();
                $users['cardname']="";
                $maincardid=$users['id'];
                $cardid=$users['vc_card_id'];
                $tid=$users['vc_type_tid'];
                $vtid=$users['trackid'];
                  $supplier=0;
                $currency="";
                $sqlQuery1 = "SELECT name,currency,supplier FROM vc_type WHERE trackid=?";
                $stmt1= $connect->prepare($sqlQuery1);
                $stmt1->bind_param("s",$tid);
                $stmt1->execute();
                $result1= $stmt1->get_result();
                $numRow2 = $result1->num_rows;
                if($numRow2 > 0){
                        $users2 = $result1->fetch_assoc();
                        $users['cardname']=$users2['name'];
                        $currency=$users2['currency'];
                        $supplier=$users2['supplier'];
                }
                
                $text="";
                $frozeen=0;
                if($type==1){
                    $text="Frozen successfully";
                    $status="inactive"; //inactive,canceled,active
                    $statusno=1;
                    $donewell=false;
                    if($supplier==1){
                        $donewell=changeCardStatus($currency,$cardid,$status);
                    }else if($supplier==2){
                         $donewell=freezeCardbc_card($currency,$cardid,0); 
                    }
                    
                    if($donewell){
                        $frozeen=1;
                        $sql = "UPDATE vc_customer_card SET freeze = ? WHERE id=?";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('ss',$statusno,$maincardid);
                        $stmt->execute();
                    }
                    
                    
                    
                }else if($type==2){
                   $text="Card Unfrozen"; 
                   $status="active"; //inactive,canceled,active
                   $statusno=0;
                         $donewell=false;
                    if($supplier==1){
                        $donewell=changeCardStatus($currency,$cardid,$status);
                    }else if($supplier==2){
                         $donewell=freezeCardbc_card($currency,$cardid,1); 
                    }
                    
                    if($donewell){
                          $frozeen=2;
                        $sql = "UPDATE vc_customer_card SET freeze = ? WHERE id=?";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('ss',$statusno,$maincardid);
                        $stmt->execute();
                    }
                   
                }
             if($stmt->affected_rows >0 && $frozeen>0){
                          // sms mail noti for who receive
                        $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
                        $sysgetdata->bind_param("s",$userid);
                        $sysgetdata->execute();
                        $dsysresult7 = $sysgetdata->get_result();
                        // check if user is sending to himself
                        $datais=$dsysresult7->fetch_assoc();
                        $ussernamesenttomail=$datais['email'];
                        $usersenttophone=$datais['phoneno'];
                        $userusername=$datais['username'];
                        
                    if($frozeen==1){
                  
                        $subject = virtualCardFrozenSubject($userid,$vtid); 
                        $to = $ussernamesenttomail;
                        $messageText =virtualCardFrozenText($userid, $vtid);
                        $messageHTML = virtualCardFrozenHTML($userid,$vtid);
                        sendUserMail($subject,$to,$messageText, $messageHTML);
                        sendUserSMS($usersenttophone,$messageText);
                        // $userid,$message,$type,$ref,$status
                        freeze_vc_user_noti($userid,$vtid);
                        notify_admin_noti_b_bot($messageText,$userid);
                    }else if($frozeen==2){
                                $subject = virtualCardUnFrozenSubject($userid,$vtid); 
                        $to = $ussernamesenttomail;
                        $messageText =virtualCardUnFrozenText($userid, $vtid);
                        $messageHTML = virtualCardUnFrozenHTML($userid,$vtid);
                        sendUserMail($subject,$to,$messageText, $messageHTML);
                        sendUserSMS($usersenttophone,$messageText);
                        // $userid,$message,$type,$ref,$status
                        unfreeze_vc_user_noti($userid,$vtid);
                         notify_admin_noti_b_bot($messageText,$userid);
                    }
                    
                 
                 
                    $maindata=[];
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text =$text;
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
             }else{
               $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="An error occured, try again later";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
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