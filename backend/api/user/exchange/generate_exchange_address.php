<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";
  
    $endpoint="/api/user/transaction/".basename($_SERVER['PHP_SELF']);
    $method = getenv('REQUEST_METHOD');
if ($method == 'POST') {
        $exchangetid = isset($_POST['exchangetid']) ? cleanme($_POST['exchangetid']) : '';
        $paymentid = isset($_POST['paymentid']) ? cleanme($_POST['paymentid']) : '';
        $currencytid =isset($_POST['currencytid']) ? cleanme($_POST['currencytid']) : '';
        $exchangetype =isset($_POST['exchangetype']) ? cleanme($_POST['exchangetype']) : 0;

               
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
            
        }else if (empty($exchangetid) || empty($paymentid) ||empty($currencytid)) {//checking if data is empty
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
                // get coinproduct, check if its multiple or single, get platform type also, max coin generate
                // if multiple ,check max coin genrategenrate address AND save the address and show in list on sidebar
                // if single genertae address  and form transaction history
                //  get subwallet details
                //  generate address and save in usergenaddress
                $active=1;
                $checkdata =  $connect->prepare("SELECT coinplatform,producttrackid,cointype,	coingentype,maxcoingenerate	 FROM coinproducts WHERE producttrackid=? AND status=?");
                $checkdata->bind_param("ss",$currencytid,$active);
                $checkdata->execute();
                $dresult3 = $checkdata->get_result();
               if ($dresult3->num_rows >0) {
                        $coinsdata =$dresult3->fetch_assoc();
                        $coinplatform=$coinsdata['coinplatform'];
                        $producttrackid =$coinsdata['producttrackid'];
                        $cointype=$coinsdata['cointype'];
                        $coingentype=$coinsdata['coingentype'];
                        $maxcoingenerate=$coinsdata['maxcoingenerate'];
                  
                        
                        //   check if user has the sub wallet
                        $checksubwall =  $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid=?  AND status=?");
                        $checksubwall->bind_param("ss", $exchangetid,$active);
                        $checksubwall->execute();
                        $dsubwallresult= $checksubwall->get_result();
                        if ($dsubwallresult->num_rows >0) {
                                $coinsdata =$dsubwallresult->fetch_assoc();
                                $exchnagename=$coinsdata['name'];
                                $exchangetrackid =$coinsdata['trackid'];
                                
                                $generateadd=false;
                                if($coinplatform==2){
                                    //bg
                                        $allrespnse=[];
                                    //   get all address
                                    //  if yes then get user list of addresses for the subwallet
                                    // $active=1;
                                    // $sqlQuery = "SELECT * FROM exchangegenaddress WHERE userid=? AND cointype=? AND coinprodtrackid=? ORDER BY id DESC LIMIT 1";
                                    // $stmt= $connect->prepare($sqlQuery);
                                    // $stmt->bind_param("sss",$userid,$cointype,$producttrackid);
                                    // $stmt->execute();
                                    // $result= $stmt->get_result();
                                    // $numRow = $result->num_rows;
                                    // if($numRow > 0){
                                    //     $generateadd=true;
                                    // }else{
                                          $generateadd=generateExchangeBGWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangetid,$exchangetype);
                                    // }
                                }else if($coinplatform==3){
                                    //cp
                                    //   $active=1;
                                    // //   checking if user has wallet already, remove this if wallet should be regenerated
                                    // $sqlQuery = "SELECT * FROM exchangegenaddress WHERE userid=? AND cointype=? AND coinprodtrackid=? ORDER BY id DESC LIMIT 1";
                                    // $stmt= $connect->prepare($sqlQuery);
                                    // $stmt->bind_param("sss",$userid,$cointype,$producttrackid);
                                    // $stmt->execute();
                                    // $result= $stmt->get_result();
                                    // $numRow = $result->num_rows;
                                    // if($numRow > 0){
                                    //     $generateadd=true;
                                    // }else{
                                    $generateadd=generateExchangeCPWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangetid,$exchangetype);
                                    // }
                                }else if($coinplatform==4){
                                    //cp
                                    //   $active=1;
                                    // //   checking if user has wallet already, remove this if wallet should be regenerated
                                    // $sqlQuery = "SELECT * FROM exchangegenaddress WHERE userid=? AND cointype=? AND coinprodtrackid=? ORDER BY id DESC LIMIT 1";
                                    // $stmt= $connect->prepare($sqlQuery);
                                    // $stmt->bind_param("sss",$userid,$cointype,$producttrackid);
                                    // $stmt->execute();
                                    // $result= $stmt->get_result();
                                    // $numRow = $result->num_rows;
                                    // if($numRow > 0){
                                    //     $generateadd=true;
                                    // }else{
                                    $generateadd=generateHTWallet($userid,$producttrackid,$coinplatform,$paymentid,$exchangetid,$exchangetype);
                                    // }
                                }else if ($coinplatform==2332321){
                                          $generateadd=generateCBWallet($userid,$producttrackid,$currency,$subwalltrackid,$coinplatform);
                                          if($generateadd!=false){
                                                $allrespnse=[];
                                                // get coin detail $producttrackid
                                                $coinname=getCoinDetails($producttrackid)['name'];
                                                
                                                array_push( $allrespnse,array("address"=>$generateadd,"coinname"=>$coinname));
                                                $maindata['userdata']=  $allrespnse;
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
                                            $errordesc="Bad request";
                                            $linktosolve="https://";
                                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Error generating wallet";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
                                } 
                                
                                if($coinplatform==3||$coinplatform==2||$coinplatform==4){
                                            if($generateadd!=false){
                                                 $allrespnse=[];
                                                //   get all address
                                                //  if yes then get user list of addresses for the subwallet
                                                $active=1;
                                                $sqlQuery = "SELECT * FROM exchangegenaddress WHERE userid=? AND cointype=? AND coinprodtrackid=? ORDER BY id DESC LIMIT 1";
                                                $stmt= $connect->prepare($sqlQuery);
                                                $stmt->bind_param("sss",$userid,$cointype,$producttrackid);
                                                $stmt->execute();
                                                $result= $stmt->get_result();
                                                $numRow = $result->num_rows;
                                                if($numRow > 0){
                                                        while($users = $result->fetch_assoc()){
                                                                $addressis =$users['address'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                $coinmemo=$users['memo'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                
                                                                
                                                                array_push( $allrespnse,array("address"=>$addressis,"coinname"=>$coinname,"memo"=>$coinmemo));
                                                        }
                                                }
                                                
                                                $maindata['userdata']=  $allrespnse;
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
                                                    $errordesc="Bad request";
                                            $linktosolve="https://";
                                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Error generating wallet";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                            }
                                    }
                                    
                        }else{
                            // error max wallet reached
                            $errordesc="Bad request";
                            $linktosolve="https://";
                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Exchange system does not exist";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
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