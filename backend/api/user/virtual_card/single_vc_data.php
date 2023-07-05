<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    
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
            
        }
        else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
        
           // check if the current password field was passed 
        if (!isset($_POST['vctid'])|| !isset($_POST['pin']) ) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
             $vctid = cleanme($_POST['vctid']);
                $pin = cleanme($_POST['pin']);
        }
        
        
        $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel FROM users WHERE id=? ");
        $checkdata->bind_param("s", $userid);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
    
    
            // check user pin
        $verifypass =check_pass($pin,$passpin);
        
        if (empty($vctid)||empty($pin)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the track id";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else  if (!$verifypass) {
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Invalid pin.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
        }
        

        
        
        $active=1;
        $mainorsubwallet=2;
        $sqlQuery1 = "SELECT fullname FROM kyc_details WHERE user_id=?";
        $stmt1= $connect->prepare($sqlQuery1);
        $stmt1->bind_param("s",$userid);
        $stmt1->execute();
        $result1= $stmt1->get_result();
        $numRow2 = $result1->num_rows;
        if($numRow2 > 0){
                $users2 = $result1->fetch_assoc();
             $fullname=$users2['fullname'];
        }
           $notdeleted=0;      
        $sqlQuery = "SELECT `json_response`,`vc_card_id`,`vc_type_tid`,`status`, `trackid`, `balance`,`brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, `freeze`,`activated`,`cansetpin` FROM vc_customer_card WHERE user_id=? AND trackid=?  AND deleted=?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sss", $userid,$vctid,$notdeleted);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $users['cardname']="";
                $cardid=$users['vc_card_id'];
                $users['fullname']=$fullname;
                $tid=$users['vc_type_tid'];
                $vc_card_freeze= $users['freeze'];
                if($vc_card_freeze==1){
                                $errordesc="Please unfreeze your card before you can view card details.";
                        $linktosolve="htps://";
                        $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="Please unfreeze your card before you can view card details.";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                }else{
                
                $currency="";
                $supplier=null;
                $sqlQuery1 = "SELECT name,currency,theme,supplier,maintanace_fee,shadow FROM vc_type WHERE trackid=?";
                $stmt1= $connect->prepare($sqlQuery1);
                $stmt1->bind_param("s",$tid);
                $stmt1->execute();
                $result1= $stmt1->get_result();
                $numRow2 = $result1->num_rows;
                if($numRow2 > 0){
                        $users2 = $result1->fetch_assoc();
                        $users['cardname']=$users2['name'];
                        $currency=$users2['currency'];
                        $users['theme'] = $users2['theme'];
                        $users['shadow'] = $users2['shadow'];
                        $supplier=$users2['supplier'];
                         $maintanfee=$users2['maintanace_fee'];
                }
                $users['balance']="***";
                
                $users['street']=$users['city']=$users['country']=$users['postalcode']=$users['state']=$users['state_code']="";
                $breakdata = json_decode($users['json_response']);
                if($supplier==1){
                    $users['street']=$breakdata->data->billingAddress->line1;
                    $users['city']=$breakdata->data->billingAddress->city;
                    $users['country']=$breakdata->data->billingAddress->country;
                    $users['postalcode']=$breakdata->data->billingAddress->postalCode;
                    $users['state']=$breakdata->data->billingAddress->state;
                    $users['state_code']="";
                }else if($supplier==2){
                    $users['street']=$breakdata->data->billing_address->billing_address1;
                    $users['city']=$breakdata->data->billing_address->billing_city;
                    $users['country']=$breakdata->data->billing_address->billing_country;
                    $users['postalcode']=$breakdata->data->billing_address->billing_zip_code;
                    $users['state']=$breakdata->data->billing_address->state;
                    $users['state_code']=$breakdata->data->billing_address->state_code;
                }

                if($supplier==1){           
                        $breakdata = json_decode(revealCardFullData($currency,$cardid));
                        if(isset($breakdata->data->balance)){
                        $users['balance']=round($breakdata->data->balance,2);
                        $users['cvv']=$breakdata->data->cvv;
                        $users['expireMonth']=$breakdata->data->expiryMonth;
                        $expiryYear=$breakdata->data->expiryYear;
                        $currentYear = date('Y');
                        if (strlen($expiryYear) == 2) {
                            $expiryYear = substr($currentYear, 0, 2) . $expiryYear;
                        }
                        $users['expireyear']=$expiryYear;
                        $users['pan']=$breakdata->data->number;
                        }
                 }else if($supplier==2){
                        $breakdata = json_decode( revealBCCardFullData($currency,$cardid));
                        if(isset($breakdata->data->balance)){
                            $users['balance']=round($breakdata->data->balance/100,2);
                            $users['cvv']=$breakdata->data->cvv;
                            $users['expireMonth']=$breakdata->data->expiry_month;
                            $users['expireyear']=$breakdata->data->expiry_year;
                            $users['pan']=$breakdata->data->card_number;
                        }
                 }
                 
                $selectedmonth= date('n');//12
                $selectedyear= date('Y');//2022
                $todaydayis=date('d');
                $donesuccess=1;
                // SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=2 AND MONTH(created_at) = 1 AND YEAR(created_at)=2023 AND DAY(created_at)=19 AND status=1 AND userid=7 GROUP BY userid
                $getexactdata =  $connect->prepare("SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE wallettrackid=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=?  GROUP BY userid");
                $getexactdata->bind_param("sssss", $tid,$selectedmonth,$selectedyear,$donesuccess,$userid);
                $getexactdata->execute();
                $rresult2 = $getexactdata->get_result();
                $totaldone_adayis = $rresult2->num_rows;
                if ($totaldone_adayis>0) {// if user have done 1 trans that mean user have paid maitanance fee
                    $users['maintanace_fee']=0;
                }else{
                     $users['maintanace_fee']=$maintanfee; 
                }
                
                unset($users['vc_card_id']);
                unset($users['json_response']);
                
                array_push($allResponse,json_decode(json_encode($users), true));
            }
                
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