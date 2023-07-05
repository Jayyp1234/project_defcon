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
        $cryptotrackid = isset($_POST['cryptotrackid']) ? cleanme($_POST['cryptotrackid']) : '';
        $currency = isset($_POST['currency']) ? cleanme($_POST['currency']) : '';
        $addressname =isset($_POST['addressname']) ? cleanme($_POST['addressname']) : '';
          $subwalltrackid=isset($_POST['wallettid']) ? cleanme($_POST['wallettid']) : ''; 
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
            
        }else if (empty( $cryptotrackid) || empty($currency) ||empty($addressname)||empty($subwalltrackid)) {//checking if data is empty
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Please fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else if (strlen($addressname)>10) {//checking if data is empty
            $errordesc="Bad request";
            $linktosolve="https://";
            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Name cannot be more than 10";
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
                $checkdata =  $connect->prepare("SELECT coinplatform,producttrackid,cointype,	coingentype,maxcoingenerate,subwallettag	 FROM coinproducts WHERE producttrackid=? AND status=?");
                $checkdata->bind_param("ss", $cryptotrackid,$active);
                $checkdata->execute();
                $dresult3 = $checkdata->get_result();
               if ($dresult3->num_rows >0) {
                        $coinsdata =$dresult3->fetch_assoc();
                        $coinplatform=$coinsdata['coinplatform'];
                        $producttrackid =$coinsdata['producttrackid'];
                        $cointype=$coinsdata['cointype'];
                        $coingentype=$coinsdata['coingentype'];
                        $maxcoingenerate=$coinsdata['maxcoingenerate'];
                         $subwallettag = $coinsdata['subwallettag'];
                        //  check if user have the address, if its multiple genartor else generate and show address
                        //  if yes get the address and send
                        //  if user dont have call the genarator
                        
                                      // multiple address allowed and can be used any time
                                if( $coingentype==2){//multiple address
                                 
                                
                                
                                  
                                    $sqlQuery = "SELECT * FROM  usergenaddress WHERE userid=? AND currencytag=?  AND subcurrencytrackid=?  AND coinprodtrackid=? ";
                                    $stmt= $connect->prepare($sqlQuery);
                                    $stmt->bind_param("ssss",$userid,$currency,$subwalltrackid,$producttrackid);
                                    $stmt->execute();
                                    $result= $stmt->get_result();
                                    $subwalletcount= $result->num_rows;
                                      // check max coin generate , activate this when multiple coin generation is alowed
                                    // if($subwalletcount>=$maxcoingenerate&&$maxcoingenerate!=0){
                                    //     // error max wallet reached
                                    //     $errordesc="Bad request";
                                    //     $linktosolve="https://";
                                    //     $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                    //     $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                    //     $text="You have reached the maximum amount of address you can generate for this Coins";
                                    //     $method=getenv('REQUEST_METHOD');
                                    //     $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                    //     respondBadRequest($data);
                                     // check if user already hv address
                                    if($subwalletcount>0){
                                             $allrespnse=[];
                                                //   get all address
                                                        while($users = $result->fetch_assoc()){
                                                                $addressis =$users['address'];
                                                                $addressname =$users['name'];
                                                                $addressmemo=$users['memo'];
                                                                $currencytagis=$users['currencytag'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
                                                                array_push( $allrespnse,array("name"=>$addressname,"address"=>$addressis,"currency"=>$currencyname,"cointoshow"=>$coinname,"memo"=>$addressmemo));
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
                                        if($coinplatform==2){
                                            //bg
                                         $generateadd=generateBGWallet($userid,$producttrackid,$currency,$subwalltrackid,$coinplatform,$addressname);
                                          if($generateadd!=false){
                                                     $allrespnse=[];
                                                //   get all address
                                                //  if yes then get user list of addresses for the subwallet
                                                $active=1;
                                                $sqlQuery = "SELECT * FROM  usergenaddress WHERE userid=? AND currencytag=?  AND subcurrencytrackid=?  AND coinprodtrackid=? ";
                                                $stmt= $connect->prepare($sqlQuery);
                                                $stmt->bind_param("ssss",$userid,$currency,$subwalltrackid,$producttrackid);
                                                $stmt->execute();
                                                $result= $stmt->get_result();
                                                $numRow = $result->num_rows;
                                                if($numRow > 0){
                                                        while($users = $result->fetch_assoc()){
                                                                $addressis =$users['address'];
                                                                $addressmemo=$users['memo'];
                                                                $addressname =$users['name'];
                                                                $currencytagis=$users['currencytag'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
                                                                array_push( $allrespnse,array("name"=>$addressname,"address"=>$addressis,"currency"=>$currencyname,"cointoshow"=>$coinname,"memo"=>$addressmemo));
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
                                            
                                        }else if($coinplatform==3){
                                            //cp
                                         $generateadd=generateCPWallet($userid,$producttrackid,$currency,$subwalltrackid,$coinplatform,$addressname);
                                          if($generateadd!=false){
                                                 $allrespnse=[];
                                                //   get all address
                                                //  if yes then get user list of addresses for the subwallet
                                                $active=1;
                                                $sqlQuery = "SELECT * FROM  usergenaddress WHERE userid=? AND currencytag=?  AND subcurrencytrackid=?  AND coinprodtrackid=? ";
                                                $stmt= $connect->prepare($sqlQuery);
                                                $stmt->bind_param("ssss",$userid,$currency,$subwalltrackid,$producttrackid);
                                                $stmt->execute();
                                                $result= $stmt->get_result();
                                                $numRow = $result->num_rows;
                                                if($numRow > 0){
                                                        while($users = $result->fetch_assoc()){
                                                                $addressis =$users['address'];
                                                                $addressmemo=$users['memo'];
                                                                $addressname =$users['name'];
                                                                $currencytagis=$users['currencytag'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
                                                                array_push( $allrespnse,array("name"=>$addressname,"address"=>$addressis,"currency"=>$currencyname,"cointoshow"=>$coinname,"memo"=>$addressmemo));
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
                                        }else if($coinplatform==4){
                                            //cp
                                            $generateadd=generate_HT_deposit_wallet($producttrackid,$userid,$currency,$subwalltrackid,$coinplatform,$addressname);
                                          if($generateadd!=false){
                                                 $allrespnse=[];
                                                //   get all address
                                                //  if yes then get user list of addresses for the subwallet
                                                $active=1;
                                                $sqlQuery = "SELECT * FROM  usergenaddress WHERE userid=? AND currencytag=?  AND subcurrencytrackid=?  AND coinprodtrackid=? ";
                                                $stmt= $connect->prepare($sqlQuery);
                                                $stmt->bind_param("ssss",$userid,$currency,$subwalltrackid,$producttrackid);
                                                $stmt->execute();
                                                $result= $stmt->get_result();
                                                $numRow = $result->num_rows;
                                                if($numRow > 0){
                                                        while($users = $result->fetch_assoc()){
                                                                $addressis =$users['address'];
                                                                $addressmemo=$users['memo'];
                                                                $addressname =$users['name'];
                                                                $currencytagis=$users['currencytag'];
                                                                $coinprodtrackidis=$users['coinprodtrackid'];
                                                                //   get all addresses
                                                                // get coin detail $producttrackid
                                                                $coinname=getCoinDetails($coinprodtrackidis)['name'];
                                                                // get currency details $currency
                                                                $currencyname=getCurrencyDetails( $currencytagis)['name'];
                                                                
                                                                array_push( $allrespnse,array("name"=>$addressname,"address"=>$addressis,"currency"=>$currencyname,"cointoshow"=>$coinname,"memo"=>$addressmemo));
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
                                        $text="Error generating wallet, try again later";
                                        $method=getenv('REQUEST_METHOD');
                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                        respondBadRequest($data);
                                        }
                                        }else{
                                            $errordesc="Bad request";
                                            $linktosolve="https://";
                                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Coin type not detected";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
                                    }
                                    
                                    
                                    
                                }else{//single address allowed,nd usage is once
                                     //generate coin address
                                        if($coinplatform==1){//cb
                                            $generateadd=generateCBWallet($userid,$producttrackid,$currency,$subwalltrackid,$coinplatform);
                                          if($generateadd!=false){
                                              $allrespnse=[];
                                              
                                                // get coin detail $producttrackid
                                                $coinname=getCoinDetails($producttrackid)['name'];
                                                // get currency details $currency
                                                $currencyname=getCurrencyDetails($currency)['name'];
                                              
                                                array_push( $allrespnse,array("address"=>$generateadd,"currency"=>$currencyname,"cointoshow"=>$coinname));
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
                                        }else{
                                            $errordesc="Bad request";
                                            $linktosolve="https://";
                                            $hint=["Data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Coin type not detected";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
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