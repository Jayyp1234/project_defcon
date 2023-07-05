<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/systems/".basename($_SERVER['PHP_SELF']);
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
 
        $user_id = getUserWithPubKey($connect, $user_pubkey);
        // check if the current password field was passed 
        if ( !isset($_GET['card_id'] ) ) {
            $errordesc="All fields must be passed";
            $linktosolve="https://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required card id in this endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $cardid = cleanme($_GET['card_id']);
        }

        if (empty($user_id) || empty($cardid) ){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, current password and new password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the card_id field in this endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }

        // Delete cards from the db;
        $query = "DELETE FROM `userbanks` WHERE `id` = ? AND `user_id` = ?";
        $delete = $connect->prepare($query);
        $delete->bind_param("ss",$cardid,$user_id);
        $delete->execute();
        if ( $delete->affected_rows>0 ){
            
                            // sms mail noti for who receive
                            $userid=$user_id;
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];;
                $subject = deleteBankSubject($userid); 
                $to = $ussernamesenttomail;
                $messageText = deleteBankText($userid);
                $messageHTML =deleteBankHTML($userid);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                // $userid,$message,$type,$ref,$status
                deleted_bank_acc_user_noti($userid);
            
            
            
            
            
            $maindata=[];
            $errordesc = "";
            $linktosolve = "https://";
            $hint = "The bank deletion process was carried out successfully.";
            $errordata = [];
            $text = "The bank deletion process was carried out successfully.";
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }
        else{
            $errordesc="No Permission";
            $linktosolve="https://";
            $hint=["Kindly pass value to the user_id, current password and new password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="User Not Authorized to Delete Cards, Contact Admin.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
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