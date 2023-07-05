<?php
//SMS FUNCTION below is where all functions related to sms is added
//  you dont have to edit this
function  GetActiveTermiApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM termiapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
} 
function  GetSimpuApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM  simpuapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
} 
function  GetActiveKudiApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM kudiapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function  GetActiveSmartSolutionApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM smartsolutionapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}

//  code for all integrations
function sendWithSimpu($sendto,$smstosend){
        $termidata=GetSimpuApi();
        $smssent=false;
        // $dnum = substr($sendto, 1);
        // $sendto="234".$dnum;
        $channel=$termidata['smschannel']; 
        $tokenis=$termidata['apikey'];
        
        $postdatais=array (
            'recipients' => "$sendto",
            'content' =>"$smstosend",
            'channel' =>"$channel",
        );
        $jsonpostdata=json_encode($postdatais);
        $url ="https://api.simpu.co/sms/send";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => trim($jsonpostdata),
                CURLOPT_HTTPHEADER => array(
                    "Authorization:   $tokenis",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);

        //  print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        if($err){
            $smssent=false;
        }else{
            $theresponse= json_decode($userdetails);
            //   print_r($theresponse);
            if(isset($theresponse->status) && $theresponse->status==200){
                $smssent=true;
                // $msgid= $theresponse->message_id;
                // later log sms sent
                // $systype="Termii";
                // $insert_data4 = $connect->prepare("INSERT INTO smslog(message,sentto,sentwith,messageid,sentrom) VALUES (?,?,?,?,?)");
                // $insert_data4->bind_param("sssss", $msg,$sendto,$systype,$msgid,$sendfrom);
                // $insert_data4->execute();
                // $insert_data4->close();
            }else{
                 $smssent=false;
            }
        }
    return $smssent;
}
function sendWithSimpuWhatsApp($sendto,$smstosend){
        $termidata=GetSimpuApi();
        $smssent=false;
        // $dnum = substr($sendto, 1);
        // $sendto="234".$dnum;
        
        $phoneNumber = preg_replace('/\D/', '',$sendto);

    // Check if the phone number starts with "0"
    if (substr($phoneNumber, 0, 1) === '0') {
        // Remove the leading "0" and add "234" in front
        $phoneNumber = '234' . substr($phoneNumber, 1);
    }else if (substr($phoneNumber, 0, 3) === '234') {// Check if the phone number starts with "234"
        // Do nothing, the country code is already added
    } else {
        // Add "234" in front of the phone number
        $phoneNumber = '234' . $phoneNumber;
    }
    $sendto=$phoneNumber;
        $channel=$termidata['smschannel2']; 
        $tokenis=$termidata['apikey'];
        $channelid=$termidata['channel_id'];
        
        $postdatais=array (
            'recipients' => "$sendto",
            'content' =>"$smstosend",
            'channel' =>"$channel",
             'channel_id' =>"$channelid"
        );
        $jsonpostdata=json_encode($postdatais);
        $url ="https://api.simpu.co/sms/send";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => trim($jsonpostdata),
                CURLOPT_HTTPHEADER => array(
                    "Authorization:   $tokenis",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);

        //  print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        if($err){
            $smssent=false;
        }else{
            $theresponse= json_decode($userdetails);
            //   print_r($theresponse);
            if(isset($theresponse->status) && $theresponse->status==200){
                $smssent=true;
                // $msgid= $theresponse->message_id;
                // later log sms sent
                // $systype="Termii";
                // $insert_data4 = $connect->prepare("INSERT INTO smslog(message,sentto,sentwith,messageid,sentrom) VALUES (?,?,?,?,?)");
                // $insert_data4->bind_param("sssss", $msg,$sendto,$systype,$msgid,$sendfrom);
                // $insert_data4->execute();
                // $insert_data4->close();
            }else{
                 $smssent=false;
            }
        }
    return $smssent;
}
function sendWithTermi($sendto,$smstosend){
        $termidata=GetActiveTermiApi();
        $smssent=false;
        $dnum = substr($sendto, 1);
        $sendto="234".$dnum;
        $channel=$termidata['smschannel'];
   
        $starttimefortoday= strtotime("6:00 PM");
        $endtimefortoday= strtotime("9:20 AM");
        $currenttimeis=time();
        // echo $currenttimeis;
        // echo "<br>";
        // check for if data is to next day 10PM-8AM
        // if($starttimefortoday>$endtimefortoday){
        //     if($currenttimeis >=$starttimefortoday || $currenttimeis <$endtimefortoday){
        //          $channel=$termidata['smschannel2']; 
        //     }
        // }else if($currenttimeis>=$starttimefortoday && $currenttimeis<=$endtimefortoday){
            $channel=$termidata['smschannel2']; 
            // }
        
        
        
        
       $arr = array(
        "to"=> $sendto,
        "sms"=>$smstosend,
       "api_key"=> $termidata['apikey'],
       "from"=> "N-Alert",//$termidata['sendfrom'],
       "type"=> $termidata['smstype'],
       "channel"=> $channel,
       );
       //below is the base url
       $url ="https://termii.com/api/sms/send";
       $params =  json_encode($arr);
       $curl = curl_init();
       curl_setopt_array($curl, array(
       //u change the url infront based on the request u want
       CURLOPT_URL => $url,
       CURLOPT_POSTFIELDS => $params,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       //change this based on what u need post,get etc
       CURLOPT_CUSTOMREQUEST => "POST",
       CURLOPT_HTTPHEADER => array(
       "content-type: application/json",
       ),
       ));
       $resp = curl_exec($curl);
       $err = curl_error($curl);
       curl_close($curl);
    //   print($resp);
        if($err){
            $smssent=false;
        }else{
            $theresponse= json_decode($resp);
            //   print_r($theresponse);
            if(isset($theresponse->code) && $theresponse->code=="ok"){
                $smssent=true;
                $msgid= $theresponse->message_id;
                // later log sms sent
                // $systype="Termii";
                // $insert_data4 = $connect->prepare("INSERT INTO smslog(message,sentto,sentwith,messageid,sentrom) VALUES (?,?,?,?,?)");
                // $insert_data4->bind_param("sssss", $msg,$sendto,$systype,$msgid,$sendfrom);
                // $insert_data4->execute();
                // $insert_data4->close();
            }else{
                 $smssent=false;
            }
        }
    return $smssent;
}
function sendWithKudiSMS($sendto,$smstosend){
        $sysdata=GetActiveKudiApi();
        $smssent=false;

        /*
        Sending messages using the KudiSMS API
        Requirements - PHP, file_get_contents (enabled) function
        */
        // Initialize variables ( set your variables here )
        $username = $sysdata['username'];
        $password = $sysdata['password'];
        $sender = $sysdata['sendfrom'];
        $message = $smstosend;
        // Separate multiple numbers by comma
        $mobiles = $sendto;
          // Set your domain's API URL
        $api_url = 'https://account.kudisms.net/api/';
         //Create the message data
        $data = array('username' => $username, 'password' => $password, 'sender' => $sender,
            'message' => $message, 'mobiles' => $mobiles);
            //URL encode the message data
            $data = http_build_query($data);
            //Send the message  
            $request = $api_url . '?' . $data;
            $result = file_get_contents($request);
            $result = json_decode($result);
            if (isset($result->status) && strtoupper($result->status) == 'OK') {
            // Message sent successfully, do anything here
            // echo 'Message sent at N' . $result->price;
                 $smssent=true;
            } else if (isset($result->error)) {
                $smssent=false;
            // Message failed, check reason.
            // echo 'Message failed - error: ' . $result->error;
            } else {
                $smssent=false;
            // Could not determine the message response.
            // echo 'Unable to process request';
            }
            return $smssent;
}
function sendWithSmartSolution($sendto,$smstosend){
        $sysdata=GetActiveSmartSolutionApi();
        $smssent=false;
        // Initialize variables ( set your variables here )
        $sendfrom = $sysdata['sendfrom'];
        $sendtype = $sysdata['sendtype'];
        $routing = $sysdata['routing'];
        $token = $sysdata['apitoken'];
        // ref_id ADD THIS WHEN LOGGING SMS
        $message = $smstosend;
        // Separate multiple numbers by comma
        $mobiles = $sendto;
        $baseurl = 'https://smartsmssolutions.com/api/json.php?';
      
          $sms_array = array
              (
              'sender' => $sendfrom,
              'to' => $mobiles,
              'message' => $message,
              'type' => $sendtype,
              'routing' => $routing,
              'token' => $token,
          );
      
          $params = http_build_query($sms_array);
          $ch = curl_init();
      
          curl_setopt($ch, CURLOPT_URL, $baseurl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      
          $resp = curl_exec($ch);
          $err = curl_error($ch);
           if($err){
               $smssent=false;
           }else{
               $theresponse= json_decode($resp);
               //   print_r($theresponse);
               if($theresponse->code==1000){
                    $smssent=true;
                    $msgid= $theresponse->message_id;
                   // $systype="Termii";
                   // $insert_data4 = $connect->prepare("INSERT INTO smslog(message,sentto,sentwith,messageid,sentrom) VALUES (?,?,?,?,?)");
                   // $insert_data4->bind_param("sssss", $msg,$sendto,$systype,$msgid,$sendfrom);
                   // $insert_data4->execute();
                   // $insert_data4->close();
               }else{
                    $smssent=false;
               }
           }
           curl_close($ch);
           return  $smssent;
}
//  code for all integrations
//  you dont have to edit this

// FUNCTIONS functions related to the users
 function smsgetUserData($userid) {
    //input type checks if its from post request or just normal function call
    global $connect;
    $alldata = [];

    $checkdata = $connect->prepare("SELECT  * FROM users  WHERE id=?");
    $checkdata->bind_param("s",$userid);
    $checkdata->execute();
    $getresultemail = $checkdata->get_result();
    if ($getresultemail->num_rows > 0) {
        $getthedata = $getresultemail->fetch_assoc();
        $alldata = $getthedata;
    }
    return $alldata;
}
function smsgetSingleUserTransWithOrderID($orderid)
{
    global $connect;
    $alldata=[];
    $checkdata = $connect->prepare("SELECT * FROM userwallettrans  WHERE orderid = ?");
    $checkdata->bind_param("s",$orderid);
    $checkdata->execute();
    $getresultemail = $checkdata->get_result();
    if ($getresultemail->num_rows > 0) {
        while ($getthedata = $getresultemail->fetch_assoc()) {

            array_push($alldata,$getthedata);
            // array_push($alldata, array("id" => $getthedata['id'], "username" => $getthedata['username'], "addresssentto" => $getthedata['addresssentto'], "transhash" => $getthedata['transhash'], "orderid" => $getthedata['orderid'], "amtusd" => $getthedata['amtusd'], "amttopay" => $getthedata['amttopay'], "ourrate" => $getthedata['ourrate'], "ordertime" => $ordertime, "paytime" => $paytime, "accpayto" => $getthedata['accpayto'], "approvedby" => $getthedata['approvedby'], "paymentref" => $getthedata['paymentref'], "status" => $getthedata['status'], "statustext" => $statustext, "confirmation" => $getthedata['confirmation'], "cointrackid" => $getthedata['cointrackid'], "livecointype" => $getthedata['livecointype'], "transactiontype" => $getthedata['transactiontype'], "systempayref" => $getthedata['systempayref']));

        }
        $alldata = $alldata[0];
    }
        return $alldata;
    
}

function smsgetAllSystemSetting(){
  global $connect;
  $alldata=[];
  $active=1;
  $getdataemail =  $connect->prepare("SELECT * FROM systemsettings WHERE id=?");
  $getdataemail->bind_param("s",$active);
  $getdataemail->execute();
  $getresultemail = $getdataemail->get_result();
  if( $getresultemail->num_rows> 0){
      $getthedata= $getresultemail->fetch_assoc();
      $alldata=$getthedata;
  }
  return $alldata;
}

function sendUserSMS($sendto,$smstosend){// send to is phone number, smsto send (call the function in the smstemplate)
    // 1 Termi, 2 kudi 3 smart solution, 4 simpu
    $smssent=false;
    $activemailsystem=smsgetAllSystemSetting()['activesmssystem'];
    if($activemailsystem==1){
        $smssent=sendWithTermi($sendto,$smstosend);
    }else if($activemailsystem==2){
        $smssent=sendWithKudiSMS($sendto,$smstosend);
    }else if($activemailsystem==3){
        $smssent=sendWithSmartSolution($sendto,$smstosend);
    }else if($activemailsystem==4){
        $smssent= sendWithSimpu($sendto,$smstosend);
    }
    return $smssent;
}
?>