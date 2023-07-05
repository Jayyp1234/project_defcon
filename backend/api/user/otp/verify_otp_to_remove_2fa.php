<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/otp/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') === 'POST'){
        //collect input and validate it
        if(!isset($_POST['code']) ){
            $errordesc="Pin required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input OTP Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
      
        if(isset($_POST['code'])){
            $code = cleanme($_POST['code']);
        }
        
        if(isset($_POST['identity'])) {
            $token = cleanme($_POST['identity']);
        }
        //check if empty('') return true
        if((isset($_POST['code']) && empty($code)) || (isset($_POST['identity']) && empty($token))){
            $errordesc="input cannot be empty";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input Username and OTP Pin";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            //check if token exist
            $sql = "SELECT * FROM token WHERE useridentity =? AND otp=?";
            $getToken = $connect->prepare($sql);
            $getToken->bind_param('ss', $token,$code);
            $getToken->execute();
            $result = $getToken->get_result();
            if($result->num_rows ==1){
                $row = $result->fetch_assoc();
                $otp = $row['otp'];
                $time = $row['time'];
               $user_id= $id = $row['user_id'];
                $verifytype= $row['verifytype'];
                
                //then check expiry
                $expiredAt = time();
                if($time > time()){
                        $query="";
                        $updatePassQuery = "UPDATE users SET 2fa = null,login_2fa =0 WHERE id = $id";
                        $updateStmt = $connect->prepare($updatePassQuery);
                        if($updateStmt->execute()){
                            
                                  
                            // sms mail noti for who receive
                            $userid=$user_id;
                            $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                            $sysgetdata->bind_param("s",$userid);
                            $sysgetdata->execute();
                            $dsysresult7 = $sysgetdata->get_result();
                            // check if user is sending to himself
                            $datais=$dsysresult7->fetch_assoc();
                            $ussernamesenttomail=$datais['email'];
                            $usersenttophone=$datais['phoneno'];
                            $subject = deactivate2faSubject($userid); 
                            $to = $ussernamesenttomail;
                            $messageText = deactivate2faText($userid);
                            $messageHTML = deactivate2faHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($usersenttophone,$messageText);
                            // $userid,$message,$type,$ref,$status
                            turn_off_2fa_user_noti($userid);
                            
                            
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $method=getenv('REQUEST_METHOD');
                            $text = "Your 2fa Settings Has Been Removed Successfully !";
                            $status = true;
                            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);

                        }else{
                            //invalid input/ server error
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="invalid Email or DB issue";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }


                }else{
                    //otp expired
                    $errordesc="OTP Expired";
                    $linktosolve="https://";
                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="The One-Time Password (OTP) you received has expired. Please click on the 'Resend' option to receive a new token.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);

                }
            }else{
                //invalid token
                $errordesc="Incorrect token";
                $linktosolve="htps://";
                $hint=["Input token sent to your email or phone","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Fill in valid token";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);

            }
        }

}else {
        //method not allowed
        $errordesc="Method not allowed";
        $linktosolve="htps://";
        $hint=["Ensure to use the method stated in the documentation."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Method used not allowed";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondMethodNotAlowed($data);
    }




