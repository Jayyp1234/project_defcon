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
        //collect input and validate it
        if(!isset($_POST['code'])||!isset($_POST['token'])){
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
        
        if(isset($_POST['token'])) {
            $token = cleanme($_POST['token']);
        }


        //check if empty('') return true
        if((isset($_POST['code']) && empty($code)) || (isset($_POST['token']) && empty($token))){
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
            $sql = "SELECT * FROM token WHERE token =? || otp=?";
            $getToken = $connect->prepare($sql);
            $getToken->bind_param('ss', $token,$code);
            $getToken->execute();
            $result = $getToken->get_result();
            if($result->num_rows ==1){
                $row = $result->fetch_assoc();
                $otp = $row['otp'];
                $time = $row['time'];
                $id = $row['user_id'];
                $verifytype= $row['verifytype'];
                
                //then check expiry
                $expiredAt = time();
                
                if($time > time()){
                    $query="";
                    $active=1;
                    if($verifytype==1){
                        $query="UPDATE users SET emailverified = ? WHERE id =?";
                        //To Check OTP PIN
                        //update status using id
                        $result->close();
                        $sql = "$query";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('ss', $active,$id);
                        $stmt->execute();
                        // if($stmt->affected_rows > 0 ){
                        if($stmt->execute()){
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $method=getenv('REQUEST_METHOD');
                            $text = "Email Address Verified, Proceed to login";
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
                    }else if($verifytype==2){
                          $query="UPDATE users SET phoneverified = ? WHERE id=?";
                          //To Check OTP PIN
                        //update status using id
                        $result->close();
                        $sql = "$query";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param('ss', $active,$id);
                        $stmt->execute();
                        // if($stmt->affected_rows > 0 ){
                        if($stmt->execute()){
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = [];
                            $errordata = [];
                            $method=getenv('REQUEST_METHOD');
                            $text = "Phone number Verified, Proceed to login";
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




