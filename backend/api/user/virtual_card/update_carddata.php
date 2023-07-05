<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    // header("Cache-Control: no-cache");
    $seconds_to_cache = 100;
    $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
    header("Expires: $ts");
    header("Pragma: cache");
    header("Cache-Control: max-age=$seconds_to_cache");

    
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
            
        } else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
        
            $active=2;
            $mainorsubwallet=2;
            $sqlQuery = "SELECT trackid,currency FROM vc_type WHERE  supplier =? ";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("s",$active);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){
                $allResponse = [];
                while($users = $result->fetch_assoc()){
                        $trackid=$users['trackid'];
                        $currency=$users['currency'];
                        $notdeleted=0;
                        $sqlQuery1 = "SELECT * FROM vc_customer_card WHERE user_id=? AND vc_type_tid = ? AND deleted=? ORDER BY id DESC LIMIT 4";
                        $stmt3= $connect->prepare($sqlQuery1);
                        $stmt3->bind_param("sss", $userid,$trackid,$notdeleted);
                        $stmt3->execute();
                        $result2= $stmt3->get_result();
                        $numRow1 = $result2->num_rows;
                        if($numRow1 > 0){
                             $allResponse1 = [];
                             while($users3 = $result2->fetch_assoc()){
                                 $cardptid=$users3['trackid'];
                                //  419292*******44566
                                    $customerid=$users3['customer_id'];
                                    $cardid=$users3['vc_card_id'];
                                    $waletid=$users3['wallet_id'];
                                    $txt =$users3['pan'];
                                    
                                    if(strpos($txt,"419292*******")  !== false){
                                        $vc_data=GetActiveBCVirtualCardApi($currency);
                                        $success=false;
                                        $authkey=$vc_data['authkey'];
                                        $secretekey=$vc_data['secretekey'];
                                        $issueid=$vc_data['issueid'];
                                        $baseurl=$vc_data['baseurl']; 
                                        $relay_url=$vc_data['relay_url'];
                                        $currency=$vc_data['currency']; 
                                        
                                    
                                         //  check if customer exist
                                         $zero=0;
                                         $checkdata =  $connect->prepare("SELECT trackid,user_id,brand FROM vc_customer_card  WHERE customer_id=?  AND vc_card_id=? AND wallet_id=?");
                                         $checkdata->bind_param("sss", $customerid,$cardid,$waletid);
                                         $checkdata->execute();
                                         $dresult = $checkdata->get_result();
                                         if ($dresult->num_rows>0) {
                                               // get customer card trckid
                                                $getsys =$dresult->fetch_assoc();
                                                $user_trackid =  $getsys['trackid'];
                                                $user_id =  $getsys['user_id'];
                                                $cardbrand= $getsys['brand'];
                                       
                                               
                                         
                                                // GET CARD DETAILS API
                                                $url ="$relay_url/cards/get_card_details?card_id=$cardid";
                                                $curl = curl_init();
                                                curl_setopt_array(
                                                $curl, array(
                                                CURLOPT_URL => $url,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => "",
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 60,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => "GET",
                                                CURLOPT_HTTPHEADER => array(
                                                "token: Bearer  $authkey",
                                                "content-type: application/json",
                                                'accept: application/json',
                                                ),
                                                )); 
                                                $userdetails = curl_exec($curl);
                                                      $err = curl_error($curl);
                                                // print_r($userdetails);
                                                // print(   $url);
                                                $breakdata = json_decode($userdetails);
                                                if(isset($breakdata->status) && $breakdata->status== "success"){
                                                        $walletid=$breakdata->data->issuing_app_id;
                                                        $last4=$breakdata->data->last_4;
                                                        $cvv="***";
                                                        $maskedPan=$breakdata->data->card_number;
                                                        $expiryMonth=$breakdata->data->expiry_month;
                                                        if(strlen($expiryMonth)==1){
                                                            $expiryMonth="0$expiryMonth";
                                                        }
                                                        $expiryYear=$breakdata->data->expiry_year;
                                                   
                                                        $maskedPan=substr_replace($maskedPan,"*",6,6);
                                                        $breakitup=explode("*",$maskedPan);
                                                        $maskedPan=$breakitup[0]."******".$breakitup[1];
                                                        //  $expiryYear=substr_replace($expiryYear,"",0,2);
                                    
                                                         // GET JSON FORMAT OF CARD DETAILS ENCRYPTED
                                                        $url ="$baseurl/cards/get_card_details?card_id=$cardid";
                                                        $curl = curl_init();
                                                        curl_setopt_array(
                                                        $curl, array(
                                                        CURLOPT_URL => $url,
                                                        CURLOPT_RETURNTRANSFER => true,
                                                        CURLOPT_ENCODING => "",
                                                        CURLOPT_MAXREDIRS => 10,
                                                        CURLOPT_TIMEOUT => 30,
                                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                        CURLOPT_CUSTOMREQUEST => "GET",
                                                        CURLOPT_HTTPHEADER => array(
                                                            "token: Bearer  $authkey",
                                                            "content-type: application/json",
                                                            'accept: application/json',
                                                        ),
                                                        ));
                                                        $userdetails = curl_exec($curl);
                                                        $activated=0;
                                                        $insert_data = $connect->prepare("UPDATE vc_customer_card SET json_response=?,last4=?,cvv=?,pan=?,expireMonth=?,expireyear=?,deleted=?  WHERE customer_id=?  AND vc_card_id=? AND wallet_id=?");
                                                        $insert_data->bind_param("ssssssssss",$userdetails,$last4, $cvv,$maskedPan,$expiryMonth,$expiryYear,$activated, $customerid,$cardid,$waletid);
                                                        $insert_data->execute();
                                                        
                                                }
                                                             }
                                }
                         
                             }
                         }
                }
            }
    
        }
}else {
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