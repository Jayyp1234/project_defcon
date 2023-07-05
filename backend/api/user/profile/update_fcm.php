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
if (getenv('REQUEST_METHOD') == 'POST') {
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
        if ( !isset($_POST['fcm'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass the required field";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $fcm = cleanme($_POST['fcm']);;
        }
        if (empty($user_id) || empty($fcm)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Postal code Field Empty.";
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
                $linktosolve="htps://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="User is not in the database ensure the user is in the database";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
                
            }else{
                $row =  mysqli_fetch_assoc($result);
                $stmt->close();
                if (1){
                    $updatePassQuery = "UPDATE users SET  fcm = ? WHERE id = ?";
                    $updateStmt = $connect->prepare($updatePassQuery);
                    $updateStmt->bind_param('si',$fcm, $user_id);
                    $updateStmt->execute();
        
                     
            
                    if ( $updateStmt->affected_rows > 0 ){
                            $maindata=[];
                            $errordesc = " ";
                            $linktosolve = "https://";
                            $hint = "Success";
                            $errordata = [];
                            $text = "Your Details is Changed Successfully";
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
                             $text="No data changed";
                             $method=getenv('REQUEST_METHOD');
                             $data=returnErrorArray($text,$method,$endpoint,$errordata);
                             respondBadRequest($data);
                        }
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