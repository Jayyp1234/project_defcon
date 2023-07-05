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
    $user_pubkey = $decodedToken->usertoken;
    $bvn="";
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
            
        }    else{
            $userid = getUserWithPubKey($connect, $user_pubkey);
            $getUser = $connect->prepare("SELECT * FROM users WHERE id = ?");
            $getUser->bind_param("s",$userid);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                //user exist
                $row = $result->fetch_assoc();
                $email = $row['email'];
                $username = $row['username'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $phoneno=$phone = $row['phoneno'];
            }
            
            // get first name and last name from bvn
            $getUser = $connect->prepare("SELECT bvn,fname,lname FROM `kyc_details` WHERE user_id = ?");
            $getUser->bind_param("s",$userid);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                //user exist
                $row = $result->fetch_assoc();
                $bvn = $row['bvn'];
                $fname = $row['fname'];
                $lname = $row['lname'];
            }else{
                $errordesc="Bad request";
                $linktosolve="htps://";
                $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="BVN not found, please upgrade to level 2";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
        }
        
        
        //To Get Active 
        $query = 'SELECT * FROM `systemsettings` WHERE id=1';
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $row =  mysqli_fetch_assoc($result);
        $active = $row['activebanksystem']; //2 monify 3 1app 4 sh
    
      
        
        # This is Check from the database to see if user has created any bank account 
        $activestatus=1;
        $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE userid = ? AND status=? AND banktypeis =?";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("sii",$userid,$activestatus,$active);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            //  put this out because in future admin may want to activate both 1 app and monify, if activated, just remove the if(active ) code from all code below
            $allResponse = [];
            //  if active system is monify below gets all the banks related to monify
            if($active==2){
                $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banktypeis = ? AND userid = ?  AND status=?";
                $stmt= $connect->prepare($sqlQuery);
                $stmt->bind_param("ssi",$active,$userid,$activestatus);
                $stmt->execute();
                $result= $stmt->get_result();
                $numRow = $result->num_rows;
                if($numRow > 0){
                    while($users = $result->fetch_assoc()){
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
                }
            }
               // if($active==4){
            $active=4;
                $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banktypeis = ? AND userid = ?  AND status=?";
                $stmt= $connect->prepare($sqlQuery);
                $stmt->bind_param("ssi",$active,$userid,$activestatus);
                $stmt->execute();
                $result= $stmt->get_result();
                $numRow = $result->num_rows; 
                if($numRow > 0){
                    while($users = $result->fetch_assoc()){
                        $theaccid=$users['id'];
                        // check if it has expire and regenerate
                         $users['acctname']=str_replace("THECARDIFYCOMPA /","",$users['acctname']); 
                         $users['percent']=0;
                            array_push($allResponse,json_decode(json_encode($users), true));
                    }
                        // $maindata['userdata']= $allResponse;
                        // $errordesc = "";
                        // $linktosolve = "https://";
                        // $hint = [];
                        // $errordata = [];
                        // $text = "Data found";
                        // $method = getenv('REQUEST_METHOD');
                        // $status = true;
                        // $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                        // respondOK($data);
                }
            // }
            // if active system is 1app, below system gets all banks account with 1app based on active bank type either kuda or providus or the two if active
            // if($active==3){
                    $active=3;
                    $sqlQuery = "SELECT * FROM `oneappbankgenerate` WHERE status = 1";
                    $stmt= $connect->prepare($sqlQuery);;
                    $stmt->execute();
                    $result= $stmt->get_result();
                    $numRow = $result->num_rows; 
                    if($numRow > 0){
                        while($users = $result->fetch_assoc()){
                            $bankcode = $users['code'];
                            $bankname = $users['name'];
                            $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banksystemtype = ? AND userid = ? AND banktypeis = ?  AND status=?";
                            $stmt= $connect->prepare($sqlQuery);
                            $stmt->bind_param("sssi",$bankcode,$userid,$active,$activestatus);
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                            if($numRow > 0){
                                while($users = $result->fetch_assoc()){
                                    $users['acctname']=str_replace("1APP","",$users['acctname']);
                                    $users['acctname']=str_replace("(","",$users['acctname']);
                                     $users['acctname']=str_replace(")","",$users['acctname']);
                                     $users['acctname']=str_replace("Saver Co-","",$users['acctname']);
                                      $users['percent']=0.5;
                                    array_push($allResponse,json_decode(json_encode($users), true));
                                }
                            }else{
                                //  in case admin activated another bank type which user have not generated before, system auto genrate it with below
                                oneappgenerateAccNumber($fname,$lname,$email,$phone,$bvn,$bankcode,$bankname,$userid);
                                
                                $sqlQuery = "SELECT * FROM `oneappbankgenerate` WHERE status = 1";
                                $stmt= $connect->prepare($sqlQuery);;
                                $stmt->execute();
                                $result= $stmt->get_result();
                                $numRow = $result->num_rows;
                                if($numRow > 0){
                                    while($users = $result->fetch_assoc()){
                                        $bankcode = $users['code'];
                                        $bankname = $users['name'];
                                        $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banksystemtype = ? AND userid = ? AND banktypeis = ?  AND status=?";
                                        $stmt= $connect->prepare($sqlQuery);
                                        $stmt->bind_param("sssi",$bankcode,$userid,$active,$activestatus);
                                        $stmt->execute();
                                        $result= $stmt->get_result();
                                        $numRow = $result->num_rows;
                                        if($numRow > 0){
                                            while($users = $result->fetch_assoc()){
                                                $users['percent']=0.5;
                                                array_push($allResponse,json_decode(json_encode($users), true));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
            // }
         
            
             //  put this out because in future admin may want to activate both 1 app and monify, if activated, just remove the if(active ) code from all code above
            $maindata['userdata']= $allResponse;
            $errordesc = "";
            $linktosolve = "https://";
            $hint = [];
            $errordata = [];
            $text = "Data foundq";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);
        } else{
            // if user does not have any active bank account, generate the bank account for the user
            //  if active bank is monify generate monify
            if ($active == 2 || $active == '2'){
                $status = monifygenerateAccNumber($fname,$lname,$email,2,$userid);
                if($status){
                    $allResponse=[];
                    //   get all bank list
                    $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banktypeis = ? AND userid = ?  AND status=?";
                    $stmt= $connect->prepare($sqlQuery);
                    $stmt->bind_param("ssi",$active,$userid,$activestatus);
                    $stmt->execute();
                    $result= $stmt->get_result();
                    $numRow = $result->num_rows;
                    if($numRow > 0){
                        while($users = $result->fetch_assoc()){
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
                    }
                }else{
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Error Generating Bank Account";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            }
            else if ($active == 3 || $active == '3'){
                //  if active system is 1app , generate bnak account
                if ($bvn != "" || !empty(trim($bvn))){// if user is now in level 2 proceed
                        $sqlQuery = "SELECT * FROM `oneappbankgenerate` WHERE status = 1";
                        $stmt= $connect->prepare($sqlQuery);;
                        $stmt->execute();
                        $result= $stmt->get_result();
                        $numRow = $result->num_rows;
                        $count = 0;
                        if($numRow > 0){
                            $allResponse = [];
                            while($users = $result->fetch_assoc()){
                                $bankcode = $users['code'];
                                $bankname = $users['name'];
                                $status = oneappgenerateAccNumber($fname,$lname,$email,$phone,$bvn,$bankcode,$bankname,$userid);
                                if ($status ==true){
                                    $count = $count + 1;
                                }
                            }
                        }
                        if($count > 0){
                            // /get all generate bank account
                            $sqlQuery = "SELECT * FROM `oneappbankgenerate` WHERE status = 1";
                            $stmt= $connect->prepare($sqlQuery);;
                            $stmt->execute();
                            $result= $stmt->get_result();
                            $numRow = $result->num_rows;
                            if($numRow > 0){
                                $allResponse = [];
                                while($users = $result->fetch_assoc()){
                                    $bankcode = $users['code'];
                                    $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banksystemtype = ? AND userid=?  AND status=?";
                                    $stmt= $connect->prepare($sqlQuery);
                                    $stmt->bind_param("ssi",$bankcode,$userid,$activestatus);
                                    $stmt->execute();
                                    $result= $stmt->get_result();
                                    $numRow = $result->num_rows;
                                    if($numRow > 0){
                                        $allResponse = [];
                                        while($users = $result->fetch_assoc()){
                                             $users['acctname']=str_replace("1APP","",$users['acctname']);
                                    $users['acctname']=str_replace("(","",$users['acctname']);
                                     $users['acctname']=str_replace(")","",$users['acctname']);
                                     $users['acctname']=str_replace("Saver Co-","",$users['acctname']);
                                            array_push($allResponse,json_decode(json_encode($users), true));
                                        }
                                    }
                                    else{
                                        oneappgenerateAccNumber($fname,$lname,$email,$phone,$bvn,$bankcode,$bankname,$userid);
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
                            
                            
                        }else{
                            $errordesc="Bad request";
                            $linktosolve="htps://";
                            $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Error Generating Bank Account, try again later";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                            
                        }
                } else{
                    
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Upgrade to level 2, to generate bank account.";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                            
                }
            }
            else if ($active == 4 || $active == '4'){
                $status =shgenerateAccNumber($fname,$lname,$phoneno,$email,$bvn,$userid);
                if($status){
                    $allResponse=[];
                    //   get all bank list
                    $sqlQuery = "SELECT id,bankname,accno,acctname FROM userpersonalbnkacc WHERE banktypeis = ? AND userid = ?  AND status=?";
                    $stmt= $connect->prepare($sqlQuery);
                    $stmt->bind_param("ssi",$active,$userid,$activestatus);
                    $stmt->execute();
                    $result= $stmt->get_result();
                    $numRow = $result->num_rows;
                    if($numRow > 0){
                        while($users = $result->fetch_assoc()){
                              $users['acctname']=str_replace("THECARDIFYCOMPA /","",$users['acctname']);
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
                    }
                }else{
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Error Generating Bank Account";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
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