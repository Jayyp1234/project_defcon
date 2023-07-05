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

        if ( !isset($_POST['country_tid'])||!isset($_POST['category_tid']) ) {
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
        
  
        if (isset ($_POST['voucher_cat'])&&!empty($_POST['voucher_cat'])) {
            $searchtagis=cleanme($_POST['voucher_cat']);
            $queryClause .= " AND cat_tid=?";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        
        
        if (isset ($_POST['featured'])&&!empty(trim($_POST['featured']))) {
            $searchtagis=cleanme($_POST['featured']);
            $queryClause .= " AND featured=? ";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        
        if (isset ($_POST['search'])&&!empty(trim($_POST['search']))) {
            $searchtagis=cleanme($_POST['search']);
            $searchtagis== "%{$searchtagis}%";
            $queryClause .= " AND name LIKE ? ";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        
       if (isset ($_POST['hascashback'])&&!empty(trim($_POST['hascashback']))) {
            $queryClause .= " AND average_cashback> ?";
            $bindparams[]=0;
            $bindparamss.="i";
        }
        
        
        if (isset ($_POST['limit'])&&!empty($_POST['limit'])) {
            $searchtagis=cleanme($_POST['limit']);
            $limitclause = " LIMIT ?";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
       
        
        // SUB CAT NI COUNTRY O 
        $sqlQuery = "SELECT name,average_cashback,imglink  FROM bill_voucher_main_prod  WHERE status=?  $queryClause $limitclause";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindparamss",...$bindparams);
 
        $stmt->execute();
        $result= $stmt->get_result(); 
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
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