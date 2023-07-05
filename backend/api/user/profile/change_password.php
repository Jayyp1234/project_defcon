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
        if ( !isset($_POST['currentpassword'] ) ) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required fields";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $currentpassword = cleanme($_POST['currentpassword'],1);
        }

        // check if the new password field was passed 
        if ( !isset($_POST['password'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required new password field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
            

        }
        else{
            $newpassword = cleanme($_POST['password'],1);
  
        }

        if (empty($user_id) || empty($currentpassword) || empty($newpassword) ){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, current password and new password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the user_id, current password and new password field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }elseif (strlen($newpassword)<6||strlen($newpassword)>13) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Password can not be less than 8 or greater than 13";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }
        elseif (!preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,100})",$newpassword)) {
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Data already registered in the database.", "For security purpose,Password requires at least 1 lower and upper case character, 1 number, 1 special character and must be at least 6 characters long"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="For security purpose,Password requires at least 1 lower and upper case character, 1 number, 1 special character and must be at least 6 characters long";
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
            $stmt->close();
            
            // check if old pass hash from db is equivalent to the current password passed
            if ( check_pass($currentpassword, $oldHashedPass)){

                // Check if new password is not equal to old password
                if ( check_pass($newpassword, $oldHashedPass)){
                    
                    $errordesc="New Password Can't be equal to old password";
                    $linktosolve="htps://";
                    $hint=["Password must be different from old password in the db","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Password must be different from old password";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                    
                }

                $hashNewPassword = Password_encrypt(cleanme($newpassword,1));
                $timeis= date("h:ia, d m Y");
                $updatePassQuery = "UPDATE users SET password = ?, lastpassupdate = ? WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('ssi', $hashNewPassword,$timeis, $user_id);
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
                            $subject = changePasswordSubject($userid); 
                            $to = $ussernamesenttomail;
                            $messageText = changePasswordText($userid);
                            $messageHTML = changePasswordHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($usersenttophone,$messageText);
                            // $userid,$message,$type,$ref,$status
                            change_password_user_noti($userid);

                    
                    
                    
                    
                    $maindata=[];
                    $errordesc = " ";
                    $linktosolve = "htps://";
                    $hint = "Successfull Password Change";
                    $errordata = [];
                    $text = "Password Updated Proceed to Login";
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
            
            }else{
                
                $errordesc="Incorrect Password";
                $linktosolve="htps://";
                $hint=["Current password passed does not match the passowrd in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Current password passed does not match the passowrd in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
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