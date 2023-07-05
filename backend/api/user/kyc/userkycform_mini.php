<?php
// send some CORS headers so the API can be called from anywhere
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
Header("Cache-Control: no-cache");
// header("Access-Control-Max-Age: 3600");//3600 seconds
// 1)private,max-age=60 (browser is only allowed to cache) 2)no-store(public),max-age=60 (all intermidiary can cache, not browser alone)  3)no-cache (no ceaching at all)



include "../../../config/utilities.php";

$endpoint="../../api/user/auth/".basename($_SERVER['PHP_SELF']);
$method = getenv('REQUEST_METHOD');
?>
<?php
function getBase64ImageSize($base64Image){ //return memory size in B, KB, MB
    try{
        $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
        $size_in_kb    = $size_in_bytes / 1024;
        $size_in_mb    = $size_in_kb / 1024;

        return $size_in_kb;
    }
    catch(Exception $e){
        return $e;
    }
}

$maindata=[];
if (getenv('REQUEST_METHOD') == 'POST') {
    $fail="";
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
        
    } else{
        $user_id = getUserWithPubKey($connect, $user_pubkey);
    }

    $allowed =  array('jpg','jpeg','svg','png','gif');
    $userfacebook = $usertwitter= $userinstagram= $usertelegram=$imagename4="null";
    
        $data = json_decode(file_get_contents("php://input"));


 
    if (isset($data->regtype)) {
        $userregtype = cleanme($data->regtype);
    } else {
        $userregtype = '';
    }



    if (isset($data->regimage)) {
        $regimage = cleanme($data->regimage,1);
    } else {
        $regimage  = '';
    }
    if (isset($data->regimgname)) {
        $regimgname = cleanme($data->regimgname);
    } else {
        $regimgname = '';
    }
    
    if (isset($data->reg_id_num)) {
        $reg_id_num = cleanme($data->reg_id_num);
    } else {
        $reg_id_num = '';
    }
        if (isset($data->house_number)) {
        $house_number = cleanme($data->house_number);
    } else {
        $house_number = '';
    }
    
    // start here
         $checkdata =  $connect->prepare("SELECT pin,kyclevel,userlevel,country,state,postalcode,address1,address2 FROM users WHERE id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        $foundUser= $dresultUser->fetch_assoc();
        $passpin = $foundUser['pin'];
        $userKycLevel= $foundUser['kyclevel'];
        $userlevel= $foundUser['userlevel'];
        
        $usercountry= $foundUser['country'];
        $userstate= $foundUser['state'];
        $userpostalcode= $foundUser['postalcode'];
        $useraddress1= $foundUser['address1'];
        
        // check user address and details
        $checkdata =  $connect->prepare("SELECT full_address,stateorigin,country FROM kyc_details WHERE user_id=? ");
        $checkdata->bind_param("s", $user_id);
        $checkdata->execute();
        $dresultUser = $checkdata->get_result();
        if($dresultUser->num_rows>0){
            
            $foundUser= $dresultUser->fetch_assoc();
            // check if city state and country is empty
            $kycfull_address= $foundUser['full_address'];
            $kycstateorigin= $foundUser['stateorigin'];
            $kyccountry= $foundUser['country'];
            // if both bvn detail and profile detail is empty
            if((empty($kycfull_address)||empty($kycstateorigin)||empty($kyccountry)) && (empty($usercountry)||empty($userstate)||empty($userpostalcode)||empty($useraddress1))){
                    $errordesc="Bad request";
                    $linktosolve="htps://";
                    $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text="Please update your profile detail";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
            }
            // if empty bvn details, get data from profile detail
            if(empty($kycfull_address)||empty($kycstateorigin)||empty($kyccountry)) {
                // (city postal code ->state) (country) address
                $postalcode= getPostalCodeFromState($userstate);
                $city= getCityFromState($userstate);
            
                $updatePassQuery = "UPDATE kyc_details SET 	stateorigin= ?,	country=?,full_address=?,postalcode=?,city=? WHERE user_id=?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('ssssss', $userstate,$usercountry,$useraddress1, $postalcode,$city, $user_id);
                $updateStmt->execute();
            }
        }else{
            $errordesc="Bad request";
            $linktosolve="htps://";
            $hint=["Ensure to send valid data, data already registered in the database.", "Use registered API to get a valid data","Read the documentation to understand how to use this API"];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text="Please upgrade your level to level 2";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);
        }


    
   
    // 'image/gif', 
   $array_file_type = array('image/png', 'image/x-png', 'image/jpeg', 'image/pjpeg');

   $regarray=getimagesize("$regimage");
    if(getBase64ImageSize($regimage) > 250){
         $fail="Regulation card File size must not be more than 250KB.";
    }else if(!isset($regarray['mime'])|| !in_array($regarray['mime'],$array_file_type) || explode("/",$regarray['mime'])[0]!="image"){
          $fail="Your Regulation card is not a valid image, only png, jpeg, jpg is allowed";
    }else if (empty($house_number)||empty($reg_id_num )||empty($userregtype)) {
          $fail="Please fill all required fields";
    }
 
    if ($fail=="") {
                    $getUser = $connect->prepare("SELECT * FROM users WHERE id= ?");
                    $getUser->bind_param("s",$user_id);
                    $getUser->execute();
                    $result = $getUser->get_result();
                    if($result->num_rows > 0){
                        //user exist
                        $row = $result->fetch_assoc();
                        $dashid = $row['id'];
                        $dashuname =$row['username'];
                        $phoneno=$row['phoneno'];
                        $email =$row['email'];
                    }
            
                    $checkdata =  $connect->prepare("SELECT * FROM kyc_details WHERE user_id=?");
                    $checkdata->bind_param("s",$user_id);
                    $checkdata->execute();
                    $dresult = $checkdata->get_result();
                    if ($dresult->num_rows > 0) {
                        $dta= $dresult->fetch_assoc();
                        $front= $dta['vc_verify_img'];
                        
                        $rpathToThumbs2 = "../../../assets/images/userregulatorycards/";
                       
                        if(!empty($front)){
                        deleteinFolder($front ,$rpathToThumbs2);
                        }   
                    }
                    $checkdata->close();
                    $target_dir = "../../../assets/images/imgholder/";
            
                    $dob =  $dob;
                    $dob = convertTime($dob);
                    $mobileno =  $phoneno;

                    //optimization=
                    $rpathToImages = $target_dir;
                    $rpathToThumbs = "../../../assets/images/userpassport/";
                    $rthumbWidth = 520;
                    //optimization
                    $rpathToThumbs2 = "../../../assets/images/userregulatorycards/";
                  
                    $regimagearr= explode(',',$regimage);
                    $realImage2 = base64_decode($regimagearr[1]);
                    $imagename2 = cleanme(preg_replace("#[^a-z0-9.]#i", "", $dashuname.$regimgname));
                    file_put_contents($target_dir.$imagename2, $realImage2);
                    try {
                    createThumbsDynamic($rpathToImages, $rpathToThumbs2, $rthumbWidth, $imagename2);
                    }catch(Exception $e) {
                    // echo 'Message: ' .$e->getMessage();
                    }
                  

                    //delete first image
                    $dir = "../../../assets/images/imgholder/";
                    deleteinFolder($imagename2, $dir);
                   
                    $now=time();
                    $status=0;
                    $showadmin=1;
                  
                    
                    $update_data = $connect->prepare("UPDATE kyc_details SET   house_number=?,reg_id_number=?,vc_verify_img=?,reg_type=? WHERE 	user_id=?");
                    $update_data->bind_param("sssss",$house_number, $reg_id_num, $imagename2,$userregtype,$dashid);
                    if ($update_data->execute()) {
                        
                        // call BC server for verification, rmeove if BC server is not used again
                        $active=1;
                        $supplier=2;
                        $checkdata =  $connect->prepare("SELECT id FROM vc_type WHERE supplier=? AND status=?");
                        $checkdata->bind_param("ss", $supplier,$active);
                        $checkdata->execute();
                        $dresult2 = $checkdata->get_result();
                        if($dresult2->num_rows>0){
                            $currency="USD";
                            $validcreation=createBCVC_customer($dashid,$currency);
                            if($validcreation==1||$validcreation==true){
                                
                                $kyc=2;// pending $kyclevel=1;// 3-approved(fully added and valid) 1-partly added 2-pending (added via level 3 form)
                                $update_data = $connect->prepare("UPDATE users SET vc_card_verified=? WHERE id=?");
                                $update_data->bind_param("ss", $kyc, $dashid);
                                $update_data->execute();
                                $update_data->close();
                                
                                $errordesc = " ";
                                $linktosolve = "htps://";
                                $hint = [];
                                $errordata = [];
                                $text = "Form submitted successfully, kindly wait while we process your submission.";
                                $method = getenv('REQUEST_METHOD');
                                $status = true;
                                $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
                                respondOK($data);
                            }else{
                                $errordesc = "Bad request";
                                $linktosolve = "htps://";
                                $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
                                $errordata = returnError7003($errordesc, $linktosolve, $hint);
                                $text = $validcreation;
                                $data = returnErrorArray($text, $method, $endpoint, $errordata);
                                respondBadRequest($data);
                            }
                        }else{
                            $errordesc = "Bad request";
                            $linktosolve = "htps://";
                            $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
                            $errordata = returnError7003($errordesc, $linktosolve, $hint);
                            $text = "System is currently not active, try again later";
                            $data = returnErrorArray($text, $method, $endpoint, $errordata);
                            respondBadRequest($data);
                        }
                        
                    } else {
                        $errordesc = "Internal server error";
                        $linktosolve = "htps://";
                        $hint = ["System not saving user details into the KYC table"];
                        $errordata = returnError7003($errordesc, $linktosolve, $hint);
                        $text = "An error occured";
                        $method = getenv('REQUEST_METHOD');
                        $data = returnErrorArray($text, $method, $endpoint, $errordata);
                        respondInternalError($data);
                        $update_data->close();
                    }
                
    } else {
        $errordesc = "Bad request";
        $linktosolve = "htps://";
        $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
        $errordata = returnError7003($errordesc, $linktosolve, $hint);
        $text = $fail;
        $data = returnErrorArray($text, $method, $endpoint, $errordata);
        respondBadRequest($data);
    }
} else {
    $errordesc = "Method not allowed";
    $linktosolve = "htps://";
    $hint = ["Ensure to use the method stated in the documentation."];
    $errordata = returnError7003($errordesc, $linktosolve, $hint);
    $text = "Method used not allowed";
    $method = getenv('REQUEST_METHOD');
    $data = returnErrorArray($text, $method, $endpoint, $errordata);
    respondMethodNotAlowed($data);
}
?>