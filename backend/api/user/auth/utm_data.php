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
    $utm_source = isset($_POST['utm_source'])&& !empty($_POST['utm_source']) ? cleanme($_POST['utm_source'],1) : '';
    $utm_medium = isset($_POST['utm_medium'])&& !empty($_POST['utm_medium']) ? cleanme($_POST['utm_medium'],1) : '';
    $utm_campaign = isset($_POST['utm_campaign']) && !empty($_POST['utm_campaign']) ? cleanme($_POST['utm_campaign'],1) : '';
    if(!empty($utm_source)&&!empty($utm_medium)&&!empty($utm_campaign)){
        $ipaddress= getIp();
        $location = getLoc($ipaddress);
        $browser = ' '.getBrowser()['name'].' on '.ucfirst(getBrowser()['platform']);
        //Put sessioncode inside database
        $dateloggedin= time();
        $insert_data = $connect->prepare("INSERT INTO campaign_channel (`utm_source`, `utm_medium`, `utm_campaign`, `ipaddress`, `browser`, `location`, `date`) VALUES (?,?,?,?,?,?,?)");
        $insert_data->bind_param("sssssss",$utm_source, $utm_medium, $utm_campaign, $ipaddress,$browser,$location,$dateloggedin);
        if($insert_data->execute()){
        $insert_data->close();
        
      
        $maindata=[];
        $errordesc="";
        $linktosolve="https://";
        $hint=[];
        $errordata=[];
        $text="Done";
        $method=getenv('REQUEST_METHOD');
        $status=true;
        $data=returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
        respondOK($data);
        
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