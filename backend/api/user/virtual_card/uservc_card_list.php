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
if ($method == 'GET') {
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
        
            $active=1;
            $mainorsubwallet=2;
            $sqlQuery = "SELECT * FROM vc_type WHERE is_show_status =? ";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("s",$active);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){
                $allResponse = [];
                while($users = $result->fetch_assoc()){
                        //Getting Track Id for each specific Category
                        $trackid = $users['trackid'];
                        $cardname = $users['name'];
                        $theme = $users['theme'];
                        $shadow = $users['shadow'];
                        $maintanfee=$users['maintanace_fee'];
                         $supplier=$users['supplier'];
                          $currency=$users['currency'];
                        $users['second_fund_fee']=$users['second_fund_fee']+0;
                        
                        //Appending All Users Cards in each Categories...
                        $active=1;
                        $mainorsubwallet=2;
                        $notdeleted=0;
                        $sqlQuery1 = "SELECT fullname FROM kyc_details WHERE user_id=?";
                        $stmt3= $connect->prepare($sqlQuery1);
                        $stmt3->bind_param("s",$userid);
                        $stmt3->execute();
                        $result3= $stmt3->get_result();
                        $numRow2 = $result3->num_rows;
                        if($numRow2 > 0){
                             $users2 = $result3->fetch_assoc();
                             $fullname=$users2['fullname'];
                        }
                        
                        $sqlQuery1 = "SELECT `vc_card_id`,`vc_type_tid`,`status`, `trackid`, `balance`,`brand`, `last4`, `cvv`, `pan`, `expireMonth`, `expireyear`, `freeze`,`activated`,`cansetpin` FROM vc_customer_card WHERE user_id=? AND vc_type_tid = ? AND deleted=?";
                        $stmt3= $connect->prepare($sqlQuery1);
                        $stmt3->bind_param("sss", $userid,$trackid,$notdeleted);
                        $stmt3->execute();
                        $result2= $stmt3->get_result();
                        $numRow1 = $result2->num_rows;
                        if($numRow1 > 0){
                             $allResponse1 = [];
                             while($users3 = $result2->fetch_assoc()){
                                 $cardptid=$users3['trackid'];
                                // check if its first time fund in a month if yes collect maintan fee
                                // count sum user sent a day
                                $selectedmonth= date('n');//12
                                $selectedyear= date('Y');//2022
                                $todaydayis=date('d');
                                $donesuccess=1;
                                // SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE send_type=2 AND MONTH(created_at) = 1 AND YEAR(created_at)=2023 AND DAY(created_at)=19 AND status=1 AND userid=7 GROUP BY userid
                                $getexactdata =  $connect->prepare("SELECT SUM(btcvalue) AS totalsent FROM userwallettrans WHERE wallettrackid=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=?  GROUP BY userid");
                                $getexactdata->bind_param("sssss", $cardptid,$selectedmonth,$selectedyear,$donesuccess,$userid);
                                $getexactdata->execute();
                                $rresult2 = $getexactdata->get_result();
                                $totaldone_adayis = $rresult2->num_rows;
                                if ($totaldone_adayis>0) {// if user have done 1 trans that mean user have paid maitanance fee
                                    $users3['maintanace_fee']=0;
                                }else{
                                     $users3['maintanace_fee']=$maintanfee; 
                                }
                                $users3['holdbalance']=0;
                                // if($supplier==1){   
                                //     $cardid=$users3['vc_card_id'];
                                //     $breakdata = json_decode(revealCardFullData($currency,$cardid));
                                //     if(isset($breakdata->data->balance)){
                                //         $users3['holdbalance']=$breakdata->data->balance;
                                //     }
                                // }else if($supplier==2){
                                //       $cardid=$users3['vc_card_id'];
                                //     $breakdata = json_decode( revealBCCardFullData($currency,$cardid));
                                //     if(isset($breakdata->data->balance)){
                                //         $users3['holdbalance']=$breakdata->data->balance/100;
                                //     }
                                // }
                        
                              unset($users3['vc_card_id']);
                                $users3['cardname']=$cardname;
                                $users3['fullname']=$fullname;
                                $users3['balance']="***";
                                $users3['street']="";
                                $users3['city']="";
                                $users3['country']="";
                                $users3['postalcode']="";
                                $users3['state']="";
                                $users3['theme']= $theme;
                                $users3['shadow']= $shadow;
                                 $users3['expireyear']=substr_replace($users3['expireyear'],"",0,2);
                                 array_push($allResponse1,json_decode(json_encode($users3), true));
                                 $users['cards'] = $allResponse1;
                             }
                         }
                        else{
                             $users['cards'] = array();
                        }
                        array_push($allResponse,json_decode(json_encode($users), true));
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
            }  else{
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