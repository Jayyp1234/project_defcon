<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    
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
        
        $bindparams=[];
        $bindparamss="";
        
        $bindparams[]=$userid;
        $bindparamss.="s";
        
        $currencytag='';
        if(isset($_POST['currencytag'])){
            $sentcurrencytag=cleanme($_POST['currencytag']);
            $currencytag=" AND  currencytag=?";
            $bindparams[]=$sentcurrencytag;
            $bindparamss.="s";
        }
        
     
        $active=0;
        $sqlQuery = "SELECT currencytag FROM usersubwallet WHERE userid=? $currencytag GROUP BY `currencytag` ";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindparamss",...$bindparams);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            $thusdval=0;
            while($users = $result->fetch_assoc()){
                $walletcurrencytag =$users['currencytag'];
                
                $sqlQuery1 = "SELECT wallettrackid FROM userwallet WHERE userid=? AND currencytag=?";
                $stmt1= $connect->prepare($sqlQuery1);
                $stmt1->bind_param("ss",$userid,$walletcurrencytag);
                $stmt1->execute();
                $result1= $stmt1->get_result();
                $users1 = $result1->fetch_assoc();
                $wallettrackid=$users1['wallettrackid'];
                
                $sqlQuery1 = "SELECT sidebarname FROM currencysystem WHERE currencytag=?";
                $stmt1= $connect->prepare($sqlQuery1);
                $stmt1->bind_param("s",$walletcurrencytag);
                $stmt1->execute();
                $result1= $stmt1->get_result();
                $users1 = $result1->fetch_assoc();
                $sidebarname=$users1['sidebarname'];
                
                
                
                array_push( $allResponse,array("currencytag"=>$walletcurrencytag,"wallettid"=> $wallettrackid,"sidebarname"=> $sidebarname));
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