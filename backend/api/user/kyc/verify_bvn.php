<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include "../../../config/utilities.php";

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
if (getenv('REQUEST_METHOD') === 'POST'){
        $medthodis= isset($_POST['method']) ? cleanme($_POST['method']) : 0;// 1 whatsapp none is sms
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
        if (!isset($_POST['bvn']) || !isset($_POST['type'])||empty($_POST['bvn'])) {
            $errordesc="All fields must be passed";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Kindly  fill all data";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        } else{
            $bvn = cleanme($_POST['bvn']);
            $bvntype = cleanme($_POST['type']);
        }

        $checkdata =  $connect->prepare("SELECT id FROM kyc_details WHERE bvn=? ");
        $checkdata->bind_param("s",$bvn);
        $checkdata->execute(); 
        $dresult2 = $checkdata->get_result();
        if($dresult2->num_rows==0||$bvn=="22445670983"){
            // checking if sms is sent is not older than 3 min
            $checkdata =  $connect->prepare("SELECT id,timeinserted FROM token WHERE user_id=? ORDER BY id DESC LIMIT 1");
            $checkdata->bind_param("s",$user_id);
            $checkdata->execute();
            $dresult = $checkdata->get_result();
            if ($dresult->num_rows > 0) {
                $row = $dresult->fetch_assoc();
                $time = strtotime($row['timeinserted']);
                $differenceis= time() - $time;
                $minute = round($differenceis/60);
                $left=60-$differenceis;
                    if($minute<1 && $bvntype==1){
                        $errordesc="Bad request";
                        $linktosolve="htps://";
                        $hint=["User is not in the database ensure the user is in the database","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                        $text="You need to wait for at least 1 minute before you can resend ($left seconds left)";
                        $method=getenv('REQUEST_METHOD');
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondBadRequest($data);
                    } 
            }  
            
        
            
            
            if ($bvntype==2 && !isset($_POST['otp'])) {
                $errordesc="All fields must be passed";
                $linktosolve="htps://";
                $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="OTP is needed";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            } else{
                if(isset($_POST['otp'])){
                    $bvnotp = cleanme($_POST['otp']);
                }
            }
            // ban user for calling to many times
            $checkdata =  $connect->prepare("SELECT id FROM bvncalls WHERE userid=? ");
            $checkdata->bind_param("s", $user_id);
            $checkdata->execute();
            $dresult2 = $checkdata->get_result();
            if($dresult2->num_rows>20){
                // $ban=0;
                // $updatePassQuery = "UPDATE users SET  status=? WHERE id = ?";
                // $updateStmt = $connect->prepare($updatePassQuery);
                // $updateStmt->bind_param('ii',$ban,$user_id);
                // $updateStmt->execute();
                // $companykey=" 0_null";
                // ValidateAPITokenSentIN($servername, $companykey, $method, $endpoint);
                
                // $errordesc="You account is banned.";
                // $linktosolve="htps://";
                // $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                // $errordata=returnError7003($errordesc,$linktosolve,$hint);
                // $text="You account is banned.";
                // $method=getenv('REQUEST_METHOD');
                // $data=returnErrorArray($text,$method,$endpoint,$errordata);
                // respondBadRequest($data);
            }
        
            $verifywith=2;//1 1app 2 dojah
            if ( empty($bvn)||empty($bvntype)){
                $errordesc="Insert all fields";
                $linktosolve="htps://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Kindly pass value to the user_id, username field in this register endpoint";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            } else if ($bvntype==2 && empty($bvnotp)){
                $errordesc="Insert all fields";
                $linktosolve="htps://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="OTP can not be empty";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else if (strlen(trim($bvn))<11||strlen(trim($bvn))>11){
                $errordesc="The BVN must be exactly 11 digits.";
                $linktosolve="htps://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="The BVN must be exactly 11 digits.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else{
            $email="";
            $username="";
            $country="";
            $userfulladdress="";
            $userstate="";
            $userdob="";
            $usergender="";
            $phonenuser="";
            $phoneverified=0;
            $sql = "SELECT email,postalcode,username,address1,country,state,dob,sex,phoneno,phoneverified FROM users WHERE id=?";
            $getToken = $connect->prepare($sql);
            $getToken->bind_param('s',$user_id);
            $getToken->execute();
            $result = $getToken->get_result();
            if($result->num_rows ==1){
                $row = $result->fetch_assoc();
                $country=$row['country'];
                $userfulladdress=$row['address1'];
                $userstate=$row['state'];
                $email= $row['email'];
                $username=$row['username'];
                $userdob=$row['dob'];
                $usergender=$row['sex'];
                $phonenuser=$row['phoneno'];
                $phoneverified =$row['phoneverified'];
            }
                
            if (empty($userstate) || empty($country)|| empty($userfulladdress)|| empty($userdob)|| empty($usergender)){
                $errordesc="Please ensure your state,country,address,date of birth and gender is updated in your profile settings";
                $linktosolve="htps://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please ensure your state,country,address,date of birth and gender is updated in your profile settings";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            if (strlen($userfulladdress)<25){
                $errordesc="Please provide your house's complete address in profile settings.";
                $linktosolve="htps://";
                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text="Please provide your house's complete address in profile settings.";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            
                    if($bvntype==1){
                     
                        // bvn verification
                                $amverified=false;
                                // only recall below if user data is not in bvncalls
                                // {"error":"Your balance is low, pls visit the dashboard to top up"}
                                //  empty fullname,phoneno
                                
                                    $checkdata =  $connect->prepare("SELECT pno,jsonresponse FROM bvncalls WHERE userid=? ORDER BY id DESC LIMIT 1");
                                    $checkdata->bind_param("s", $user_id);
                                    $checkdata->execute();
                                    $dresult2 = $checkdata->get_result();
                                    if($dresult2->num_rows>0){
                                        $getdetails=$dresult2->fetch_assoc();
                                        $pno =$getdetails['pno'];
                                         $jsonresponse=$getdetails['jsonresponse'];
                                         $response = json_decode($jsonresponse);
                                        if(strlen($pno)>3 && isset($response->entity->bvn) && $response->entity->bvn==$bvn){
                                            $amverified=true;
                                        }
                                        if(empty($pno)&&strlen($pno)<3){
                                            $errordesc="We noticed your BVN does not have a phone number attached, kindly visit the bank to update your BVN";
                                            $linktosolve="htps://";
                                            $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="We noticed your BVN does not have a phone number attached, kindly visit the bank to update your BVN.";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
                                    }
                                
                                if($amverified==false){
                                    if($verifywith==1){
                                      $amverified=  oneappVerifyBasicBVN($bvn,$user_id);
                                    }else if($verifywith==2){
                                       $amverified =verifyBvnwith_DOj($bvn,$user_id);
                                    }
                                }
                                    
                                if($amverified){  
                                    $checkdata =  $connect->prepare("SELECT pno FROM bvncalls WHERE userid=? ORDER BY id DESC LIMIT 1");
                                    $checkdata->bind_param("s", $user_id);
                                    $checkdata->execute();
                                    $dresult2 = $checkdata->get_result();
                                    if($dresult2->num_rows>0){
                                        $getdetails=$dresult2->fetch_assoc();
                                        $pno =$getdetails['pno'];
                                        // check if phone is same as user reg
                                        $userInput = $pno; 
                                        // Remove any non-digit characters from the user input
                                        $userInput = preg_replace('/[^0-9]/', '', $userInput);
                                        $targetNumber = $phonenuser;
                                        $targetDigits = substr($targetNumber, -11); // Get the last 11 digits of the target number
                                        $onit=1;
                                        if ($onit==1 &&$userInput === $targetDigits && $phoneverified==1) {
                                                //  process bvn
                                                  $checkdata =  $connect->prepare("SELECT jsonresponse FROM bvncalls WHERE userid=? AND pno=?");
                                                $checkdata->bind_param("ss", $user_id,$pno);
                                                $checkdata->execute();
                                                $dresult2 = $checkdata->get_result();
                                                if($dresult2->num_rows>0){
                                                    $bvndata=$dresult2->fetch_assoc();
                                                        $jsonresponse=$bvndata['jsonresponse'];
                                                    $response = $pno= $title=$fname =$lname = $mname = $fullname = $bvn= $gender= $date_of_birth= $residential_address= $state_of_origin= $nationality=$image= $passimgname= $imagename=' ';
                                                    
                                                              if($verifywith==1){
                                                                    $pno=$bvndata['pno'];
                                                                    $title=$bvndata['title'];
                                                                    $fname =$bvndata['fname'];
                                                                    $lname =$bvndata['lastname'];
                                                                    $mname =$bvndata['middlename'];
                                                                    $fullname =$bvndata['fullname'];
                                                    
                                                              }else if($verifywith==2){
                                                    $jsonresponse=$bvndata['jsonresponse'];
                                                    $response = json_decode($jsonresponse);
                                                    $pno="".$response->entity->phone_number1."";
                                                    $title=isset($response->entity->title)?"".$response->entity->title."":" ";
                                                    $fname ="".$response->entity->first_name."";
                                                    $lname ="".$response->entity->last_name."";
                                                    $mname ="".$response->entity->middle_name."";
                                                    $fullname ="$fname $mname $lname";
                                                    $bvn="".$response->entity->bvn."";
                                                    $gender="".$response->entity->gender."";
                                                    $date_of_birth=trim(str_replace("-", "/","".$response->entity->date_of_birth.""));
                                                    $residential_address=isset($response->entity->residential_address)?"".$response->entity->residential_address."":$userfulladdress;
                                                    $state_of_origin=isset($response->entity->state_of_origin)?trim(str_replace("State", "", "".$response->entity->state_of_origin."")):$userstate;
                                                    $nationality=isset($response->entity->nationality)?"".$response->entity->nationality."":$country;
                                                    $image="".$response->entity->image."";
                                                    $passimgname=isset($response->entity->customer)?"".$response->entity->customer."":' ';
                                                    
                                                    // adddress ","country":" ",   "state":" "city": "postalCode":""
    
                                                    if(empty($residential_address)||strlen($residential_address)<=3||empty(trim($residential_address))){
                                                        $residential_address=$userfulladdress;
                                                    }
                                                    if(empty($nationality)||strlen($nationality)<3||empty(trim($nationality))){
                                                        $nationality=$country;
                                                    }
                                                    if(empty($state_of_origin)||strlen($state_of_origin)<3||empty(trim($state_of_origin))){
                                                        $state_of_origin=$userstate;
                                                    }
                                                    $postalcode= getPostalCodeFromState($state_of_origin);
                                                    $city= getCityFromState($state_of_origin);
                                                    
                                                    //optimization=
                                                    // $target_dir = "../../../assets/images/imgholder/";
                                                    // $rpathToImages = $target_dir;
                                                    // $rpathToThumbs = "../../../assets/images/userpassport/";
                                                    // $rthumbWidth = 520;
                                                    
                                                    // $realImage = base64_decode($image);
                                                    // $imagename = cleanme(preg_replace("#[^a-z0-9.]#i", "", $username.$passimgname));
                                                    // file_put_contents($target_dir.$imagename, $realImage);
                                                    // try {
                                                    // createThumbsDynamic($rpathToImages, $rpathToThumbs, $rthumbWidth, $imagename);
                                                    // }
                                                    // catch(Exception $e) {
                                                    // // echo 'Message: ' .$e->getMessage();
                                                    // }
                                                              }
                                 
                                                        $query="";
                                                        $active=1;
       
                                                        $no=0;
                                                        $tokenQuery = 'INSERT INTO  kyc_details (user_id, bvn, fname, lname, middlename, fullname, title, phoneno,json,   gender,postalcode,city,username,email,dob,full_address,stateorigin,country,status,adminseen,showadmin) Values (?, ?, ?, ?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?)';
                                                        $tokenStmt = $connect->prepare($tokenQuery);
                                                        $tokenStmt->bind_param("sssssssssssssssssssss", $user_id,$bvn,$fname,$lname,$mname,$fullname,$title,$pno,$jsonresponse,$gender,$postalcode,$city,$username,$email,$date_of_birth,$residential_address,$state_of_origin,$nationality,$no,$active,$no);
                                                        
                                                        // check if statement executes 
                                                        if($tokenStmt->execute()){
                                                                $tokenStmt->close();
                                                                $kyclevel=1;// 3-approved(fully added and valid) 1-partly added 2-pending (added via level 3 form)
                                                                // if($verifywith==2){
                                                                //   $kyclevel=3; 
                                                                // }
                                                                // 1998/04/23 $date_of_birth 2023-05-18
                                                                $realuserdob=trim(str_replace("/", "-",$date_of_birth));
                                                                $updatePassQuery = "UPDATE users SET kyclevel= ?,sex=?,dob=? WHERE id = ?";
                                                                $updateStmt = $connect->prepare($updatePassQuery);
                                                                $updateStmt->bind_param('sssi', $kyclevel,$gender,$realuserdob ,$user_id);
                                                                $updateStmt->execute();
                                                                
                                                                $userid=$user_id;
                                                                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                                                                $sysgetdata->bind_param("s",$userid);
                                                                $sysgetdata->execute();
                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                // check if user is sending to himself
                                                                $datais=$dsysresult7->fetch_assoc();
                                                                $email=$datais['email'];
                                                                $phoneno=$datais['phoneno'];
                                
                                                                // $subject = levelUpdatedSubject($user_id); 
                                                                // $to = $email;
                                                                // $messageText = levelUpdatedText($user_id);
                                                                // $messageHTML = levelUpdatedHTML($user_id);
                                                                // sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                // sendUserSMS($phoneno,$messageText);
                                                                // // $userid,$message,$type,$ref,$status
    
                                                                
                                                                $maindata=[];
                                                                $maindata['verified']=true;
                                                                $errordesc = " ";
                                                                $linktosolve = "https://";
                                                                $hint = [];
                                                                $errordata = [];
                                                                $method=getenv('REQUEST_METHOD');
                                                                $text = "BVN verified successfully";
                                                                $status = true;
                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                respondOK($data);
                                    
                                                        }else{
                                                                echo $tokenStmt->error;
                                                                //invalid input/ server error
                                                                $errordesc="Bad request";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Error creating KYC data";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data);
                                                        }
                                            
                                                }else{
                                                    //otp expired
                                                    $errordesc="Data not found";
                                                    $linktosolve="https://";
                                                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Data not found";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                
                                                }
                                        }else{
                                           
                                            if(empty($pno)&&strlen($pno)<3){
                                                $errordesc="We noticed your BVN does not have a phone number attached, kindly visit the bank to update your BVN";
                                                $linktosolve="htps://";
                                                $hint=["Kindly pass value to the user_id, username field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                $text="We noticed your BVN does not have a phone number attached, kindly visit the bank to update your BVN.";
                                                $method=getenv('REQUEST_METHOD');
                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                respondBadRequest($data);
                                            }else{
                                            
                                                    // send otp now
                                                    $verifytype=2;
                                                    $useridentity=$pno;
                                                    // set expireTime of the token to 5 minutes
                                                    $expiresin = 10;
                                                    // generate token and insert it into the token table
                                                    // generating  OTP
                                                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                    $otp = createUniqueToken(7," token","otp","",true,false,false);
                                                    $expiretime = time() + ($expiresin*60);
                                                    // generating  token
                                                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                                    $keyup = createUniqueToken(18," token","token","",true,true,true);
                                                    $tokenQuery = 'INSERT INTO  token (user_id,useridentity,token,time,verifytype,otp) Values (?, ?, ?, ?,?,?)';
                                                    $tokenStmt = $connect->prepare($tokenQuery);
                                                    $tokenStmt->bind_param("isssss", $user_id,$useridentity,$keyup,$expiretime, $verifytype,$otp);
                            
                                                    // check if statement executes 
                                                    $tokenStmt->execute();
                                                    $tokenStmt->close();
                                                    $smstosend = sendVerifyBVNotpText($user_id,$keyup,$otp);
                                                    $sendto= $useridentity;
                                                
                                                    
                                                               if($medthodis!=3){
                        // if($medthodis==1){
                          $send21=  sendWithSimpuWhatsApp($sendto,$smstosend);
                        // }else{
                           $send22=  sendUserSMS($sendto,$smstosend);
                        // }
                        }else{
                            // TG OTP
                            $send21=true;
                            $send22=true;
                            send_call_otp($otp,$sendto,"$expiresin Minutes",$keyup,$user_id);
                        }
                        
                                                    // if ($send2) {
                                                    if ($send21||$send22) {
                                                            $mainpno="*******".substr("$pno", -4);
                                                            $maindata['phoneno']=$mainpno;
                                                            $maindata=[$maindata];
                                                            $errordesc = " ";
                                                            $linktosolve = "https://";
                                                            $hint = [];
                                                            $errordata = [];
                                                            $method=getenv('REQUEST_METHOD');
                                                            $text = "Check your sms/whatsapp and type in your OTP";
                                                            $status = true;
                                                            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                            respondOK($data);
                                                    }else{
                                                        $errordesc="Verification Failed";
                                                        $linktosolve="https://";
                                                        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                        $text= "Error sending sms, try again later";
                                                        $method=getenv('REQUEST_METHOD');
                                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                        respondBadRequest($data);
                                                    }
                                            
                                            }
                                            
                                        }
                                            
                                    }else{
                                            $errordesc="Verification Failed";
                                            $linktosolve="https://";
                                            $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text= "Invalid Verfication, Please Check details and try again.";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                    }
                                }else{
                                        $errordesc="Verification Failed";
                                        $linktosolve="https://";
                                        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                        $text= "Invalid Verfication, Please Check details and try again.";
                                        $method=getenv('REQUEST_METHOD');
                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                        respondBadRequest($data);
                                 }
                    }else if($bvntype==2){
                                // bvn otp verification
                                //check if token exist
                            $sql = "SELECT * FROM token WHERE otp=?";
                            $getToken = $connect->prepare($sql);
                            $getToken->bind_param('s', $bvnotp);
                            $getToken->execute();
                            $result = $getToken->get_result();
                            if($result->num_rows ==1){
                                        $row = $result->fetch_assoc();
                                        $otp = $row['otp'];
                                        $time = $row['time'];
                                        $id = $row['user_id'];
                                        $useridentity =$row['useridentity'];
                                        $verifytype= $row['verifytype'];
                                        if($id==$user_id){
                                                //then check expiry
                                                $expiredAt = time();
                                                // get all data
                                                $checkdata =  $connect->prepare("SELECT jsonresponse FROM bvncalls WHERE userid=? AND pno=?");
                                                $checkdata->bind_param("ss", $user_id,$useridentity);
                                                $checkdata->execute();
                                                $dresult2 = $checkdata->get_result();
                                                if($dresult2->num_rows>0){
                                                    $bvndata=$dresult2->fetch_assoc();
                                                        $jsonresponse=$bvndata['jsonresponse'];
                                                    $response = $pno= $title=$fname =$lname = $mname = $fullname = $bvn= $gender= $date_of_birth= $residential_address= $state_of_origin= $nationality=$image= $passimgname= $imagename=' ';
                                                    
                                                               if($verifywith==1){
                                                                    $pno=$bvndata['pno'];
                                                                    $title=$bvndata['title'];
                                                                    $fname =$bvndata['fname'];
                                                                    $lname =$bvndata['lastname'];
                                                                    $mname =$bvndata['middlename'];
                                                                    $fullname =$bvndata['fullname'];
                                                    
                                                               }else if($verifywith==2){
                                                    $jsonresponse=$bvndata['jsonresponse'];
                                                    $response = json_decode($jsonresponse);
                                                    $pno="".$response->entity->phone_number1."";
                                                    $title=isset($response->entity->title)?"".$response->entity->title."":" ";
                                                    $fname ="".$response->entity->first_name."";
                                                    $lname ="".$response->entity->last_name."";
                                                    $mname ="".$response->entity->middle_name."";
                                                    $fullname ="$fname $mname $lname";
                                                    $bvn="".$response->entity->bvn."";
                                                    $gender="".$response->entity->gender."";
                                                    $date_of_birth=trim(str_replace("-", "/","".$response->entity->date_of_birth.""));
                                                    $residential_address=isset($response->entity->residential_address)?"".$response->entity->residential_address."":$userfulladdress;
                                                    $state_of_origin=isset($response->entity->state_of_origin)?trim(str_replace("State", "", "".$response->entity->state_of_origin."")):$userstate;
                                                    $nationality=isset($response->entity->nationality)?"".$response->entity->nationality."":$country;
                                                    $image="".$response->entity->image."";
                                                    $passimgname=isset($response->entity->customer)?"".$response->entity->customer."":' ';
                                                    
                                                    // adddress ","country":" ",   "state":" "city": "postalCode":""
    
                                                    if(empty($residential_address)||strlen($residential_address)<=3||empty(trim($residential_address))){
                                                        $residential_address=$userfulladdress;
                                                    }
                                                    if(empty($nationality)||strlen($nationality)<3||empty(trim($nationality))){
                                                        $nationality=$country;
                                                    }
                                                    if(empty($state_of_origin)||strlen($state_of_origin)<3||empty(trim($state_of_origin))){
                                                        $state_of_origin=$userstate;
                                                    }
                                                    $postalcode= getPostalCodeFromState($state_of_origin);
                                                    $city= getCityFromState($state_of_origin);
                                                    
                                                    //optimization=
                                                    // $target_dir = "../../../assets/images/imgholder/";
                                                    // $rpathToImages = $target_dir;
                                                    // $rpathToThumbs = "../../../assets/images/userpassport/";
                                                    // $rthumbWidth = 520;
                                                    
                                                    // $realImage = base64_decode($image);
                                                    // $imagename = cleanme(preg_replace("#[^a-z0-9.]#i", "", $username.$passimgname));
                                                    // file_put_contents($target_dir.$imagename, $realImage);
                                                    // try {
                                                    // createThumbsDynamic($rpathToImages, $rpathToThumbs, $rthumbWidth, $imagename);
                                                    // }
                                                    // catch(Exception $e) {
                                                    // // echo 'Message: ' .$e->getMessage();
                                                    // }
                                                               }
                                 
                                                
                                                    if($time > time()){
                                                        $query="";
                                                        $active=1;
       
                                                        $no=0;
                                                        $tokenQuery = 'INSERT INTO  kyc_details (user_id, bvn, fname, lname, middlename, fullname, title, phoneno,json,   gender,postalcode,city,username,email,dob,full_address,stateorigin,country,status,adminseen,showadmin) Values (?, ?, ?, ?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?)';
                                                        $tokenStmt = $connect->prepare($tokenQuery);
                                                        $tokenStmt->bind_param("sssssssssssssssssssss", $user_id,$bvn,$fname,$lname,$mname,$fullname,$title,$pno,$jsonresponse,$gender,$postalcode,$city,$username,$email,$date_of_birth,$residential_address,$state_of_origin,$nationality,$no,$active,$no);
                                                        
                                                        // check if statement executes 
                                                        if($tokenStmt->execute()){
                                                                $tokenStmt->close();
                                                                $kyclevel=1;// 3-approved(fully added and valid) 1-partly added 2-pending (added via level 3 form)
                                                                // if($verifywith==2){
                                                                //   $kyclevel=3; 
                                                                // }
                                                                $realuserdob=trim(str_replace("/", "-",$date_of_birth));
                                                                $updatePassQuery = "UPDATE users SET kyclevel= ?,sex=?,dob=? WHERE id = ?";
                                                                $updateStmt = $connect->prepare($updatePassQuery);
                                                                $updateStmt->bind_param('sssi', $kyclevel,$gender,$realuserdob ,$user_id);
                                                                $updateStmt->execute();
                                                                
                                                                $userid=$user_id;
                                                                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                                                                $sysgetdata->bind_param("s",$userid);
                                                                $sysgetdata->execute();
                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                // check if user is sending to himself
                                                                $datais=$dsysresult7->fetch_assoc();
                                                                $email=$datais['email'];
                                                                $phoneno=$datais['phoneno'];
                                
                                                                // $subject = levelUpdatedSubject($user_id); 
                                                                // $to = $email;
                                                                // $messageText = levelUpdatedText($user_id);
                                                                // $messageHTML = levelUpdatedHTML($user_id);
                                                                // sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                // sendUserSMS($phoneno,$messageText);
                                                                // // $userid,$message,$type,$ref,$status
    
                                                                
                                                                $maindata=[];
                                                                $errordesc = " ";
                                                                $linktosolve = "https://";
                                                                $hint = [];
                                                                $errordata = [];
                                                                $method=getenv('REQUEST_METHOD');
                                                                $text = "BVN verified successfully";
                                                                $status = true;
                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                respondOK($data);
                                    
                                                        }else{
                                                                echo $tokenStmt->error;
                                                                //invalid input/ server error
                                                                $errordesc="Bad request";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Error creating KYC data";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data);
                                                        }
                                                    }else{
                                                        //otp expired
                                                        $errordesc="OTP Expired";
                                                        $linktosolve="https://";
                                                        $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                        $text="Your Otp is expired";
                                                        $method=getenv('REQUEST_METHOD');
                                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                        respondBadRequest($data);
                                    
                                                    }
                                                }else{
                                                    //otp expired
                                                    $errordesc="Data not found";
                                                    $linktosolve="https://";
                                                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Data not found";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                
                                                }
                                        }else{
                                            //invalid token
                                            $errordesc="Incorrect OTP";
                                            $linktosolve="htps://";
                                            $hint=["Input token sent to your email or phone","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                            $text="Fill in valid token";
                                            $method=getenv('REQUEST_METHOD');
                                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                            respondBadRequest($data);
                                        }
                            }else{
                                //invalid token
                                $errordesc="Incorrect OTP";
                                $linktosolve="htps://";
                                $hint=["Input token sent to your email or phone","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text="Fill in valid token";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                
                            }
                        
                    }else if($bvntype==3){
                        
                        if (!isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['pno'])|| !isset($_POST['dob'])) {
                            $errordesc="Please fill all data requested.";
                            $linktosolve="htps://";
                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Please fill all data requested.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        } else{
                            $sfname = trim(cleanme($_POST['fname']));
                            $slname = trim(cleanme($_POST['lname']));
                            $spno = trim(cleanme($_POST['pno']));
                            $sdob = trim(cleanme($_POST['dob']));
                            
                            


                        }
                        
                        if (empty($sfname) || empty($slname) || empty($spno)|| empty($sdob)) {
                            $errordesc="Please fill all data requested.";
                            $linktosolve="htps://";
                            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                            $errordata=returnError7003($errordesc,$linktosolve,$hint);
                            $text="Please fill all data requested.";
                            $method=getenv('REQUEST_METHOD');
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondBadRequest($data);
                        } else{
                              //then check expiry
                              $verification=0;
                            //   Validate phone number,dob,fname,lname,middlename >=4
                            $checkdata =  $connect->prepare("SELECT jsonresponse FROM bvncalls WHERE userid=? ORDER BY id DESC");
                            $checkdata->bind_param("s", $user_id);
                            $checkdata->execute();
                            $dresult2 = $checkdata->get_result();
                            if($dresult2->num_rows>0){
                                    $bvndata=$dresult2->fetch_assoc();
                                    $jsonresponse=$bvndata['jsonresponse'];
                                     $response = $pno= $title=$fname =$lname = $mname = $fullname = $bvn= $gender= $date_of_birth= $residential_address= $state_of_origin= $nationality=$image= $passimgname= $imagename=' ';
                
                                       if($verifywith==1){
                                            $pno=$bvndata['pno'];
                                            $title=$bvndata['title'];
                                            $fname =$bvndata['fname'];
                                            $lname =$bvndata['lastname'];
                                            $mname =$bvndata['middlename'];
                            
                                       }else if($verifywith==2){
                                            $jsonresponse=$bvndata['jsonresponse'];
                                            $response = json_decode($jsonresponse);
                                            $pno="".$response->entity->phone_number1."";
                                            $fname ="".$response->entity->first_name."";
                                            $lname ="".$response->entity->last_name."";
                                            $mname ="".$response->entity->middle_name."";
                                            $date_of_birth=trim(str_replace("-", "/","".$response->entity->date_of_birth.""));
                                }
                                
   if (substr($spno, 0, 3) === '234') {// Check if the phone number starts with "234"
        $spno =substr($spno, 3);
    }
       $allresp="$sfname,$slname,$spno,$sdob";
    
    $paymentidisni="MANUAL BVN $user_id";
$orderidni="MANUAL BVN $user_id";
$insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
$insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
$insert_data->execute();
$insert_data->close();

                                            if($pno==$spno){
                                                $verification++;
                                            }
                                            if(strtolower($sfname)==strtolower($fname)||strtolower($sfname)==strtolower($lname)){
                                                $verification++;
                                            }
                                            if(strtolower($slname)==strtolower($fname)||strtolower($slname)==strtolower($lname)){
                                                $verification++;
                                            }
                                            // 1998-04-23
                                            $sdob=str_replace("/", "-",$sdob);
                                            $sdob=date("Y-m-d",strtotime($sdob));
                                            $date_of_birth=str_replace("/", "-",$date_of_birth);
                                            $date_of_birth=date("Y-m-d",strtotime($date_of_birth));
                                            if(strtolower($sdob)==strtolower($date_of_birth)){
                                                $verification++;
                                            }
                                           
                                      if($verification>=4){
                                                $expiredAt = time();
                                                // get all data
                                                $checkdata =  $connect->prepare("SELECT jsonresponse FROM bvncalls WHERE userid=? ORDER BY id DESC");
                                                $checkdata->bind_param("s", $user_id);
                                                $checkdata->execute();
                                                $dresult2 = $checkdata->get_result();
                                                if($dresult2->num_rows>0){
                                                    $bvndata=$dresult2->fetch_assoc();
                                                    $jsonresponse=$bvndata['jsonresponse'];
                                                    $response = $pno= $title=$fname =$lname = $mname = $fullname = $bvn= $gender= $date_of_birth= $residential_address= $state_of_origin= $nationality=$image= $passimgname= $imagename=' ';
                                                    
                                                       if($verifywith==1){
                                                            $pno=$bvndata['pno'];
                                                            $title=$bvndata['title'];
                                                            $fname =$bvndata['fname'];
                                                            $lname =$bvndata['lastname'];
                                                            $mname =$bvndata['middlename'];
                                                            $fullname =$bvndata['fullname'];
                                            
                                                       }else if($verifywith==2){
                                            $jsonresponse=$bvndata['jsonresponse'];
                                            $response = json_decode($jsonresponse);
                                                    $pno="".$response->entity->phone_number1."";
                                                    $title=isset($response->entity->title)?"".$response->entity->title."":" ";
                                                    $fname ="".$response->entity->first_name."";
                                                    $lname ="".$response->entity->last_name."";
                                                    $mname ="".$response->entity->middle_name."";
                                                    $fullname ="$fname $mname $lname";
                                                    $bvn="".$response->entity->bvn."";
                                                    $gender="".$response->entity->gender."";
                                                    $date_of_birth=trim(str_replace("-", "/","".$response->entity->date_of_birth.""));
                                                    $residential_address=isset($response->entity->residential_address)?"".$response->entity->residential_address."":$userfulladdress;
                                                    $state_of_origin=isset($response->entity->state_of_origin)?trim(str_replace("State", "", "".$response->entity->state_of_origin."")):$userstate;
                                                    $nationality=isset($response->entity->nationality)?"".$response->entity->nationality."":$country;
                                                    $image="".$response->entity->image."";
                                                    $passimgname=isset($response->entity->customer)?"".$response->entity->customer."":' ';
                                                       if(empty($residential_address)||strlen($residential_address)<=3||empty(trim($residential_address))){
                                                        $residential_address=$userfulladdress;
                                                    }
                                                    if(empty($nationality)||strlen($nationality)<3||empty(trim($nationality))){
                                                        $nationality=$country;
                                                    }
                                                    if(empty($state_of_origin)||strlen($state_of_origin)<3||empty(trim($state_of_origin))){
                                                        $state_of_origin=$userstate;
                                                    }
                                                    $postalcode= getPostalCodeFromState($state_of_origin);
                                                    $city= getCityFromState($state_of_origin);
                                            
                                            //optimization=
                                            // $target_dir = "../../../assets/images/imgholder/";
                                            // $rpathToImages = $target_dir;
                                            // $rpathToThumbs = "../../../assets/images/userpassport/";
                                            // $rthumbWidth = 520;
                                            
                                            // $realImage = base64_decode($image);
                                            // $imagename = cleanme(preg_replace("#[^a-z0-9.]#i", "", $username.$passimgname));
                                            // file_put_contents($target_dir.$imagename, $realImage);
                                            // try {
                                            // createThumbsDynamic($rpathToImages, $rpathToThumbs, $rthumbWidth, $imagename);
                                            // }
                                            // catch(Exception $e) {
                                            // // echo 'Message: ' .$e->getMessage();
                                            // }
                                                       }
                                 
                                                
                                                        $query="";
                                                        $active=1;
                        
                                                        $no=0;
                                                        $tokenQuery = 'INSERT INTO  kyc_details (user_id, bvn, fname, lname, middlename, fullname, title, phoneno,json,   gender,postalcode,city,username,email,dob,full_address,stateorigin,country,status,adminseen,showadmin) Values (?, ?, ?, ?,?,?,?,?,?, ?,?,?,?,?,?,?,?,?,?,?,?)';
                                                        $tokenStmt = $connect->prepare($tokenQuery);
                                                        $tokenStmt->bind_param("sssssssssssssssssssss", $user_id,$bvn,$fname,$lname,$mname,$fullname,$title,$pno,$jsonresponse,$gender,$postalcode,$city,$username,$email,$date_of_birth,$residential_address,$state_of_origin,$nationality,$no,$active,$no);
                                                        
                                                        // check if statement executes 
                                                        if($tokenStmt->execute()){
                                                                $tokenStmt->close();
                                                                $kyclevel=1;// 3-approved(fully added and valid) 1-partly added 2-pending (added via level 3 form)
                                                                // if($verifywith==2){
                                                                //   $kyclevel=3; 
                                                                // }
                                                                $realuserdob=trim(str_replace("/", "-",$date_of_birth));
                                                                $updatePassQuery = "UPDATE users SET kyclevel= ?,sex=?,dob=? WHERE id = ?";
                                                                $updateStmt = $connect->prepare($updatePassQuery);
                                                                $updateStmt->bind_param('sssi', $kyclevel,$gender,$realuserdob ,$user_id);
                                                                $updateStmt->execute();
                                                                
                                                                $userid=$user_id;
                                                                $sysgetdata =  $connect->prepare("SELECT email,phoneno FROM users WHERE id=?");
                                                                $sysgetdata->bind_param("s",$userid);
                                                                $sysgetdata->execute();
                                                                $dsysresult7 = $sysgetdata->get_result();
                                                                // check if user is sending to himself
                                                                $datais=$dsysresult7->fetch_assoc();
                                                                $email=$datais['email'];
                                                                $phoneno=$datais['phoneno'];
                                
                                                                // $subject = levelUpdatedSubject($user_id); 
                                                                // $to = $email;
                                                                // $messageText = levelUpdatedText($user_id);
                                                                // $messageHTML = levelUpdatedHTML($user_id);
                                                                // sendUserMail($subject,$to,$messageText, $messageHTML);
                                                                // sendUserSMS($phoneno,$messageText);
                                                                // // $userid,$message,$type,$ref,$status
                        
                                                                
                                                                $maindata=[];
                                                                $errordesc = " ";
                                                                $linktosolve = "https://";
                                                                $hint = [];
                                                                $errordata = [];
                                                                $method=getenv('REQUEST_METHOD');
                                                                $text = "BVN verified successfully";
                                                                $status = true;
                                                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                                                respondOK($data);
                                    
                                                        }else{
                                                                echo $tokenStmt->error;
                                                                //invalid input/ server error
                                                                $errordesc="Bad request";
                                                                $linktosolve="htps://";
                                                                $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                                                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                                $text="Error creating KYC data";
                                                                $method=getenv('REQUEST_METHOD');
                                                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                                respondBadRequest($data);
                                                        }
                                                   
                                                }else{
                                                    //otp expired
                                                    $errordesc="Data not found";
                                                    $linktosolve="https://";
                                                    $hint=["Generate another token","Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                                    $text="Data not found";
                                                    $method=getenv('REQUEST_METHOD');
                                                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                                    respondBadRequest($data);
                                
                                        }
                                      }else{
                                        $errordesc="Invalid data inputted,please contact support via the chat button below.";
                                        $linktosolve="https://";
                                        $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                        $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                        $text= "Invalid data inputted,please contact support via the chat button below.";
                                        $method=getenv('REQUEST_METHOD');
                                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                        respondBadRequest($data);
                                    }
                            }else{
                                $errordesc="You need to pass through first verification stage";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text= "Invalid Verfication, Please Check details and try again.";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data);
                            }
                        }
                        
                    }else{
                                $errordesc="Verification Failed";
                                $linktosolve="https://";
                                $hint=["Ensure that all data specified in the API is sent","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
                                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                                $text= "Invalid Verfication, Please Check details and try again.";
                                $method=getenv('REQUEST_METHOD');
                                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                                respondBadRequest($data); 
                    }     
            }
        }else{
            $errordesc="An account with this BVN already exists. Please note that each BVN can only be associated with one account.";
            $linktosolve="htps://";
            $hint=["Kindly pass the required current password field in this register endpoint","Ensure that all data sent is not empty","Ensure that the exact data type specified in the documentation is sent."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="An account with this BVN already exists. Please note that each BVN can only be associated with one account.";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
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




