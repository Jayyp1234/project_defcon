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
    
    include "../../../../config/utilities.php";
  
    $endpoint="/api/user/currency/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
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

        if ( !isset($_POST['country_tid'])||!isset($_POST['category_tid'])||!isset($_POST['voucher_tid'])||strlen($_POST['voucher_tid'])<2 || strlen($_POST['category_tid'])<2) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="All fields must be passed";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
        else{
            $countrytid= cleanme($_POST['country_tid']);
            $category_tid= cleanme($_POST['category_tid']);
            $vouchertidis=cleanme($_POST['voucher_tid']);
        }
        
        
        $bindparams=[];
        $bindparamss="";
        $queryClause ="";
        $limitclause="";
        // sttaus
        $active=1;
        $bindparams[]=$active;
        $bindparamss.="s";
        // main catgeory
        $queryClause .= " AND main_cat_tid=? ";
        $bindparams[]=$category_tid;
        $bindparamss.="s";
                // country
        $queryClause .= " AND sub_cat_tid=? ";
        $bindparams[]=$countrytid;
        $bindparamss.="s";
        
  
        if (isset ($_POST['voucher_tid'])&&!empty($_POST['voucher_tid'])) {
            $searchtagis=cleanme($_POST['voucher_tid']);
            $queryClause .= " AND voucher_tid=?";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        

       
        
        // SUB CAT NI COUNTRY O 
        $sqlQuery = "SELECT name,average_cashback,imglink,general_name,description,redeemption,locations,validity_period,has_prices,average_cashback,min_to_purchase,voucher_tid  FROM bill_voucher_main_prod  WHERE status=?  $queryClause";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindparamss",...$bindparams);
 
        $stmt->execute();
        $result= $stmt->get_result(); 
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                
                // seprarate desc, redeem,locatio n
                // $users['description']=explode(",",$users['description']);
                $users['redeemption']=explode(",",$users['redeemption']);
                $users['locations']=explode(",",$users['locations']);
                
                // get minimum price
                $active=1;
                $getdataemail =  $connect->prepare("SELECT amount FROM bill_voucher_prices WHERE voucher_tid=? AND status=? ORDER BY amount ASC LIMIT 1");
                $getdataemail->bind_param("si",$vouchertidis,$active);
                $getdataemail->execute();
                $getresultemail = $getdataemail->get_result();
                if( $getresultemail->num_rows> 0){
                    $getthedata= $getresultemail->fetch_assoc();
                    $users['min_to_purchase']=$getthedata['amount'];
                }

                array_push($allResponse,json_decode(json_encode($users), true));
            }
            $maindata= $allResponse;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        }  else{
            $allResponse = [];
            $maindata= $allResponse;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data not found";
            $method = getenv('REQUEST_METHOD');
            $status = false;
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