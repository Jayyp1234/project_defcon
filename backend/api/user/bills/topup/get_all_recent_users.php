<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
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

        if ( !isset($_POST['mainprod_tid']) ) {
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
            $mainprod_tid= cleanme($_POST['mainprod_tid']);
        }
        
        
         $bindparams=[];
        $bindparamss="";
        $queryClause ="";
        $limitclause="";
        // sttaus
        $active=1;
        $bindparams[]=$active;
        $bindparamss.="i";
                // users
        $bindparams[]=$userid;
        $bindparamss.="i";
        // main catgeory
        $queryClause .= " AND bill_main_prodtid=? ";
        $bindparams[]=$mainprod_tid;
        $bindparamss.="s";
        
        if (isset ($_POST['limit'])&&!empty($_POST['limit'])) {
            $searchtagis=cleanme($_POST['limit']);
            $limitclause = " LIMIT ?";
            $bindparams[]=$searchtagis;
            $bindparamss.="s";
        }
        
        // SUB CAT NI COUNTRY O 
        $sqlQuery = "SELECT bill_product_no FROM userwallettrans WHERE status=? AND userid=? $queryClause GROUP BY bill_product_no $limitclause";
         $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindparamss",...$bindparams);
 
        $stmt->execute();
        $result= $stmt->get_result(); 
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $users['phoneno']=$users['bill_product_no'];
                unset($users['bill_product_no']);
                array_push($allResponse,json_decode(json_encode($users), true));
            }
            $maindata['resdata']= $allResponse;
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
            $maindata['resdata']= $allResponse;
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