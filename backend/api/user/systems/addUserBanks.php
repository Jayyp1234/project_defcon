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
        }
        if ( !isset($_POST['accountname']) || !isset($_POST['bankcode']) || !isset($_POST['bankname']) || !isset($_POST['accountnumber'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else if ($_POST['accountnumber']<0 || ! is_numeric($_POST['accountnumber'])){
            // Insert all fields
            $errordesc = "Invalid account number";
            $linktosolve = 'https://';
            $hint = "Invalid account number";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }
        else{
            $accountname = cleanme($_POST['accountname']);
            $bankcode = cleanme($_POST['bankcode']);
            $bankname = cleanme($_POST['bankname']);
            $accountnumber = cleanme($_POST['accountnumber']);
            // $firstname = cleanme($_POST['firstname']);
            // $lastname = isset($_POST['lastname'])?cleanme($_POST['lastname']):' ';
            
            $getUser = $connect->prepare("SELECT * FROM users WHERE id = ?");
            $getUser->bind_param("s",$userid);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                //user exist
                $row = $result->fetch_assoc();
                $firstName = $row['fname'];
                $lastName = $row['lname'];
            }
            $firstname = isset($_POST['firstname'])?cleanme($_POST['firstname']):$firstName;
            $lastname = isset($_POST['lastname'])?cleanme($_POST['lastname']):$lastName;
        }

        if (empty($userid) || empty($accountname) || empty($bankcode) || empty($bankname) || empty($accountnumber) || empty($firstname)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the account name, account number field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
                $getdataemail =  $connect->prepare("SELECT * FROM userbanks WHERE user_id=? AND account_no=? AND bankcode=? ");
    $getdataemail->bind_param("sss",$userid,$accountnumber,$bankcode);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows== 0){
            
            $fullname=$firstname." ". $lastname;
             $refcode =" ";//addUserToPayStack($fullname,$accountnumber,payGetBankcode($bankcode)['paystackbankcode']);
            $insert_data = $connect->prepare("INSERT INTO userbanks (user_id,bank_name,account_no,account_name,bankcode,refcode) VALUES (?,?,?,?,?,?)");
            $insert_data->bind_param("ssssss",$userid,$bankname,$accountnumber,$accountname,$bankcode,$refcode);
           
            if( $insert_data->execute()){
                $insert_data->close();
                
                // sms mail noti for who receive
                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                // check if user is sending to himself
                $datais=$dsysresult7->fetch_assoc();
                $ussernamesenttomail=$datais['email'];
                $usersenttophone=$datais['phoneno'];;
                $subject = addBankSubject($userid); 
                $to = $ussernamesenttomail;
                $messageText = addBankText($userid);
                $messageHTML =addBankHTML($userid);
                sendUserMail($subject,$to,$messageText, $messageHTML);
                sendUserSMS($usersenttophone,$messageText);
                // $userid,$message,$type,$ref,$status
                addNew_bank_acc_user_noti($userid);

                
                
                
                
                
                $maindata['userdata']= [];
                $errordesc = "";
                $linktosolve = "https://";
                $hint = [];
                $errordata = [];
                $text = "Bank Added successfully";
                $method = getenv('REQUEST_METHOD');
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }
            else{
                // send db error
                $errordesc =  "opps an erorr occured";
                $linktosolve = 'https://';
                $hint = "500 code internal error, check ur database connections";
                $errorData = returnError7003($errordesc, $linktosolve, $hint);
                $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                respondInternalError($data);
            }
    }else{
            $errordesc="Bank already added";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Bank already added";
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