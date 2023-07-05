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
    $selectedcurrency = isset($_POST['selectedcurrency']) ? cleanme($_POST['selectedcurrency']) : '';
    $selectedexchange = isset($_POST['selectedexchange']) ? cleanme($_POST['selectedexchange']) : '';
    $amount = isset($_POST['amount']) ? cleanme($_POST['amount']) : '';
    
    
    $fail=""; 
    
    
    if (empty($selectedcurrency) || empty($selectedexchange) || empty($amount)) {//checking if data is empty
        $errordesc="Bad request";
        $linktosolve="https://";
        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Please fill all data";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondBadRequest($data);
    } 

    else{
       
        $status=1;
        // generating user pub key
         // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
        $public_key = createUniqueToken(23,"externalinternal_trans","token","",true,true,true);
 
        $insert_data = $connect->prepare("INSERT INTO externalinternal_trans (token,selectedcurrency,amount,selectedexhange) VALUES (?,?,?,?)");
        $insert_data->bind_param("ssss",$public_key,$selectedcurrency, $amount, $selectedexchange);
        if($insert_data->execute()){
            $insert_data->close();
            // generating user access token
            $accesstoken=$public_key;
            $maindata['access_token']=$accesstoken;
            $maindata=[$maindata];
            $errordesc="";
            $linktosolve="https://";
            $hint=[];
            $errordata=[];
            $text="User account created successfully, kindly verify your account...";
            $method=getenv('REQUEST_METHOD');
            $status=true;
            $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        
       } else{
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["problem encountered while trying to send email"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Error creating user. Try again! $insert_data->error";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }
        

    }

} 

else {
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