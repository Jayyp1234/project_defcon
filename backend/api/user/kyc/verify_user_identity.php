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
$method = getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') === 'POST'){
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
            $user_id = getUserWithPubKey($connect, $user_pubkey);
        }
        //collect input and validate it
        // check if the current password field was passed 
        if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['dob']) || !isset($_POST['bvn'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the first name in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        else{
            $firstname = cleanme($_POST['firstname']);
            $lastname = cleanme($_POST['lastname']);
            $dob = cleanme($_POST['dob']);
            $bvn = cleanme($_POST['bvn']);
        }
        $secret_key = "prod_sk_8gWaxwBLne65ihjqyQh9XrEHT";
        $appid = "63121f44fc538e00354ecf05";
        
        if (empty($firstname) || empty($lastname) || empty($dob) || empty($bvn)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the user_id, username field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }


        if(empty($dob)){
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
            $url ="https://api.dojah.io/api/v1/kyc/age_verification?mode=bvn&dob=$dob&first_name=$firstname&last_name=$lastname&bvn=$bvn";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                //u change the url infront based on the request u want
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //change this based on what u need post,get etc
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "authorization: $secret_key",
                    "appid: $appid",
                    "cache-control: no-cache"
                ),
            ));
            $userdetails = curl_exec($curl);
            //print_r($userdetails);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                //print_r($err);
                $refcode="";
                // throw new \Exception("Error getting bank names: $err");
                $errordesc="Unauthorized";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text= $err;
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            } 
            else {
                $data = json_decode($userdetails);
                //print_r($data->error);
                if (isset($data->error)){
                    $errordesc="Unauthorized";
                    $linktosolve="https://";
                    $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text= $data->error;
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
                else if ($data->entity->verification){
                    $updatePassQuery = "UPDATE users SET  userlevel = userlevel + 1 WHERE id = ?";
                    $updateStmt = $connect->prepare($updatePassQuery);
                    $updateStmt->bind_param('i',$user_id);
                    if ($updateStmt->execute()){
                        
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
                        // $subject = levelUpdatedSubject($userid); 
                        // $to = $ussernamesenttomail;
                        // $messageText = levelUpdatedText($userid);
                        // $messageHTML =levelUpdatedHTML($userid);
                        // sendUserMail($subject,$to,$messageText, $messageHTML);
                        // sendUserSMS($usersenttophone,$messageText);
                        // // $userid,$message,$type,$ref,$status
                        
                        
                        
                        $maindata=[];
                        $errordesc = " ";
                        $linktosolve = "https://";
                        $hint = [];
                        $errordata = [];
                        $method=getenv('REQUEST_METHOD');
                        $text = "Identity Verification Successful.";
                        $status = true;
                        $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        respondOK($data);
                    }
                }
                else{
                    $errordesc="Verification Failed";
                    $linktosolve="https://";
                    $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text= "Invalid Verfication, Please Check details and try again.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
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




