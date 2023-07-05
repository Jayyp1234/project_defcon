<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if ($method  == 'GET') {
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
            $getUser = $connect->prepare("SELECT * FROM users WHERE id = ?");
            $getUser->bind_param("s",$userid);
            $getUser->execute();
            $result = $getUser->get_result();
    
            if($result->num_rows > 0){
                //user exist
                $row = $result->fetch_assoc();
                $email = $row['email'];
                $username = $row['username'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $phone = $row['phoneno'];
            }
            else{
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User is not in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            if(isset($_GET['code']) && isset($_GET['name'])){
                //user exist
                $bankcode = cleanme($_GET['code']);
                $bankname = cleanmemini($_GET['name']);
            }
            else{
                $errordesc="Bad request";
                $linktosolve="https://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User is not in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            //To Get Active 
            $query = 'SELECT * FROM `systemsettings` WHERE 1';
            $stmt = $connect->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            $row =  mysqli_fetch_assoc($result);
            $active = $row['activebanksystem'];
            
            if ($active == 2 || $active == '2'){
                $status = monifygenerateAccNumber($fname,$lname,$email,2,$userid);
                if($status){
                    $maindata['userdata']='';
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text = "Account number generated";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                }else{
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Error Generating Bank Account";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            }
            else if ($active == 3 || $active == '3'){
                $userid = getUserWithPubKey($connect, $user_pubkey);
                $getUser = $connect->prepare("SELECT * FROM `kyc_details` WHERE user_id = ?");
                $getUser->bind_param("s",$userid);
                $getUser->execute();
                $result = $getUser->get_result();
        
                if($result->num_rows > 0){
                    //user exist
                    $row = $result->fetch_assoc();
                    $bvn = $row['bvn'];
                    if ($bvn != "" || !empty(trim($bvn))){
                        $status = oneappgenerateAccNumber($fname,$lname,$email,$phone,$bvn,$bankcode,$bankname,$userid);
                        if($status){
                            $maindata['userdata']='';
                            $errordesc = "";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $text = "Account number generated Successfully.";
                            $method = getenv('REQUEST_METHOD');
                            $status = true;
                            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                            respondOK($data);
                        }else{
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Error Generating Bank Account, try again later";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                            
                        }
                    }
                    else{
                        $maindata['userdata']='empty bvn';
                        $errordesc = "";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $text = "You need to be in level 2 before you can generate bank account.";
                        $method = getenv('REQUEST_METHOD');
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                }
                else{
                    $maindata['userdata']='empty bvn';
                    $errordesc = "";
                    $linktosolve = "https://";
                    $hint = [];
                    $errordata = [];
                    $text = "You need to be in level 2 before you can generate bank account.";
                    $method = getenv('REQUEST_METHOD');
                    $status = true;
                    $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                    respondOK($data);
                }
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