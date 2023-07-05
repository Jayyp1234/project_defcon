<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);
if ($method == 'POST') {
    $maindata['frozedate']="";
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
 
        $user_id = getUserWithPubKey($connect, $user_pubkey);
        // check if the current password field was passed 
        $currentpassword="ddd";
        // if ( !isset($_POST['currentpassword'] ) ) {
        //     $errordesc="All fields must be passed";
        //     $linktosolve="htps://";
        //     $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
        //     $text="Kindly pass the required current password field in this register endpoint";
        //     $method=getenv('REQUEST_METHOD');
        //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
        //     respondBadRequest($data);

        // }
        // else{
        //     $currentpassword = cleanme($_POST['currentpassword'],1);
        // }

        // check if the new password field was passed 
        if ( !isset($_POST['password'])) {
            $errordesc="All fields must be passed";
            $linktosolve="https://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
            

        }
        else{
            $newpassword = cleanme($_POST['password']);
        }

        if (empty($user_id) || empty($currentpassword) || empty($newpassword) ){
            $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Insert all fields","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Insert all fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else if(strlen($newpassword)>4 || strlen($newpassword)<4 ||!is_numeric($newpassword)){
              $errordesc="Insert all fields";
            $linktosolve="https://";
            $hint=["Insert all fields","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Pin should only be a number and 4 in length";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        // check for user details in the database
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
            $row =  mysqli_fetch_assoc($result);
            $oldHashedPass = $row['password'];
            $oldHashedPin = $row['pin'];
            $stmt->close();
            
            // check if old pass hash from db is equivalent to the current password passed
            // if ( check_pass($currentpassword, $oldHashedPass)){

                // Check if new password is not equal to old password
                if ( check_pass($newpassword, $oldHashedPin)){
                    $errordesc="New Pin Can't be equal to old pin";
                    $linktosolve="htps://";
                    $hint=["Password must be different from old password in the db","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Pin must be different from old pin.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                    
                }

                $hashNewPassword = Password_encrypt(cleanme($newpassword));
                $done=1;
                $dateis=date("h:ia, d m Y");
                $updatePassQuery = "UPDATE users SET pin = ?, lastpinupdate = ?,pinadded=? WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('sssi', $hashNewPassword, $dateis,$done, $user_id);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0){
                    
                            
                            $userid=$user_id;
                            $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                            $sysgetdata->bind_param("s",$userid);
                            $sysgetdata->execute();
                            $dsysresult7 = $sysgetdata->get_result();
                            // check if user is sending to himself
                            $datais=$dsysresult7->fetch_assoc();
                            $ussernamesenttomail=$datais['email'];
                            $usersenttophone=$datais['phoneno'];
                            $subject = changePinSubject($userid); 
                            $to = $ussernamesenttomail;
                            $messageText = changePinText($userid);
                            $messageHTML = changePinHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($usersenttophone,$messageText);
                            // $userid,$message,$type,$ref,$status
                            change_pin_user_noti($userid);

                    
                    
                    
                    
                    $maindata=[];
                    $errordesc = " ";
                    $linktosolve = "htps://";
                    $hint = "Successfull Pin Change";
                    $errordata = [];
                    $text = "Pin Updated Successfully.";
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                
                }else{

                     //invalid input/ server error
                     $errordesc="Bad request";
                     $linktosolve="https://";
                     $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                     $errordata=returnError7003($errordesc,$linktosolve,$hint);
                     $text="Invalid phoneno or DB issue";
                     $method=getenv('REQUEST_METHOD');
                     $data=returnErrorArray($text,$method,$endpoint,$errordata);
                     respondBadRequest($data);
                }
            
            // }else{
                
            //     $errordesc="Incorrect Password";
            //     $linktosolve="htps://";
            //     $hint=["Current password passed does not match the passowrd in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
            //     $text="Current password passed does not match the passowrd in the database";
            //     $method=getenv('REQUEST_METHOD');
            //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
            //     respondBadRequest($data);
            // }
        }


    }
    else {
    $errordesc = "Method not allowed";
    $linktosolve = "htps://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}