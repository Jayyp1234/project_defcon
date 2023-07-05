<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)



include "../../../config/utilities.php";

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);

if (getenv('REQUEST_METHOD') === 'POST'){
        //collect input and validate it
        if(!isset($_POST['password'])){
            $errordesc="Password required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input password";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $password = cleanme($_POST['password'],1);
        }
        
        if(isset($_POST['token'])) {
            $token = cleanme($_POST['token']);
        }
        else{
            $errordesc="Token required";
            $linktosolve="https://";
            $hint=["Check your Email or Phone for Token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="pass in token in the token field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }

        //check if empty('') return true
        if(empty($password) || empty($token)){
            $errordesc="input cannot be empty";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Input Username and Password";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }  
    //     elseif (!preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,100})",$password)) {
    //     $errordesc="Bad request";
    //     $linktosolve="https://";
    //     $hint=["Data already registered in the database.", "For security purpose,Password requires at least 1 lower and upper case character, 1 number, 1 special character and must be at least 6 characters long"];
    //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
    //     $text="For security purpose,Password requires at least 1 lower and upper case character, 1 number, 1 special character and must be at least 6 characters long";
    //     $method=getenv('REQUEST_METHOD');
    //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
    //     respondBadRequest($data);
    // } 
        else{
            //check if token exist
            $sql = "SELECT * FROM token WHERE token =? || otp=?";
            $getToken = $connect->prepare($sql);
            $getToken->bind_param('ss', $token,$token);
            $getToken->execute();
            
            $result = $getToken->get_result();

            if($result->num_rows > 0){
                //then check expiry
                $row = $result->fetch_assoc();
                $expiredAt = $row['time'];
                
                if($expiredAt > time()){
                    //update password using email or phone;
                    $hashPassword = Password_encrypt($password);
                    $verifyType = $row['verifytype'];
                    
                    //verifytype 1 = email, 2=phone
                    if($verifyType == 1){
                        //update password using email
                        $email = $row['useridentity'];
                        $result->close();
                        $empty=" ";
                        $pinoff=0;
                        // $sql = "UPDATE users SET password = ?,	pinadded=?,lastpinupdate=?,pin=? WHERE email =?";
                        // $stmt = $connect->prepare($sql);
                        // $stmt->bind_param('sssss', $hashPassword,$pinoff,$empty,$empty, $email);
                        // $stmt->execute();
                        $sql = "UPDATE users SET password = ? WHERE email =?";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('ss', $hashPassword, $email);
                        $stmt->execute();
                        if($stmt->affected_rows > 0 ){
                            $checkdata =  $connect->prepare("SELECT id,email,phoneno FROM users WHERE email=? ");
                            $checkdata->bind_param("s", $email);
                            $checkdata->execute();
                            $dresult = $checkdata->get_result();
                            $getsys = $dresult->fetch_assoc();
                            $userid=$getsys['id'];
                            $phoneno =$getsys['phoneno'];
                            $email=$getsys['email'];
                            
                            
                            $subject = resetPasswordSubject($userid);
                            $to = $email;
                            $messageText = resetPasswordSuccessText($userid);
                            $messageHTML = resetPasswordSuccessHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($phoneno,$messageText);
                            
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $method=getenv('REQUEST_METHOD');
                            $text = "Password Updated, Proceed to login";
                            $status = true;
                            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);

                        }else{
                            //invalid input/ server error
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Nothing could be updated";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }

                    }
                    if($verifyType == 2){
                        // update password using phoneno
                        $phoneno = $row['useridentity'];
                          $empty=" ";
                        $pinoff=0;
                        $sql = "UPDATE users SET password = ?,pinadded=?,lastpinupdate=?,pin=?  WHERE phoneno =?";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('sssss', $hashPassword,$pinoff, $empty,$empty,$phoneno);
                        $stmt->execute();
                        if($stmt->affected_rows >0 ){
                            $checkdata =  $connect->prepare("SELECT id,email,phoneno FROM users WHERE email=? ");
                            $checkdata->bind_param("s", $email);
                            $checkdata->execute();
                            $dresult = $checkdata->get_result();
                            $getsys = $dresult->fetch_assoc();
                            $userid=$getsys['id'];
                            $phoneno =$getsys['phoneno'];
                            $email=$getsys['email'];
                            
                            
                            $subject = resetPasswordSubject($userid);
                            $to = $email;
                            $messageText = resetPasswordSuccessText($userid);
                            $messageHTML = resetPasswordSuccessHTML($userid);
                            sendUserMail($subject,$to,$messageText, $messageHTML);
                            sendUserSMS($phoneno,$messageText);
                            
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $method=getenv('REQUEST_METHOD');
                            $text = "Password Updated, Proceed to login";
                            $status = true;
                            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);
                        }else{
                            //invalid input/ server error
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Nothing could be updated";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        }

                    }

                }else{
                    //token expired
                    $errordesc="Token Expired";
                    $linktosolve="https://";
                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="The link to reset your password has expired. Please generate a new token to proceed on forgot password page";
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




