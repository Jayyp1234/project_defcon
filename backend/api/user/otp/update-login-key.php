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
      
        else{
            $status = cleanme($_POST['code']);
            $query = 'SELECT * FROM apidatatable where id = 1';
            $stmt = $connect->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row =  mysqli_fetch_assoc($result);
            $companykey = $row['privatekey'];
            
            //$servername="2FA_verification";
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
                    
            }else{
                    $userid = getUserWithPubKey($connect, $user_pubkey);
                    $checkdata =  $connect->prepare("SELECT * FROM users WHERE id=? ");
                    $checkdata->bind_param("s", $userid);
                    $checkdata->execute();
                    $dresult = $checkdata->get_result();
                    if ($dresult->num_rows == 0) {// checking if data is valid
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["Data not registered in the database.", "Use registered email to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="This User does not exists.";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    }else {
                        $row = $dresult->fetch_assoc();
                        $useridentity="";
                        $user_id = $row['id'];
                        $user_identity = $row['email'];
                        $checkdata->close();
                        if($status=="true"){
                            $status=1;
                        }else{
                            $status=0;
                        }
                        
                        $updateStmt = $connect->prepare("UPDATE users SET login_2fa = $status WHERE id = $user_id");
                            if ($updateStmt->execute()){
                                $updateStmt->close();
                                $maindata= "";
                                $errordesc="";
                                $linktosolve="https://";
                                $hint=[];
                                $errordata=[];
                                if($status==1){
                                    $text="You Have Successfully Turned On 2FA for Login.";
                                }else{
                                    $text="You Have Successfully Turned Off 2FA for Login.";
                                }
                                $method=getenv('REQUEST_METHOD');
                                $status=true;
                                $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                respondOK($data);
                            } else{
                                // send internal error response
                                $errordesc =  $tokenStmt->error;
                                $linktosolve = 'https://';
                                $hint = "500 code internal error, check ur database connections";
                                $errorData = returnError7003($errordesc, $linktosolve, $hint);
                                $text="Error Fetching Reset Token";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondInternalError($data);
                            }
                    } 
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




