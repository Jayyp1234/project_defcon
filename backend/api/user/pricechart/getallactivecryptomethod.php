<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/pricechart/".basename($_SERVER['PHP_SELF']);
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
    // $decodedToken = ValidateAPITokenSentIN($servername, $companykey, $method, $endpoint);
    // $user_pubkey = cleanme($decodedToken->usertoken);
         // send error if ur is not in the database
        // if (!getUserWithPubKey($connect, $user_pubkey)){
        //     $errordesc="Bad request";
        //     $linktosolve="htps://";
        //     $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
        //     $text="User is not in the database ensure the user is in the database";
        //     $method=getenv('REQUEST_METHOD');
        //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
        //     respondBadRequest($data);
            
        // }
        // else{
            // $userid = getUserWithPubKey($connect, $user_pubkey);
        
        $active=1;
        $bindParams=array();
        $bindParamTags="s";
        $bindParams[]=$active;
        $searchclause="";
        $typeClause="";
        if(isset($_GET['search'])&&$_GET['search']!=''){
              $search= cleanme($_GET['search']);
              $search="%{$search}%";
              $searchclause=" AND (name LIKE ? || shortname LIKE ?)";
              for($i=1;$i<=2;$i++){
                 $bindParamTags.="s";
                 $bindParams[]=$search;
              }
        }
        if(isset($GET['type'])){
            $typeis= cleanme($_GET['type']);
            $typeClause= " AND (priceapiname = ? OR producttrackid = ?)";
            for($i=1;$i<=2;$i++){
                 $bindParamTags.="s";
                 $bindParams[]=$typeis;
            }
        }
        
        
        
        $sqlQuery = "SELECT producttrackid,name,priceapidetails,priceapiname,rate,colorcode,img,wheretoshow,maxcoingenerate,coingentype,shortname FROM coinproducts WHERE status=? $typeClause $searchclause";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("$bindParamTags",...$bindParams);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                array_push($allResponse,json_decode(json_encode($users), true));
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
// }/
    
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