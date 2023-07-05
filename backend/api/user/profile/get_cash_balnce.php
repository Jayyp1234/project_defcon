<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    Header("Cache-Control: no-cache");

     
    include "../../../config/utilities.php";  
    
  

    $endpoint = basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');

    if ($method == 'GET') {

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
        $pubkey = $decodedToken->usertoken;

        $admin =  checkIfIsAdmin($connect, $pubkey);
        $user = getUserWithPubKey($connect, $pubkey);

        if  (!$admin && !$user){

            // send user not found response to the user
            $errordesc =  "User not an Admin";
            $linktosolve = 'https://';
            $hint = "Only Admin has the ability to add send grid api details";
            $errorData = returnError7003($errordesc, $linktosolve, $hint);
            $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
            respondUnAuthorized($data);
        }

        if ( $admin  ){
            if ( isset($_GET['user']) ){
                $user = "";
            }else{
                $user = cleanme($_GET['user']);
            }

            if ( empty($user) ){
                $errordesc =  "User not found";
                $linktosolve = 'https://';
                $hint = "User not found";
                $errorData = returnError7003($errordesc, $linktosolve, $hint);
                $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
                respondBadRequest($data);
            }
        }



        $total_cashback = getSumofCashback(1, $user);
        $total_withdrawalBalance = getSumofCashback(2, $user);

        // get user cashback balance
        $cbbalnce = getColumnFromField("users", "cashback_bal", "id", $user);
        $cbbalnce = ( $cbbalnce )? $cbbalnce : 0;

        // get withdrawal limit
        $systemSettings = getAllSystemSetting();
        $withdrawlLimit = ( $systemSettings )? $systemSettings['min_referall_withdraw'] : 0;

        
            
        $data = array(
            'total_cashback' => "$total_cashback",
            'total_withdrawal' => "$total_withdrawalBalance",
            'user_cashback_bal' => "$cbbalnce",
            'withdrawlLimit' => "$withdrawlLimit"
        );
        $text= "Fetch Successful";
        $status = true;
        $successData = returnSuccessArray($text, $method, $endpoint, [], $data, $status);
        respondOK($successData);
        

        

    }else{

        // Send an error response because a wrong method was passed 
        $errordesc = "Method not allowed";
        $linktosolve = 'https://';
        $hint = "This route only accepts GET request, kindly pass a post request";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, []);
        respondMethodNotAlowed($data);

    }
?>