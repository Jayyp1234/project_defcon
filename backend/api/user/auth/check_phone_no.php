<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)



include "../../../config/utilities.php";

$endpoint="../../api/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') == 'POST') {
    #Get Post Data
    $phone = isset($_POST['phone']) ? cleanme($_POST['phone']) : '';
    
    
    $fail=""; 
    
    $checkdata =  $connect->prepare("SELECT id FROM users WHERE phoneno=? ");
    $checkdata->bind_param("s", $phone);
    $checkdata->execute();
    $dresult3 = $checkdata->get_result();
    
    
    
    if (empty($phone)) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } else if ($dresult3->num_rows >0) {// checking if data is valid
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Phone number already exists.";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    }else{
      
        
        $maindata=[];
        $errordesc="";
        $linktosolve="https://";
        $hint=[];
        $errordata=[];
        $text="Phone number valid";
        $method=getenv('REQUEST_METHOD');
        $status=true;
        $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
        respondOK($data);
        

    }

} else {
    $errordesc="Method not allowed";
    $linktosolve="htps://";
    $hint=["Ensure to use the method stated in the documentation."];
    $errordata=returnError7003($errordesc,$linktosolve,$hint);
    $text="Method used not allowed";
    $method=getenv('REQUEST_METHOD');
    $data=returnErrorArray($text,$method,$endpoint,$errordata);
    respondMethodNotAlowed($data);
}

?>