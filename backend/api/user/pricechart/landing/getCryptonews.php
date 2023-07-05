<?php
 // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)

    include "../../../../apifunctions.php";
    include "../../../../config/connectdb.php";
    include "../../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../../api/user/pricechart/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') === 'POST'){
        
        
        $secret_key = "prod_sk_8gWaxwBLne65ihjqyQh9XrEHT";
        $appid = "63121f44fc538e00354ecf05";
        
        if (isset($_POST['cryptotag'])){
            $cryptotag = cleanme($_POST['cryptotag']);
        }
        else{
            $cryptotag = "BTC";
        }
        
        $url ="https://min-api.cryptocompare.com/data/v2/news/?lang=EN&categories=$cryptotag&sortOrder=latest&extraParams=Cardify";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                //u change the url infront based on the request u want
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //change this based on what u need post,get etc
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "authorization: $secret_key",
                    "appid: $appid",
                    "cache-control: no-cache"
                ),
            ));
            $userdetails = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $refcode="";
                // throw new \Exception("Error getting bank names: $err");
                $errordesc="Unauthorized";
                $linktosolve="https://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text= $err;
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            } 
            else {
                $data = json_decode($userdetails);
                $maindata['userdata']=$data;
                $errordesc = " ";
                $linktosolve = "https://";
                $hint = [];
                $errordata = [];
                $method=getenv('REQUEST_METHOD');
                $text = "Identity Verification Successful.";
                $status = true;
                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                respondOK($data);
            }

}else {
        //method not allowed
        $errordesc="Method not allowed";
        $linktosolve="htps://";
        $hint=["Ensure to use the method stated in the documentation."];
        $errordata=returnError7003($errordesc,$linktosolve,$hint);
        $text="Method used not allowed";
        $method=getenv('REQUEST_METHOD');
        $data=returnErrorArray($text,$method,$endpoint,$errordata);
        respondMethodNotAlowed($data);
    }




