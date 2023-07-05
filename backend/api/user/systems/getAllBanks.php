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
if ($method == 'GET') {
    // // Get company private key
    // $query = 'SELECT * FROM apidatatable';
    // $stmt = $connect->prepare($query);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $row =  mysqli_fetch_assoc($result);
    // $companykey = $row['privatekey'];
    // $servername = $row['servername'];
    // $expiresIn = $row['tokenexpiremin'];
    // $decodedToken = ValidateAPITokenSentIN($servername, $companykey, $method, $endpoint);
    // $user_pubkey = $decodedToken->usertoken;
    //      // send error if ur is not in the database
    //     if (!getUserWithPubKey($connect, $user_pubkey)){
    //         $errordesc="Bad request";
    //         $linktosolve="htps://";
    //         $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
    //         $errordata=returnError7003($errordesc,$linktosolve,$hint);
    //         $text="User is not in the database ensure the user is in the database";
    //         $method=getenv('REQUEST_METHOD');
    //         $data=returnErrorArray($text,$method,$endpoint,$errordata);
    //         respondBadRequest($data);
            
    //     }
    //     else{
    //         $userid = getUserWithPubKey($connect, $user_pubkey);
    //     }
      $sqlQuery = "SELECT name,sysbankcode FROM bankaccountsallowed WHERE status=1 ORDER by name ASC";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $users['joint']=$users['sysbankcode']."^".$users['name'];
                array_push($allResponse,json_decode(json_encode($users), true));
            }
            $maindata['userdata']= $allResponse;
            $maindata['total'] = $numRow;
            $maindata['currentpage'] = 1;
            $maindata['totalpage'] =1;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }
        else{
            $allResponse = [];
            $maindata['userdata']= $allResponse;
            $maindata['total'] = $numRow;
            $maindata['currentpage'] = $pagem;
            $maindata['totalpage'] = $pages;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }
}
else{
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