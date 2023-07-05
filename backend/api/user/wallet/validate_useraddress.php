<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

//     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    require_once '../../../config/GoogleAuthenticator/vendor/autoload.php';
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
    // check if the right request was sent
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
        // get if the user is a shop
        // send error if ur is not in the database
        if (!getUserWithPubKey($connect, $user_pubkey)){
            // send user not found response to the user
            $errordesc =  "Not Authorized";
            $linktosolve = 'https://';
            $hint = "Only authorized user allowed";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondBadRequest($data);
        }
        $user_id = getUserWithPubKey($connect, $user_pubkey);

        if ( !isset($_POST['type'])) {

            $errordesc="Type required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Type must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }else{
            $type = cleanme($_POST['type']);// 1=username 2=swap
        }

        if ( $type==4){   
            if (!isset($_POST['address'])) {
            
                $errordesc="Address required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Address must be passed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            
            }else{
                $address = cleanme($_POST['address']);
            }
            if (!isset($_POST['network_cointid'])) {
            
                $errordesc="Network is  required";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Network must be selected";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            
            }else{
                $network_cointid = cleanme($_POST['network_cointid']);
            }
        
        
            $checkdata =  $connect->prepare("SELECT * FROM coinaddress_validator WHERE cointrack_id=? ");
            $checkdata->bind_param("s",$network_cointid);
            $checkdata->execute();
            $dresultUser = $checkdata->get_result();
            if($dresultUser->num_rows > 0){
                $foundUser= $dresultUser->fetch_assoc();
                $minlen = $foundUser['min_len'];
                $maxlen = $foundUser['max_len'];
                $startwith = $foundUser['start_with'];
                $getstarters=explode(",",$startwith);
                $badstarted=false;
                if(strlen($address)<$minlen || strlen($address)>$maxlen ){
                    $badstarted=true;
                }
                
                $addressfirstletters=substr($address,0,1);
                $addresssecletters=substr($address,0,2);
                $addressthirdletters=substr($address,0,3);
                if(!in_array($addressfirstletters, $getstarters) && !in_array($addresssecletters, $getstarters) && !in_array($addressthirdletters, $getstarters)){
                    $badstarted=true;
                }
                if($badstarted==true){
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Invalid address, please check";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                }
            
            }
        
        
            $data = [];
            $text= "Valid Address";
            $status = true;
            $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
            respondOK($successData);
                                                                                    
        }else{
        
            $errordesc="Amount required";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Type not valid";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data); 
        }                                                                  
    }else{

        // Send an error response because a wrong method was passed 
        $errordesc = "Method not allowed";
        $linktosolve = 'https://';
        $hint = "This route only accepts POST request, kindly pass a post request";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondMethodNotAlowed($data);
        
    }
?>