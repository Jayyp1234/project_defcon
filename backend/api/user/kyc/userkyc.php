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
$maindata=[];
if (getenv('REQUEST_METHOD') == 'GET') {
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
            
        }
        else{
            $user_id = getUserWithPubKey($connect, $user_pubkey);
        }
    if ($fail=="") {
        
        $checkdata =  $connect->prepare("SELECT * FROM kyc_details WHERE user_id=?");
        $checkdata->bind_param("s",$user_id);
        $checkdata->execute();
        $dresult = $checkdata->get_result();
        if ($dresult->num_rows > 0) {
            $dta= $dresult->fetch_assoc();
            $submittedbvn='*********'.substr($dta['bvn'], 8, 13);
            $submittedfblink=$dta['fblink'];
            $submittedtwitter=$dta['twitterlink'];
            $submittedtelegram=$dta['telegram'];
            $submittedinstagram=$dta['instagram'];
            $submittedbusinesstype=$dta['business_type'];
            $submittedbusinesscc=$dta['business_cc'];
            $submittedpassport=$dta['passport'];
            $submittedfreg=$dta['front_regcard'];
            $submittedbreg=$dta['back_regcard'];
            $submittedregtype=$dta['reg_type'];
            $submittedfname=$dta['fname'];
            $submittedlaname=$dta['lname'];
            $submittedmiddlename=$dta['middlename'];
            $submittedfullname=$dta['fullname'];
            $submittedtitle=$dta['title'];
            
            
            
            $submittedemail=$dta['email'];
            $submittedphoneo=$dta['phoneno'];
            $submitteddob=strlen($dta['dob'])==0||!is_numeric ( $dta['dob'])?' ':gettheTimeAndDate($dta['dob']);
            $submittedaddress=$dta['full_address'];
            $submittedstate=$dta['stateorigin'];
            $submittedcountry=$dta['country'];
            $submittedtime=empty($dta['time'])? ' ':gettheTimeAndDate($dta['time']);
            $sbusinesstxt="";
            if ($submittedbusinesstype==1) {
                $sbusinesstxt="Individual";
            } elseif ($submittedbusinesstype==2) {
                $sbusinesstxt="Organization";
            }
            $regtypetxt="";
            if ($submittedregtype==1) {
                $regtypetxt="National ID Card";
            } elseif ($submittedregtype==2) {
                $regtypetxt="Drivers Lincense";
            } elseif ($submittedregtype==3) {
                $regtypetxt="Voters Card";
            } elseif ($submittedregtype==4) {
                $regtypetxt="International Passport";
            }

            if(empty($submittedpassport)||$submittedpassport==' '){
                 $passportlink ="";
            }else{
                 $passportlink = BASEURL."assets/images/userpassport/$submittedpassport";
            }
            if(empty($regimglink)){
                 $regimglink ="";
            }else{
                    $regimglink = BASEURL."assets/images/userregulatorycards/$submittedfreg";  
            }
      if(empty($holdregimglink)){
          $holdregimglink ="";
      }else{
               $holdregimglink = BASEURL."assets/images/userregulatorycards/$submittedbreg";   
      }
    if(empty($bizccimglink )){
         $bizccimglink ="";
    }else{
              $bizccimglink = BASEURL."assets/images/userbusinesscards/$submittedbusinesscc";  
    }
    
            $dkycstatys=" ";
            if($dta['status']==0){
                $dkycstatys="Pending";
            }else{
                $dkycstatys="Approved";
            }


            array_push($maindata, array("middlename"=>$submittedmiddlename,"fullname"=>$submittedfullname,"title"=>$submittedtitle,"bizimgname"=>$submittedbusinesscc,"biztypeno"=>$submittedbusinesstype,"bvn"=>$submittedbvn,"facebooklink" => $submittedfblink,"twitterlink" => $submittedtwitter,"telegramlink" => $submittedtelegram,"instagramlink" => $submittedinstagram,"biztype" =>$sbusinesstxt,"regtype" =>$regtypetxt,"firstname" => $submittedfname,"lastname" => $submittedlaname,"email" => $submittedemail,"phoneno" => $submittedphoneo,"dob" => $submitteddob,"fulladdress" => $submittedaddress,"state" => $submittedstate,"country"=> $submittedcountry,"submittedtime" => $submittedtime,"passport" => $passportlink,"regulationimg" => $regimglink,"holdregulationimg" => $holdregimglink,"businesscc" => $bizccimglink,"kycstatus"=>$dkycstatys));

            $errordesc = " ";
            $linktosolve = "htps://";
            $hint = [];
            $errordata = [];
            $text = "Data found";
            $method = getenv('REQUEST_METHOD');
            $status = true;
            $data = returnSuccessArray($text, $method, $endpoint, $errordata, $maindata, $status);
            respondOK($data);

        } else {
                $errordesc = "Bad request";
                $linktosolve = "htps://";
                $hint = ["Ensure user data exist in the database","Ensure that all data specified in the API is sent", "Ensure that all data sent is not empty", "Ensure that the exact data type specified in the documentation is sent."];
                $errordata = returnError7003($errordesc, $linktosolve, $hint);
                $text = "KYC not Found";
                $data = returnErrorArray($text, $method, $endpoint, $errordata);
                respondBadRequest($data);
        }
        $checkdata->close();
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