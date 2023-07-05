<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        // header("Cache-Control: no-cache");
    $seconds_to_cache = 100;
    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
    header("Expires: $ts");
    header("Pragma: cache");
    header("Cache-Control: max-age=$seconds_to_cache");
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/currency/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
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
        
           // check if the current password field was passed 
        if (!isset($_POST['vctid'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
             $vctid = cleanme($_POST['vctid']);
        }
        
        
        
        if (empty($vctid)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the track id";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } 

        

           $notdeleted=0;      
        $sqlQuery = "SELECT `json_response`,`vc_card_id`,`vc_type_tid` FROM vc_customer_card WHERE user_id=? AND trackid=?  AND deleted=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sss", $userid,$vctid,$notdeleted);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $cardid=$users['vc_card_id'];
                $tid=$users['vc_type_tid'];
                $currency="";
                $supplier=null;
                $sqlQuery1 = "SELECT name,currency,theme,supplier FROM vc_type WHERE trackid=?";
                $stmt1= $connect->prepare($sqlQuery1);
                $stmt1->bind_param("s",$tid);
                $stmt1->execute();
                $result1= $stmt1->get_result();
                $numRow2 = $result1->num_rows;
                if($numRow2 > 0){
                        $users2 = $result1->fetch_assoc();
                        $currency=$users2['currency'];
                        $supplier=$users2['supplier'];
                }
                $balance=0;
              
                if($supplier==1){           
                        $breakdata = json_decode(revealCardFullData($currency,$cardid));
                        if(isset($breakdata->data->balance)){
                        $balance=$breakdata->data->balance;
                        }
                 }else if($supplier==2){
                        $breakdata = json_decode( revealBCCardFullData($currency,$cardid));
                        if(isset($breakdata->data->balance)){
                            $balance=$breakdata->data->balance/100;
                        }
                 }
                
                $allResponse=strval(round($balance,2));
            }
            $maindata['userdata']= $allResponse;
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
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data not found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }
}
    
}
else {
    $errordesc = "Method not allowed";
    $linktosolve = "https://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>