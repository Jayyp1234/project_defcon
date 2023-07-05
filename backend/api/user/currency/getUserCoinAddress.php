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
        $cryptotrackid = isset($_POST['cryptotrackid']) ? cleanme($_POST['cryptotrackid']) : '';
        $currency = isset($_POST['currency']) ? cleanme($_POST['currency']) : '';
        
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
            
        }else if (empty( $cryptotrackid) || empty($currency) ) {//checking if data is empty
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Please fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }  else{
                $userid = getUserWithPubKey($connect, $user_pubkey);
                $active=1;
                $checkdata =  $connect->prepare("SELECT coinplatform,producttrackid,cointype,subwallettag FROM coinproducts WHERE producttrackid=? AND status=?");
                $checkdata->bind_param("si", $cryptotrackid,$active);
                $checkdata->execute();
                $dresult3 = $checkdata->get_result();
               if ($dresult3->num_rows >0) {
                        $coinsdata =$dresult3->fetch_assoc();
                        $coinplatform=$coinsdata['coinplatform'];
                        $producttrackid =$coinsdata['producttrackid'];
                        $cointype=$coinsdata['cointype'];
                        $subwallettag = $coinsdata['subwallettag'];
                        //   check if user has the sub wallet
                        $checksubwall =  $connect->prepare("SELECT 	trackid,coinplatform FROM usersubwallet WHERE currencytag=? AND coinsystemtag=? AND userid=?");
                        $checksubwall->bind_param("sss", $currency, $subwallettag,$userid );
                        $checksubwall->execute();
                        $dsubwallresult= $checksubwall->get_result();
                        if ($dsubwallresult->num_rows >0) {
                                $coinsdata =$dsubwallresult->fetch_assoc();
                                $coinplatform=$coinsdata['coinplatform'];
                                $subwalltrackid =$coinsdata['trackid'];
                                
                                 //  if yes then get user list of addresses for the subwallet
                                $active=1;
                                // coinprodtrackid is used so as to select exactly the activated system,
                                $sqlQuery = "SELECT * FROM  usergenaddress WHERE userid=? AND currencytag=? AND subcurrencytrackid=? AND coinprodtrackid=? ";
                                $stmt= $connect->prepare($sqlQuery);
                                $stmt->bind_param("ssss",$userid,$currency,$subwalltrackid,$producttrackid);
                                $stmt->execute();
                                $result= $stmt->get_result();
                                $numRow = $result->num_rows;
                                if($numRow > 0){
                                        $allResponse = [];
                                        while($users = $result->fetch_assoc()){
                                                         $addressis =$users['address'];
                                                                $addressname =$users['name'];
                                                                $currencytagis=$users['currencytag'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
                                                                array_push($allResponse,array("name"=>$addressname,"address"=>$addressis,"currency"=>$currencyname,"cointoshow"=>$coinname));
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
                                }else{
                                    $maindata['userdata']= [];
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
                                 
                        }
                        else{
                            //  if no then generate sub wallet  then get user list of addresses for the subwallet
                       
                            // // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            // $trackcode=createUniqueToken(5,"usersubwallet","trackid","",true,true,false);
                            // $insert_data = $connect->prepare("INSERT INTO usersubwallet (currencytag,trackid,userid,coinplatform,coinsystrackid,coinsystemtag) VALUES (?,?,?,?,?,?)");
                            // $insert_data->bind_param("ssssss", $currency,$trackcode,$userid,$coinplatform,$producttrackid,$subwallettag);
                            // $insert_data->execute();
                            // $insert_data->close();
                            
                            
                            
                            $maindata['userdata']= [];
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
            
               }else{
                    $errordesc="Bad request";
                    $linktosolve="https://";
                    $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Coin does not exists.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
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