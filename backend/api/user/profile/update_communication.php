<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    Header("Cache-Control: no-cache");

   
    
    
    include "../../../config/utilities.php";

    $endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
if ( $method == 'POST') {
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
        function clearer($data){
            if ($data == 'true' || $data == true){
                return 'true';
            }
            else{
                return 'false';
            }
        }
        $user_id = getUserWithPubKey($connect, $user_pubkey);
        // check if the current password field was passed 
        if ( !isset($_POST['securitynotification']) || !isset($_POST['transfernotification']) || !isset($_POST['depositnotification'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required current password field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $securitynotification = cleanme($_POST['securitynotification']);
            $transfernotification = cleanme($_POST['transfernotification']);
            $depositnotification =  cleanme($_POST['depositnotification']);
        }

        if (empty($user_id)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, securitynotification field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the user_id, securitynotification field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            // check for user details in the database
            $query = 'SELECT * FROM users WHERE id = ?';
            $stmt = $connect->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $num_row = $result->num_rows;
            
            if ( $num_row < 1){
                $errordesc="User not found";
                $linktosolve="https://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User is not in the database ensure the user is in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
                
            }else{
                $updatePassQuery = "UPDATE users SET depositnotification = ?, securitynotification = ?, transfernotification = ? WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                
                $updateStmt->bind_param('sssi',$depositnotification,$securitynotification,$transfernotification,$user_id);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0){
                    
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
                            $subject = updateCommunicationSubject($userid); 
                            $to = $ussernamesenttomail;
                            $messageText = updateCommunicationText($userid);
                            $messageHTML =updateCommunicationHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($usersenttophone,$messageText);
                            // $userid,$message,$type,$ref,$status
                            update_communication_user_noti($userid);
                    
                    
                    
                        $maindata=[];
                        $errordesc = " ";
                        $linktosolve = "https://";
                        $hint = "Success";
                        $errordata = [];
                        $text = "Your information has been successfully updated.";
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                }
                else{
                    //invalid input/ server error
                    $errordesc="Bad request";
                    $linktosolve="https://";
                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="No changes were made";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
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