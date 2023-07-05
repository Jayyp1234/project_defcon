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

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/pricechart/".basename($_SERVER['PHP_SELF']);
if (getenv('REQUEST_METHOD') === 'POST'){
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
        $user_pubkey = $decodedToken->usertoken;
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
            $user_id = getUserWithPubKey($connect, $user_pubkey);
        }
        
        $key = "e8a870cfb81dc6adb67f481ca4aab7359b1ab7a1429d3a9d5175224d9f65bc40";
        $appid = "Cardify";
        
        
        
        //Currency From 
        if (isset($_POST['cryptotag']) && !empty($_POST['cryptotag']) ){
            $cryptotag = cleanme($_POST['cryptotag']);
        }
        else{
            $cryptotag = "BTC";
        }
        
        //Currency To
        if (isset($_POST['cryptototag'])  && !empty($_POST['cryptototag'])){
            $currency = cleanme($_POST['cryptototag']);
        }
        else{
            $currency = "USD";
        }
        
        //Time
        if (isset($_POST['time']) && !empty($_POST['time']) ){
            $type = cleanme($_POST['time']);
        }
        else{
            $type = "hour";
        }
        
            if ($type == "hour"){
                $url ="https://min-api.cryptocompare.com/data/v2/histominute?aggregate=1&fsym=$cryptotag&tsym=$currency&limit=60&api_key=$key&tryConversion=false&extraParams=$appid";

            }
            else if($type == "daily"){
                $url ="https://min-api.cryptocompare.com/data/v2/histohour?aggregate=1&fsym=$cryptotag&tsym=$currency&limit=24&api_key=$key&tryConversion=false&extraParams=$appid";
            }
            else if($type == "weekly"){
                $url ="https://min-api.cryptocompare.com/data/v2/histoday?aggregate=1&fsym=$cryptotag&tsym=$currency&limit=7&api_key=$key&tryConversion=false&extraParams=$appid";
            }
            else if($type == "monthly"){
                $url ="https://min-api.cryptocompare.com/data/v2/histoday?aggregate=1&fsym=$cryptotag&tsym=$currency&limit=31&api_key=$key&tryConversion=false&extraParams=$appid";
            }
            else{
                $url ="https://min-api.cryptocompare.com/data/v2/histoday?aggregate=1&fsym=$cryptotag&tsym=$currency&limit=365&api_key=$key&tryConversion=false&extraParams=$appid";
            }
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
                $maindata['userdata']=$data->{'Data'};
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




