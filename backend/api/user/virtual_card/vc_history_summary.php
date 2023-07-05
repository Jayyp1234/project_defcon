<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);


include "../../../config/utilities.php";

   // in fund virtual card, // add fund wallet history 
//   store profite in creating card for admin, 
// admin approve processing fund

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
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
        
    
        //collect input and validate it
        // check if the current password field was passed 
        if (!isset($_POST['sort'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
             $sort = cleanme($_POST['sort']);// 1-today 2- week 3 month
        }
        
        if (empty($sort)){
            $errordesc="Insert all fields";
            $linktosolve="htps://";
            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly pass value to the user_id, username field in this register endpoint";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }else{
         
                $totalinflow=0;
                $totaloutflow=0;
                $totaltrans=0;
                if($sort==3){
                        // IN A MONTH
                        $selectedmonth= date('n');//12
                        $selectedyear= date('Y');//2022
                        $todaydayis=date('d');
                        // INFLOW
                        $donesuccess=1;
                        $on=1;
                        $inflow=2;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=? AND transtype=?  GROUP BY userid");
                        $getexactdata->bind_param("ssssss",$on,$selectedmonth,$selectedyear,$donesuccess,$user_id,$inflow);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                                $totalinflow=$ddatasent['totalsent'];
                        }
                        // OUTFLOWO
                        $donesuccess=1;
                        $on=1;
                        $inflow=1;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=? AND transtype=?  GROUP BY userid");
                        $getexactdata->bind_param("ssssss",$on,$selectedmonth,$selectedyear,$donesuccess,$user_id,$inflow);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                               $totaloutflow=$ddatasent['totalsent'];
                        }
                }else if($sort==1){
                        // IN A DAY
                        $selectedmonth= date('n');//12
                        $selectedyear= date('Y');//2022
                        $todaydayis=date('d');
                        // INFLOW
                        $donesuccess=1;
                        $on=1;
                        $inflow=2;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=? AND transtype=? AND DAY(created_at)=?  GROUP BY userid");
                        $getexactdata->bind_param("sssssss",$on,$selectedmonth,$selectedyear,$donesuccess,$user_id,$inflow,$todaydayis);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                                $totalinflow=$ddatasent['totalsent'];
                        }
                        // OUTFLOWO
                        $donesuccess=1;
                        $on=1;
                        $inflow=1;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND  MONTH(created_at) = ? AND YEAR(created_at)=?  AND status=? AND userid=? AND transtype=?  AND DAY(created_at)=?  GROUP BY userid");
                        $getexactdata->bind_param("sssssss",$on,$selectedmonth,$selectedyear,$donesuccess,$user_id,$inflow,$todaydayis);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                               $totaloutflow=$ddatasent['totalsent'];
                        }
                }else if($sort==2){
                        // IN A WEEK
                        $selectedmonth= date('n');//12
                        $selectedyear= date('Y');//2022
                        $todaydayis=date('d');
                        // INFLOW
                        $donesuccess=1;
                        $on=1;
                        $inflow=2;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND (created_at BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW())  AND status=? AND userid=? AND transtype=?   GROUP BY userid");
                        $getexactdata->bind_param("ssss",$on,$donesuccess,$user_id,$inflow);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                                $totalinflow=$ddatasent['totalsent'];
                        }
                        // OUTFLOWO
                        $donesuccess=1;
                        $on=1;
                        $inflow=1;
                        $getexactdata =  $connect->prepare("SELECT SUM(amttopay) AS totalsent FROM userwallettrans WHERE virtual_card_trans=? AND (created_at BETWEEN DATE_SUB(NOW(),INTERVAL 1 WEEK) AND NOW()) AND status=? AND userid=? AND transtype=? GROUP BY userid");
                        $getexactdata->bind_param("ssss",$on,$donesuccess,$user_id,$inflow);
                        $getexactdata->execute();
                        $rresult2 = $getexactdata->get_result();
                        $totaldone_adayis = $rresult2->num_rows;
                        $totaltrans+=$totaldone_adayis;
                        if ($totaldone_adayis>0) {
                                $ddatasent=$rresult2->fetch_assoc();
                               $totaloutflow=$ddatasent['totalsent'];
                        }
                }
                // transtype 1 send 2 receive 	transtype 	virtual_card_trans
             
             
                $maindata['totalinlflow']=strval(round($totalinflow,2));
                $maindata['totaloutflow']=strval(round($totaloutflow,2));
                 $maindata['totaltrans']=strval( $totaltrans);
                $errordesc = " ";
                $linktosolve = "https://";
                $hint = [];
                $errordata = [];
                $method=getenv('REQUEST_METHOD');
                $text = "Data";
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




