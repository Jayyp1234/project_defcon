<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");

    
    
    include "../../../config/utilities.php";

    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/peerstack/".basename($_SERVER['PHP_SELF']);
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
            if(!isset($_POST['amount'])){
                $errordesc="Amount required";
                $linktosolve="htps://";
                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Input Amount";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else{
                $amount = cleanme($_POST['amount']);
            }
        
        $active=1;
        $sqlQuery = "SELECT * FROM peerstackmerchants WHERE status=? AND active_for_withdraw = ? AND max_withrawl>=? AND active_balance>=? ";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("ssdd",$active,$active,$amount,$amount);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $peeradminid=$users['admin_id'];
                $merchant_trackid = $users['merchant_trackid'];
                $admintotalonline =0;
                $stillactive=2;
                $sysgetdata =  $connect->prepare("SELECT id FROM userwallettrans WHERE peerstack_agent =? AND status=?");
                $sysgetdata->bind_param("ss",$merchant_trackid,$stillactive);
                $sysgetdata->execute();
                $dsysresult = $sysgetdata->get_result();
                if($dsysresult->num_rows <=5){
                    $sysgetdata =  $connect->prepare("SELECT lastonline FROM admin WHERE id =?");
                    $sysgetdata->bind_param("s",$peeradminid);
                    $sysgetdata->execute();
                    $dsysresult = $sysgetdata->get_result();
                    if($dsysresult->num_rows > 0){
                        $gtddata=$dsysresult->fetch_assoc();
                        $duseractivetime= $gtddata['lastonline'];//last time
                        $datediff = time()- $duseractivetime;
                        $difference = round($datediff/60);//getting minute btween
                        // if ($difference <=10) {//if no action in 5 minute den user is off line
                            array_push($allResponse,json_decode(json_encode($users), true));
                        // }
                    }
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
            $text = "Data Empty";
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