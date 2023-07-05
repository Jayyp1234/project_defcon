<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("apifunctions.php");
require("connectdb.php");
require("constants.php");
require("smstemplate.php");
require("mailtemplate.php");
require("paytemplate.php");
require("cryptotemplate.php");
require("billtemplate.php");
require("notificationtemplate.php");

//5697223950:AAEX2fl_Ex44AMbb5a52nmaBuzZQ3XGJ54U old
$mainCryptoBotIdfor_telegrm="5899747816:AAF5ezXaPLMgPc8jRHtEy3HS9IpyClZ4ExI";
$mainPeerstackBotIdfor_telegram="5899747816:AAF5ezXaPLMgPc8jRHtEy3HS9IpyClZ4ExI";
$mainCardify_notificationBot="5905909928:AAFWonNn8cWnJBjLQT9O5cq2XHvx-cuH9ck";
$mainCardify_notificationBotB="6158782231:AAG4NJile74zRWZOmvwx1Q39J2W3ljm0MoY";
$mainCardify_crash_noti_bot="5829729895:AAHMSAwzutYMQfrjGsbiN9xL_oQCcnOfnww";
$mainCardify_SWAP_noti_bot="6295259181:AAHsmGowo5wSUuhdmWNyjr8GeOXNm-Xx5SM";
$mainCardify_Marketer_noti_bot="6193716092:AAGaQux2vV5BW4tLfdHaNtTog20JqnYtJCk";
$mainCardify_CARD_noti_bot="5723589328:AAGp3ZpuFkPWa23Kle4rHXaK093O7535HpQ";
$mainKYCBotIdfor_telegram="6247233590:AAGm8GLXvIvxVSfD-C09NKaeSY_Q7DuDmdA";
$maincashbackbot_telegram="5472267799:AAEg4Mc1eAU60bbVehQ8uj12kQNquBymQ1A";
$mainCardify_bill_bottelegram="6032885558:AAFn6Q_DJAPRwGj2vw1ksTBe9dVXFmES9UY";
$mainCardify_phoneCall_bottelegram="6286737263:AAEGlsv5thqOwKgxNNKlJ2EbFuUpxnNTXoI";
register_shutdown_function( "notify_crash_handler" );


function redirect($new_location) {
    header("location: ".$new_location);
    exit;
}

function remove_pointzero($amount) {
    // Remove trailing zeros and decimal point
    $formatted_amount = rtrim($amount, '0');
    $formatted_amount = rtrim($formatted_amount, '.');

    return $formatted_amount;
}
function giveuserReferralBonus($user_id,$userrefcode,$username){
        global $connect;
        $active=1;
        $checkdata =  $connect->prepare("SELECT referalpointforusers FROM systemsettings WHERE id=?");
        $checkdata->bind_param("i",$active);
        $checkdata->execute();
        $dresult4 = $checkdata->get_result();
        if($dresult4->num_rows>0){
            $vc_typedata= $dresult4->fetch_assoc();
            $referalpointforusers=$vc_typedata['referalpointforusers'];
            
            // call users who has the user in talk refcode as referby and redeem code has not been given and if the user level is greater than 2
            $notredeemed=0;
            $activeuser=1;
            $levelallowed=2;
            $checkdata =  $connect->prepare("SELECT id FROM users WHERE referalredeem=? AND (referby=? OR referby=?) AND status=? AND userlevel>=?");
            $checkdata->bind_param("issii",$notredeemed,$username,$userrefcode,$activeuser,$levelallowed);
            $checkdata->execute();
            $dresult4 = $checkdata->get_result();
            if($dresult4->num_rows>0){
                        while($vc_typedata2= $dresult4->fetch_assoc()){
                             $refuserid=$vc_typedata2['id'];
                            //  give user cashback
                            if (payAddUserCashbackBalance($user_id,$referalpointforusers)){
                                // store cashback history
                                $cashtranstype=3;
                                $cashbpaid=1;
                                $orderId=" ";
                                $referaluserid=$refuserid;
                                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                $track_id=createOrderidWIthTransData("CB-REF-",$user_id,"cashback_history","userid","cashbackorderid");//createUniqueToken(4,"cashback_history","cashbackorderid","CB-REF-",true,false,false);
                                $cashborderId = $track_id;//createTransUniqueToken("CB-REF", $user_id);
                                $query1 = "INSERT INTO cashback_history (`userid`, `amount`, `trans_type`, `referaluserid`, `transorderid`,  `status`, `cashbackorderid`) VALUES (?,?,?,?,?,?,?)";
                                $addTransaction1 = $connect->prepare($query1);
                                // echo $connect->error;
                                $addTransaction1 ->bind_param("sssssss",$user_id,$referalpointforusers,$cashtranstype,$referaluserid,$orderId, $cashbpaid,$cashborderId);
                                $addTransaction1->execute();
                                // echo $addTransaction1->error;
                                // set redem to 1
                                $done=1;
                                $updatePassQuery = "UPDATE users SET referalredeem=? WHERE id = ?";
                                $updateStmt = $connect->prepare($updatePassQuery);
                                // echo $connect->error;
                                $updateStmt->bind_param('ii',$done,$referaluserid);
                                $updateStmt->execute();
                            }
                            
                                 
                        }
                       
                        
                        
            }
        }
}
function sendPushNotification($deviceToken, $title, $body) {
    $result="";
    if(strlen($deviceToken)>=3){
    $serverKey = 'AAAA8-CH9XI:APA91bEhD8VyZydrvPdYTTcL-LQyNdYOsQMxb2urI9fjzYt-fCyLTFvFY0Lw5I9kB47CT7O51d1B6mhD4GZC8bROIHoZfUjIBXRdkhga_HAZ2e5dEpiAOjujprv4-JOA7OwsVXolC_8J';
    $url = 'https://fcm.googleapis.com/fcm/send';

    $data = [
        'title' => $title,
        'body' => $body,
    ];

    $fields = [
        'to' => $deviceToken,
        'notification' => $data,
        'data' => $data,
    ];

    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    }
    return $result;
}


function replace_allspecial_char_space($string){
    $pattern = '/[^a-zA-Z0-9]+/u'; // Matches any non-alphanumeric characters
$replacement = ' '; // Replaces matches with a space
$cleaned_string = preg_replace($pattern, $replacement, $string);
    
    return $cleaned_string;
}
function getCurrentFullURL(){
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        // Get the server name and port
        $servername = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        // Get the path to the current script
        $path = $_SERVER['PHP_SELF'];
        $getParams = $_SERVER['QUERY_STRING'];
        // ."?".$getParams
        // Combine the above to form the full URL
        $endpoint = $protocol . $servername . ":" . $port . $path;
        return $endpoint;
}
// telegram

function replyuser($chatid, $message_id, $message, $buttonadded, $keyboard,$botkey,$markdown="html")
{
    $path = "https://api.telegram.org/bot$botkey";
    // &parse_mode=html to mke html tag work
//     <b>bold</b>, <strong>bold</strong>
    // <i>italic</i>, <em>italic</em>
    // <a href="http://www.example.com/">inline URL</a>
    // <code>inline fixed-width code</code>
    // <pre>pre-formatted fixed-width code block</pre>
    if (!$buttonadded) {
            //   file_get_contents($path."/sendmessage?chat_id=".$chatid."&reply_to_message_id=".$message_id."&text=$message&parse_mode=$markdown");
        $message=str_replace("%0A","\n",$message);
        $url = $path."/sendmessage";
        $encodedKeyboard = json_encode($keyboard);
        $parameters =
        array(
           'chat_id' => $chatid,
           'text' => $message,
           'reply_to_message_id'=>$message_id,
           'parse_mode'=>"$markdown"
       );
   
        $curld = curl_init();
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        
    } else {
        if(empty($keyboard)){
               $url = $path."/sendmessage";
        $encodedKeyboard = json_encode($keyboard);
        $parameters =
       array(
           'chat_id' => $chatid,
           'text' => $message,
           'reply_to_message_id'=>$message_id,
           'parse_mode'=>"$markdown"
       );
   
        $curld = curl_init();
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        }else{
        $url = $path."/sendmessage";
        $encodedKeyboard = json_encode($keyboard);
        $parameters =
       array(
           'chat_id' => $chatid,
           'text' => $message,
           'reply_to_message_id'=>$message_id,
           'reply_markup' => $encodedKeyboard,
           'parse_mode'=>"$markdown"
       );
   
        $curld = curl_init();
        curl_setopt($curld, CURLOPT_POST, true);
        curl_setopt($curld, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($curld, CURLOPT_URL, $url);
        curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curld);
        curl_close($curld);
        // return $output;
        }
        
    }
}

function sendAdminPeersatckTeleNoti($type,$orderid,$amount,$userid,$merchantid,$bankid,$exchange=0){
      global $connect,$mainPeerstackBotIdfor_telegram;
    // $type 1=withdraw 2= deposit
    $currenttime=time();
    $finalchatid='';
    $finalbotid=$mainPeerstackBotIdfor_telegram;
    // get admin details
    $getexactdata =  $connect->prepare("SELECT admin_id FROM peerstackmerchants WHERE merchant_trackid=?");
    $getexactdata->bind_param("s", $merchantid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $adminidis=$ddatasent['admin_id'];
        $getexactdata =  $connect->prepare("SELECT 	username,telegrampeerchatid,id FROM admin WHERE id=?");
        $getexactdata->bind_param("s",$adminidis);
        $getexactdata->execute();
        $rresult2 = $getexactdata->get_result();
        $ddatasent=$rresult2->fetch_assoc();
        $finalchatid=$ddatasent['telegrampeerchatid'];
        $adminname=$ddatasent['username'];
        $adminselectedid=$ddatasent['id'];
    }
    // get customer details
    $cususername="";
    $getexactdata =  $connect->prepare("SELECT username FROM users WHERE id=?");
    $getexactdata->bind_param("s", $userid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $cususername=$ddatasent['username'];
        
    }
    // get trans details
    $transid="";
    $getexactdata =  $connect->prepare("SELECT id FROM 	userwallettrans WHERE orderid=? AND peerstack_agent=?");
    $getexactdata->bind_param("ss", $orderid,$merchantid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $transid=$ddatasent['id'];
        
    }
    
    if($type==1){
            $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
            $getUser->bind_param("ss",$bankid,$userid);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
            //bank exist
            $row = $result->fetch_assoc();
            $accbnkcode =$row['bankcode'];
            $acctosendto = $row['account_no'];
            $refcode = $row['refcode'];
            $accountname = $row['account_name'];
            $bankname = $row['bank_name'];
            $getUser->close();
            }
            
            if(!empty($finalchatid)&&!empty($finalbotid)){
                if($exchange==1){
                      $response="@*$adminname*\n\n*Exchange System*\n\nPay $amount\n\nUsername-$cususername\nBank-$bankname\nAcc-`$acctosendto`\nAccname-$accountname\n\nRef-`$orderid`";
                }else{
                $response="@*$adminname*\n\nPay $amount\n\nUsername-$cususername\nBank-$bankname\nAcc-`$acctosendto`\nAccname-$accountname\n\nRef-`$orderid`";
                }
                
                $keyboard = [
                'inline_keyboard' => [
                [
                    ['text' => 'Confirm Withdrawal', 'callback_data' => "withdrawit^$adminselectedid^1^$transid^$orderid^$type^$currenttime"],
                ],
                
                ],
                ];
                replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");
            }
    }
    else if($type==2){
        // deposit
        $transtype=2;
        $referenceisis=$orderid;
        // $deposittype 1 succss 3 cancle
                if(!empty($finalchatid)&&!empty($finalbotid)){

            $response="@*$adminname*\n\nUsername $cususername said he has sent $amount with the refrence below, please confirm\n\nRef-`$referenceisis`";
            $keyboard = [
                'inline_keyboard' => [
                [
                    ['text' => 'Confirm Deposit', 'callback_data' => "depositit^$adminselectedid^1^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                 [
                    ['text' => 'User Sent another amount', 'callback_data' => "depositit^$adminselectedid^8^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                  [
                    ['text' => 'Cancel Deposit', 'callback_data' => "depositit^$adminselectedid^3^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                
                ],
                ];
                replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");
        }
    }
        

}


function sendAdminCashbackTeleNoti($type,$orderid,$amount,$userid,$merchantid,$bankid,$exchange=0){
      global $connect,$maincashbackbot_telegram;
    // $type 1=withdraw 2= deposit
    $currenttime=time();
    $finalchatid='';
    $finalbotid=$maincashbackbot_telegram;
    // get admin details
    $getexactdata =  $connect->prepare("SELECT admin_id FROM peerstackmerchants WHERE merchant_trackid=?");
    $getexactdata->bind_param("s", $merchantid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $adminidis=$ddatasent['admin_id'];
        $getexactdata =  $connect->prepare("SELECT 	username,telegramcashbackchatid,id FROM admin WHERE id=?");
        $getexactdata->bind_param("s",$adminidis);
        $getexactdata->execute();
        $rresult2 = $getexactdata->get_result();
        $ddatasent=$rresult2->fetch_assoc();
        $finalchatid=$ddatasent['telegramcashbackchatid'];
        $adminname=$ddatasent['username'];
        $adminselectedid=$ddatasent['id'];
    }
    // get customer details
    $cususername="";
    $getexactdata =  $connect->prepare("SELECT username FROM users WHERE id=?");
    $getexactdata->bind_param("s", $userid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $cususername=$ddatasent['username'];
        
    }
    // get trans details
    $transid="";
    $getexactdata =  $connect->prepare("SELECT id FROM 	userwallettrans WHERE orderid=? AND peerstack_agent=?");
    $getexactdata->bind_param("ss", $orderid,$merchantid);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $num = $rresult2->num_rows ;
    if ($num>0) {
        $ddatasent=$rresult2->fetch_assoc();
        $transid=$ddatasent['id'];
        
    }
    
    if($type==1){
            $getUser = $connect->prepare("SELECT * FROM userbanks WHERE id = ? AND user_id=?");
            $getUser->bind_param("ss",$bankid,$userid);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
            //bank exist
            $row = $result->fetch_assoc();
            $accbnkcode =$row['bankcode'];
            $acctosendto = $row['account_no'];
            $refcode = $row['refcode'];
            $accountname = $row['account_name'];
            $bankname = $row['bank_name'];
            $getUser->close();
            }
            
            if(!empty($finalchatid)&&!empty($finalbotid)){
                if($exchange==1){
                      $response="@*$adminname*\n\n*Exchange System*\n\nPay $amount\n\nUsername-$cususername\nBank-$bankname\nAcc-`$acctosendto`\nAccname-$accountname\n\nRef-`$orderid`";
                }else{
                $response="@*$adminname*\n\nPay $amount\n\nUsername-$cususername\nBank-$bankname\nAcc-`$acctosendto`\nAccname-$accountname\n\nRef-`$orderid`";
                }
                
                $keyboard = [
                'inline_keyboard' => [
                [
                    ['text' => 'Confirm Withdrawal', 'callback_data' => "withdrawit^$adminselectedid^1^$transid^$orderid^$type^$currenttime"],
                ],
                
                ],
                ];
                replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");
            }
    }
    else if($type==2){
        // deposit
        $transtype=2;
        $referenceisis=$orderid;
        // $deposittype 1 succss 3 cancle
                if(!empty($finalchatid)&&!empty($finalbotid)){

            $response="@*$adminname*\n\nUsername $cususername said he has sent $amount with the refrence below, please confirm\n\nRef-`$referenceisis`";
            $keyboard = [
                'inline_keyboard' => [
                [
                    ['text' => 'Confirm Deposit', 'callback_data' => "depositit^$adminselectedid^1^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                 [
                    ['text' => 'User Sent another amount', 'callback_data' => "depositit^$adminselectedid^8^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                  [
                    ['text' => 'Cancel Deposit', 'callback_data' => "depositit^$adminselectedid^3^$transid^$referenceisis^$transtype^$currenttime"],
                ],
                
                ],
                ];
                replyuser($finalchatid, "0", $response, true, $keyboard,$finalbotid,"markdown");
        }
    }
        

}

function deleteAll($dir,$dontdelete)
{
    $dir="/home/cardifyc/public_html/app/$dir";
    $dontdelete="/home/cardifyc/public_html/app/$dontdelete";
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) {
            deleteAll($file,$dontdelete);
        } else {
            unlink($file);
        }
    }
    if($dir!=$dontdelete && file_exists($dir)){
        rmdir($dir);
    }
}


function deleteinFolder($name, $dir)
{
    $data=$name;
    $dirHandle = opendir($dir);
    while ($file = readdir($dirHandle)) {
        if ($file==$data) {
            unlink($dir."/".$file);
        }
    }
    closedir($dirHandle);
}
function createThumbsDynamic($pathToImages, $pathToThumbs, $thumbWidth,$fname) 
{
	$quality=75;
    $info = pathinfo($pathToImages . $fname);
    if (strtolower($info['extension']) == 'jpg'|| strtolower($info['extension']) == 'jpeg') 
    {
      // load image and get image size
      $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = floor($height * ($thumbWidth / $width));
	//$new_height = $thumbHeight;
      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image 
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagejpeg($tmp_img, "{$pathToThumbs}{$fname}",$quality);
    }
	else if (strtolower($info['extension']) == 'png') {
      // load image and get image size
      $img = imagecreatefrompng("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
	  $new_width = $thumbWidth;
	  //$new_height = $thumbHeight;
     $new_height = floor($height * ($thumbWidth / $width));

      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image 
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagepng($tmp_img, "{$pathToThumbs}{$fname}");
	}else if (strtolower($info['extension']) == 'gif') {

      // load image and get image size
      $img = imagecreatefromgif("{$pathToImages}{$fname}");
      $width = imagesx($img);
      $height = imagesy($img);

      // calculate thumbnail size
	  $new_width = $thumbWidth;
	  //$new_height = $thumbHeight;
     $new_height = floor($height * ($thumbWidth / $width));

      // create a new temporary image
      $tmp_img = imagecreatetruecolor($new_width, $new_height);

      // copy and resize old image into new image 
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      // save thumbnail into a file
      imagegif($tmp_img, "{$pathToThumbs}{$fname}");
		
	}
}
function cleanme($data,$specialisallowed=0) {
    global $connect;
    $input = $data;
    // remove all special chracters
    if($specialisallowed==0){
     $input =preg_replace('/[^a-zA-Z0-9_.@-]/',  ' ', $input);
    }
    // This removes all the HTML tags from a string. This will sanitize the input string, and block any HTML tag from entering into the database.
    // filter_var($geeks, FILTER_SANITIZE_STRING);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    $input = trim($input, " \t\n\r");
    // htmlspecialchars() convert the special characters to HTML entities while htmlentities() converts all characters.
    // Convert the predefined characters "<" (less than) and ">" (greater than) to HTML entities:
    $input = htmlspecialchars($input, ENT_QUOTES,'UTF-8');
    // prevent javascript codes, Convert some characters to HTML entities:
    $input = htmlentities($input, ENT_QUOTES, 'UTF-8');
    $input = stripslashes(strip_tags($input));
    $input = mysqli_real_escape_string($connect, $input);

    return $input;
}
function cleanmemini($data) {
    global $connect;
    $input = $data;
    // This removes all the HTML tags from a string. This will sanitize the input string, and block any HTML tag from entering into the database.
    // filter_var($geeks, FILTER_SANITIZE_STRING);
    $input = filter_var($input, FILTER_SANITIZE_STRING);
    $input = htmlspecialchars($input, ENT_QUOTES,'UTF-8');
    $input = trim($input, " \t\n\r");
    $input = stripslashes(strip_tags($input));
    $input = mysqli_real_escape_string($connect, $input);

    return $input;
}

function showpost($text)
{
    $text = str_replace("\\r\\n", "", $text);
    $text = trim(preg_replace('/\t+/', '', $text));
    
    $text = htmlspecialchars_decode($text, ENT_QUOTES);
    $text =html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars_decode($text, ENT_QUOTES);
    $text = nl2br($text);
    return $text;
}
function reduce($text)
{
    $reduce=substr($text, 0, 105);
    $reduce=substr($reduce, 0, strrpos($reduce, " "));
    return $reduce.'...';
}
function getDatetimethatPasssed($endday){
    //3-05-3203
    $todayis=date("Y-m-d");
    $earlier = new DateTime("$endday");
    $later = new DateTime("$todayis");

    $abs_diff = $later->diff($earlier)->format("%a"); //3
    return $abs_diff;
}
function getDaysPassed($vendorsubendday){
    //155555444545
    $datediff =time()-$vendorsubendday;
    // $datediff =$vendorsubendday-$vendorsubstartday;//getting total days btw
    //60 is for minute
    //60 by 60 is for hr
    //60 by 60 by 24 is for days
    //any number by 60 by 60 by 24 is for months
    $difference = round($datediff/(24 * 60 *60));//getting days
    return $difference;
}
function getIp(){  
    //whether ip is from the share internet  
    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
    }  
//whether ip is from the remote address  
    else{  
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}  
function getBrowser() { 
  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version= "";

  //First get the platform?
  if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
  }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
  }elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
  }
$ub="";
  // Next get the name of the useragent yes seperately and for good reason
  if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }elseif(preg_match('/Firefox/i',$u_agent)){
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
  }elseif(preg_match('/OPR/i',$u_agent)){
    $bname = 'Opera';
    $ub = "Opera";
  }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
    $bname = 'Google Chrome';
    $ub = "Chrome";
  }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
    $bname = 'Apple Safari';
    $ub = "Safari";
  }elseif(preg_match('/Netscape/i',$u_agent)){
    $bname = 'Netscape';
    $ub = "Netscape";
  }elseif(preg_match('/Edge/i',$u_agent)){
    $bname = 'Edge';
    $ub = "Edge";
  }elseif(preg_match('/Trident/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }

  // finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) .
')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
  }
  // see how many we have
//   $i = count($matches['browser']);
//   if ($i != 1) {
//     //we will have two since we are not using 'other' argument yet
//     //see if version is before or after the name
//     if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
//         $version= $matches['version'][0];
//     }else {
//         if(isset($matches['version'][1])){
//             $version= $matches['version'][1];
//         }else{
//             $version= $matches['version'];
//         }
//     }
//   }else {
//     $version= $matches['version'][0];
//   }

  // check if we have a number
  if ($version==null || $version=="") {$version="?";}

  return array(
    'userAgent' => $u_agent,
    'name'      => $bname,
    'version'   => $version,
    'platform'  => $platform,
    'pattern'    => $pattern
  );
} 
function getMinBetweentimes($latesttime,$oldtime){
    //8838983498
    $minbtwis=0;
    $subtractit=$latesttime-$oldtime;
    $minbtwis= round($subtractit/(60));
    //60 is for minute
    //60 by 60 is for hr
    //60 by 60 by 24 is for days
    //any number by 60 by 60 by 24 is for months
    return $minbtwis;
}
function getthe24Time($time){
    $data = $time;
  $date =  date('H:i',$data);
    return $date;
}
function isStringHasEmojis($string){
    $emojis_regex =
        '/[\x{0080}-\x{02AF}'
        .'\x{0300}-\x{03FF}'
        .'\x{0600}-\x{06FF}'
        .'\x{0C00}-\x{0C7F}'
        .'\x{1DC0}-\x{1DFF}'
        .'\x{1E00}-\x{1EFF}'
        .'\x{2000}-\x{209F}'
        .'\x{20D0}-\x{214F}'
        .'\x{2190}-\x{23FF}'
        .'\x{2460}-\x{25FF}'
        .'\x{2600}-\x{27EF}'
        .'\x{2900}-\x{29FF}'
        .'\x{2B00}-\x{2BFF}'
        .'\x{2C60}-\x{2C7F}'
        .'\x{2E00}-\x{2E7F}'
        .'\x{3000}-\x{303F}'
        .'\x{A490}-\x{A4CF}'
        .'\x{E000}-\x{F8FF}'
        .'\x{FE00}-\x{FE0F}'
        .'\x{FE30}-\x{FE4F}'
        .'\x{1F000}-\x{1F02F}'
        .'\x{1F0A0}-\x{1F0FF}'
        .'\x{1F100}-\x{1F64F}'
        .'\x{1F680}-\x{1F6FF}'
        .'\x{1F910}-\x{1F96B}'
        .'\x{1F980}-\x{1F9E0}]/u';
    preg_match($emojis_regex, $string, $matches);
    return !empty($matches);
}
function addDaysToTime($day,$time){
    $currentTime = $time;
   //The amount of hours that you want to add.
   $daysToAdd = $day;
   //Convert the hours into seconds.
   $secondsToAdd = $daysToAdd * (24 * 60* 60);
   //Add the seconds onto the current Unix timestamp.
   $newTime = $currentTime + $secondsToAdd;
   return $newTime;
}
function gettheTimeAndDate($time)
{
    $data = $time;
    $date =  date("d/M/Y h:ia", $data);
    return $date;
}

function generate_string($input, $strength) {
    $input_length = strlen($input);
    $random_string = '';
    for ($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}


function convertTime($time)
{
    //88734873489 
    $data = $time;
    $date = strtotime($data);
    return $date;
}

//Has password function starts here
function Password_encrypt($user_pass)
{
    $BlowFish_Format="$2y$10$";
    $salt_len=24;
    $salt=Get_Salt($salt_len);
    $the_format=$BlowFish_Format . $salt;
    
    $hash_pass=crypt($user_pass, $the_format);
    return $hash_pass;
}

function Get_Salt($size)
{
    $Random_string= md5(uniqid(mt_rand(), true));
    
    $Base64_String= base64_encode($Random_string);
    
    $change_string=str_replace('+', '.', $Base64_String);
    
    $salt=substr($change_string, 0, $size);
    
    return $salt;
}

function check_pass($pass, $storedPass) {
    $Hash=crypt($pass, $storedPass);
    if ($Hash===$storedPass) {
        return(true);
    } else {
        return(false);
    }
}
 function validatePhone($phone) {
        $regExp = '/^[0-9]{11}+$/';


        if (preg_match($regExp, $phone)){
            return true;
        }else{
            return false;
        }
    }

    function validateEmail($email) {

        if ( filter_var($email, FILTER_VALIDATE_EMAIL) ){
            return true;
        }else{
            return false;
        }
    }

    function validatePassword($password){
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {
            return false;
        }else{
            return true;
        }
    }
    
    function checkIfUsernameisEmailorPhone($username){
       $phone =  (validatePhone($username)) ? 'phone': null;
       $email = (filter_var($username, FILTER_VALIDATE_EMAIL)) ? 'email' : null;

       if ($phone){
        return $phone;
       }

       if ($email){
        return $email;
       }

    }

    // sets verify type due to user identity given
    function setVerifyType($user_identity){
        if ($user_identity == 'phone'){
            return 2;
        }

        if ($user_identity == 'email'){
            return 1;
        }
    }

    function getIPAddress() {  
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }  

    function getLoc($userIp){
        $url = "http://ipinfo.io/".$userIp."/geo";
        // $json     = file_get_contents($url);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $json = curl_exec($curl);
        curl_close($curl);
        $json     = json_decode($json, true);
        $country  = isset($json['country']) ?  $json['country'] : "";
        $region   = isset($json['region']) ? $json['region'] : "";
        $city     = isset($json['city']) ? $json['city'] : "";
        $location = isset($json['loc']) ? $json['loc'] : "";

        return $location;
    }

    function generatePubKey($strength){
        $input = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $output = generate_string($input, $strength);

        return $output;
    }

    function generateUserPubKey($connect){
        $loop = 0;
        while ($loop == 0){
            $userKey = "CNG".generatePubKey(37);
            if ( checkIfPubKeyisInDB($connect, $userKey) ){
                $loop = 0;
            }else {
                $loop = 1;
                break;
            }
        }

        return $userKey;
    }

    function checkIfPubKeyisInDB($connect, $pubkey) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM users WHERE userpubkey = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $pubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            return true;
        }

        return false;
    }

    function checkIfUserisInDB($connect, $user_id) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM users WHERE id = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            
            return true;
        }

        return false;
    }

    function getUserWithPubKey($connect, $userpubkey) {
        // Check if the email or phone number is already in the database
        $done=1;
        $query = 'SELECT * FROM users WHERE userpubkey = ? AND status=?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("si", $userpubkey,$done);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            
            // check if user bal is negative
            
            check_if_user_is_a_scam($user_id);
            
            return $user_id;
        }
        return false;
    }
    
    function getAdminWithPubKey($connect, $userpubkey) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM admin WHERE adminpubkey = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $userpubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            return $user_id;
        }
        return false;
    }
    
    function CheckifAdminhasPermission($connect, $userpubkey,$is_permitted){
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM admin WHERE adminpubkey = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $userpubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $user_id = $row['id'];
            return true;
        }
        return false;
    }
    
    

    function addSessionLog($conn, $email, $sessioncode, $ipaddress, $browser, $date, $location, $method, $endpoint) {
        // set status to 1
        $status = 1;
        // Insert seesion log query
        $query = 'INSERT INTO usersessionlog (email, sessioncode, ipaddress, browser, date , status, location) Values (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssis", $email, $sessioncode, $ipaddress, $browser, $date, $status, $location);

        if( $stmt->execute() ){
            return true;
        }

        $errordesc =  $stmt->error;
        $linktosolve = 'https://';
        $hint = "500 code internal error, check ur database connections";
        $errorData = returnError7003($errordesc, $linktosolve, $hint);
        $data = returnErrorArray($errordesc, $method, $endpoint, $errorData, null);
        respondInternalError($data);
    }


function generateOrderrefno(){
    $input = "1234756789098765421789512357";
    $strength= 17;
    $id = generate_string($input, $strength);
    return $id;
}
function createUniqueToken($length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters){
        global $connect;
        $loopit=true;
        $input="";
        if($addnumbers){
            $numbers = "1234567890";
            $input=$input.$numbers;
        }
        if($addcapitalletters){
            $capitalletters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $input=$input.$capitalletters;
        }
        if($addsmalllletters){
            $smallletters ="abcdefghijklmnopqrstuvwxyz";
            $input=$input.$smallletters;
        }
        
        $strength= $length;
        $tokenis = generate_string($input, $strength);
        
        while($loopit){
                // check field
            $query = "SELECT id FROM $tablename WHERE $tablecolname = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param("s",$tokenis);
            $stmt->execute();
            $result = $stmt->get_result();
            $num_row = $result->num_rows;
            if ($num_row > 0){
                   $tokenis = generate_string($input, $strength);
            }else{
                $loopit=false; 
            }
        }
        return $tokentag.$tokenis;
}
function createTransUniqueToken($tokentag,$userid){
    global $connect;
    // Naming Nomenclature across system for Order ID
    // 1st part.
    // Abbrevation for type - VC / SC / SUB - 2 or three alphabets
    // SUB means Swap Usdt to bitcoin
    // 2nd part.
    // Timestamp
    // 3rd part.
    // User Id
    // 4th part.
    // Order number
    // IR internal freceive, IT internal transfer ET external transfer
    //  PS paystack 1App 1A 1 App , CIT crypto internal transfer, CIR crypto internal receive, Crypto external send
    //  CVC create vitual card FVC fund virtual card
    // EXC exchange,BKT bank transfer
    // SVC sudo virtual card debit,THT threshold transaction,TH threshold
    // CBT coinbase transaction, VCD virtual card debit, BGT bitgo transactio, CPT coin pay transactin
    // IT00303237586
    $orderid=0;
    $day=date("d",time());
    $month=date("m",time());
    $year=date("y",time());
    $timestamp= $day."".$month."".$year;
    $orderid=  $timestamp."".$userid;

    $query = "SELECT id FROM userwallettrans";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_row = $result->num_rows;
    $orderid.= $num_row+1;

    $loopit=true;
    while($loopit){
            // check field
        $query = "SELECT id FROM userwallettrans WHERE orderid= ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s",$orderid);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
               $orderid = $orderid+1;
        }else{
            $loopit=false; 
        }
    }
    return $tokentag.$orderid;
}

function createOrderidWIthTransData($tokentag,$userid,$tablename,$tableuseridcol,$orderidcol){
    global $connect;
    // Naming Nomenclature across system for Order ID
    // 1st part.
    // Abbrevation for type - VC / SC / SUB - 2 or three alphabets
    // SUB means Swap Usdt to bitcoin
    // 2nd part.
    // Timestamp
    // 3rd part.
    // User Id
    // 4th part.
    // Order number
    // IR internal freceive, IT internal transfer ET external transfer
    //  PS paystack 1App 1A 1 App , CIT crypto internal transfer, CIR crypto internal receive, Crypto external send
    //  CVC create vitual card FVC fund virtual card
    // EXC exchange,BKT bank transfer
    // SVC sudo virtual card debit,THT threshold transaction,TH threshold
    // CBT coinbase transaction, VCD virtual card debit, BGT bitgo transactio, CPT coin pay transactin
    // IT00303237586
    $orderid=0;
    $orderid=  $userid."-";

    $query = "SELECT id FROM $tablename WHERE $tableuseridcol=?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s",$userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_row = $result->num_rows;
    $orderid.= $num_row+1;

    $loopit=true;
    while($loopit){
            // check field
        $query = "SELECT id FROM $tablename WHERE $orderidcol= ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s",$orderid);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
               $orderid = $orderid+1;
        }else{
            $loopit=false; 
        }
    }
    return $tokentag.$orderid;
}

function checkifTokenexist($tablename,$tablecolname,$tokenis){
        global $connect;
        $query = "SELECT $tablecolname FROM $tablename WHERE $tablecolname = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s",$tokenis);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
               $loopit = true;
        }else{
            $loopit=false; 
        }
        return $loopit;
}


function createKey() { 
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 
    while ($i <= 25) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    return $pass; 
}

    function checkifFieldExist($connect, $table, $field, $data){
        // check field
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
           return true;
        }
        return false;
    }

    function checkIfExistWhereMultiple($table, $whereClause, $data = []){
        global $connect;
        $string = "";
        foreach ($data as $item) {
            $string .= "s";
        }
        $query = "SELECT id FROM $table WHERE id > 0 AND $whereClause";
        $stmt = $connect->prepare($query);
        if (count($data) >= 1) {
            $stmt->bind_param($string, ...$data);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0) {
            return $num_row;
        }

        return false;
    }
 function getEmailWithPubKey($connect, $userpubkey) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM users WHERE userpubkey = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $userpubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $email = $row['email'];
            return $email;
        }
        return false;
    }
    function getUserEmail($connect, $userid) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT email FROM users WHERE id = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $email = $row['email'];
            return $email;
        }
        return false;
    }

    function getEmailFromField($table, $field, $data) {
        // Check if the email or phone number is already in the database
        global $connect;
        $query = "SELECT email FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $email = $row['email'];
            return $email;
        }
        return false;
    }

    
     function ConfirmEmailXUsername($connect, $data) {
        // Check if the email or phone number is already in the database
        $query = 'SELECT * FROM users WHERE email = ? || username = ?';
        $stmt = $connect->prepare($query);
        $stmt->bind_param("ss", $data, $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
            $row =  mysqli_fetch_assoc($result);
            $email = $row['id'];
            return $email;
        }
        return false;
    }

function generateNumericOTP($n) {
      
    // Take a generator string which consist of
    // all numeric digits
    $generator = "1357902468";
  
    // Iterate for n-times and pick a single character
    // from generator and append it to $result
      
    // Login for generating a random character from generator
    //     ---generate a random number
    //     ---take modulus of same with length of generator (say i)
    //     ---append the character at place (i) from generator to result
  
    $result = "";
  
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand()%(strlen($generator))), 1);
    }
  
    // Return result
    return $result;
}
    function ReductUserBalance($connect,$userid,$amount,$currency) {
        // Check if the email or phone number is already in the database
        $query = 'UPDATE userwallet SET walletbal = walletbal - ? WHERE userid = ? && currencytag = ?';
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('sii',$amount,$userid,$currency);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    function AddUserBalance($connect,$userid,$amount,$currency) {
        $query = "UPDATE userwallet SET walletbal = walletbal + ? WHERE (currencytag = ? AND userid = ?)";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('sss',$amount,$currency,$userid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }
        else{
            return false;
        }
    }
function sendanymail($emailfrom,$subject,$toemail,$messageinhtml){
    #To Be Done;
}


function getAllSystemSetting(){
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

function getCoinDetails($trackid){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE producttrackid=?");
    $getdata->bind_param("s",$trackid);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getCoinDetailsWithSubTag($trackid){
    global $connect;
    $alldata=[];
    $active=1;
    $getdata =  $connect->prepare("SELECT * FROM coinproducts WHERE subwallettag=? AND status=?");
    $getdata->bind_param("ss",$trackid,$active);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getSendCoinDetails($trackid){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE producttrackid=?");
    $getdata->bind_param("s",$trackid);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getSendCoinDetailsByTag($trackid){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE subwallettag=?");
    $getdata->bind_param("s",$trackid);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getSendCoin_Security($subwallettag){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinsystem_security WHERE subwallettag=?");
    $getdata->bind_param("s",$subwallettag);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getSendCoin_Security_Level($subwallettag,$level){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinsystem_security WHERE subwallettag=? AND level=?");
    $getdata->bind_param("ss",$subwallettag,$level);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getSendCoinDetailsByTid($trackid){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM coinproducts_send WHERE producttrackid=?");
    $getdata->bind_param("s",$trackid);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getUserMainDetails($id){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM users WHERE id=?");
    $getdata->bind_param("s",$id);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getExchangeCurrencyDetails($trackid){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM exchangecurrency WHERE trackid=?");
    $getdata->bind_param("s",$trackid);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function getCurrencyDetails($currency){
    global $connect;
    $alldata=[];
    $getdata =  $connect->prepare("SELECT * FROM currencysystem WHERE currencytag=?");
    $getdata->bind_param("s",$currency);
    $getdata->execute();
    $dresult = $getdata->get_result();
    if ($dresult->num_rows > 0) {
        $getthedata= $dresult->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function send_call_otp($otp,$sendto,$expire,$trackid,$userid,$new=1){
    
    global $mainCardify_phoneCall_bottelegram,$connect;
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_phone_call,id FROM admin WHERE telegram_phone_call!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_phone_call'];
    
    $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?"); 
    $sysgetdata->bind_param("s",$userid);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    // check if user is sending to himself
    $datais=$dsysresult7->fetch_assoc();
    $ussernamesenttomail=$datais['email'];
    $usersenttophone=$datais['phoneno'];
    $userusername=$datais['username'];
    
    if($new==1){
    $idon=0;
    $insert_data = $connect->prepare("INSERT INTO phone_otp_call(otp_tid,status) VALUES (?,?)");
    $insert_data->bind_param("ss", $trackid, $idon);
    $insert_data->execute();
    $insert_data->close();
    }
    
    $response="*OTP CALL REQUESTED*\n\nUser: *$userusername*\nPhone number: `$sendto`\nOTP: `$otp`\nExpires in: $expire";
    $keyboard = [
    'inline_keyboard' => [
    [
    ['text' => 'Confirm', 'callback_data' => "otp_verification^1^$trackid"],
    ],
    [
    ['text' => 'Extend OTP Expire time', 'callback_data' => "otp_verification^2^$trackid"],
    ],
    
    ],
    ];
    
    // 

    $botidtouse=$mainCardify_phoneCall_bottelegram;
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
}
function notify_admin_noti_b_bot($message,$userid){
    
    global $mainCardify_notificationBotB,$connect;
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_notification_bot2,id FROM admin WHERE 	telegram_notification_bot2!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_notification_bot2'];
    
    $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
    $sysgetdata->bind_param("s",$userid);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    // check if user is sending to himself
    $datais=$dsysresult7->fetch_assoc();
    $ussernamesenttomail=$datais['email'];
    $usersenttophone=$datais['phoneno'];
    $userusername=$datais['username'];

    $botidtouse=$mainCardify_notificationBotB;
    $keyboard= [];
    $response="*GENERAL NOTIFICATION*\n\nUser: *$userusername*\nEmail: *$ussernamesenttomail*\n$message";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
}
function notify_admin_bills_b_bot($message,$userid,$wallettype,$billtype){
    
    global $mainCardify_bill_bottelegram,$connect;
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_notification_bills,id FROM admin WHERE 	telegram_notification_bills!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_notification_bills'];
    
    $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
    $sysgetdata->bind_param("s",$userid);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    // check if user is sending to himself
    $datais=$dsysresult7->fetch_assoc();
    $ussernamesenttomail=$datais['email'];
    $usersenttophone=$datais['phoneno'];
    $userusername=$datais['username'];
    $botidtouse=$mainCardify_bill_bottelegram;
    $keyboard= [];
    $response="*BILLS NOTIFICATION*\n\n*User:* $userusername \n*Email:* $ussernamesenttomail\n*Message:* $message\n*Wallet:* $wallettype\n*Type:* $billtype";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
}

function check_if_user_is_a_scam($user_id){
    global $connect;
       $negativeuser=0;
            $amount=0;
            $getUser = $connect->prepare("SELECT id FROM userwallet WHERE walletbal < ? AND userid = ?");
            $getUser->bind_param('ds',$amount,$user_id);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                $negativeuser=1;
                // notify admin and ban user
                $ban=0;
                $updatePassQuery = "UPDATE users SET  status=? WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('ii',$ban,$user_id);
                $updateStmt->execute();
                
                // notify admin
                $message="@habnarm1 The user with this notification has negative balance, please check asap and see why this occured";
                notify_admin_noti_b_bot($message,$user_id);
            }
            $getUser = $connect->prepare("SELECT id FROM usersubwallet WHERE walletbal < ? AND userid = ?");
            $getUser->bind_param('ds',$amount,$user_id);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                      // notify admin and ban user
                $negativeuser=1;
                $ban=0;
                $updatePassQuery = "UPDATE users SET  status=? WHERE id = ?";
                $updateStmt = $connect->prepare($updatePassQuery);
                $updateStmt->bind_param('ii',$ban,$user_id);
                $updateStmt->execute();
                
                 $message="@habnarm1 The user with this notification has negative balance, please check asap and see why this occured";
                notify_admin_noti_b_bot($message,$user_id);
            }
            return $negativeuser;
}

function check_if_user_has_done_trans_in1($user_id){
            global $connect;
            $negativeuser=false;
            $amount=0;
            $getUser = $connect->prepare("SELECT created_at FROM userwallettrans WHERE userid=? ORDER BY id DESC LIMIT 1");
            $getUser->bind_param('i',$user_id);
            $getUser->execute();
            $result = $getUser->get_result();
            if($result->num_rows > 0){
                $row= $result->fetch_assoc();
                $time = strtotime($row['created_at']);
                $differenceinseconds= time() - $time;
                $minute = round($differenceinseconds/60);
                if($differenceinseconds<30){
                    $negativeuser=true;
                }
            }
            return $negativeuser;
}
 
function addnotification($userid,$message,$type,$ref,$status){
    global $connect,$mainCardify_notificationBot;
    $code="";
    $userid= cleanme($userid,1);
    $message= showpost(cleanme($message,1));
    $type= cleanme($type,1);
    $ref= cleanme($ref,1);
    $status= cleanme($status,1);
    $code= createUniqueToken(6,"usernotification","notificationcode","",true,true,true);
    
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_notification_bot,id FROM admin WHERE 	telegram_notification_bot!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_notification_bot'];
    
    $sysgetdata =  $connect->prepare("SELECT email,phoneno,username FROM users WHERE id=?");
    $sysgetdata->bind_param("s",$userid);
    $sysgetdata->execute();
    $dsysresult7 = $sysgetdata->get_result();
    // check if user is sending to himself
    $datais=$dsysresult7->fetch_assoc();
    $ussernamesenttomail=$datais['email'];
    $usersenttophone=$datais['phoneno'];
    $userusername=$datais['username'];
        
    $botidtouse=$mainCardify_notificationBot;
    $keyboard= [];
    $response="*GENERAL NOTIFICATION*\n\nUser: *$userusername*\nEmail: *$ussernamesenttomail*\n$message";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
    
    
    
    
    $query = 'INSERT INTO usernotification (userid, notificationtext,notificationtype,orderrefid,notificationstatus,notificationcode)  Values (?, ?, ?, ?, ?, ?)';
    $stmt = $connect->prepare($query);
    $stmt->bind_param("ssssss",$userid, $message,$type,$ref,$status,$code);
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
}



function checkIfIsAdmin($connect, $pubkey){
    $adminQuery = 'SELECT * FROM admin where adminpubkey = ?';
    $adminStmt = $connect->prepare($adminQuery);
    $adminStmt->bind_param("s", $pubkey);
    $adminStmt->execute();
    $result = $adminStmt->get_result();
    $num_row = $result->num_rows;

    if ($num_row > 0){
        $row = $result->fetch_assoc();

        $adminId = $row['id'];
        return $adminId;
    }
    return false;
}

function checkIfIsMarketer($connect, $pubkey){
    $adminQuery = 'SELECT * FROM marketers where adminpubkey = ?';
    $adminStmt = $connect->prepare($adminQuery);
    $adminStmt->bind_param("s", $pubkey);
    $adminStmt->execute();
    $result = $adminStmt->get_result();
    $num_row = $result->num_rows;

    if ($num_row > 0){
        $row = $result->fetch_assoc();

        $adminId = $row['id'];
        return $adminId;
    }
    return false;
}

function notify_crash_handler(){
    global $connect,$mainCardify_crash_noti_bot;
      
    $errfile = "unknown file";
    $errstr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errline = 0;

    $error = error_get_last();
    if($error !== NULL) {
            $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_crash_noti_bot,id FROM admin WHERE telegram_crash_noti_bot!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_crash_noti_bot'];
    // https://www.php.net/manual/en/errorfunc.constants.php
        $errno   = $error["type"];
        if($errno ==1){
            $errno ="Fatal and code has stopped";
        }else         if($errno ==2){
            $errno ="Warning but code did not stop";
        }
        $errfile =str_replace("public_html","",$error["file"]);
        $errline = $error["line"];
        $errstr  = preg_replace('/[^A-Za-z0-9\-]/', ' ',$error["message"]);
    
    $botidtouse=$mainCardify_crash_noti_bot;
    $keyboard= [];
    $response="@habnarm1 \n*CRASH NOTIFICATION*\n\nFile: $errfile\nType:$errno\nLine:$errline\nText:$errstr";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
    }
}


function system_notify_crash_handler($message,$from){
    global $connect,$mainCardify_crash_noti_bot;
      
    $adminidis=0;
    $getexactdata =  $connect->prepare("SELECT 	username,telegram_crash_noti_bot,id FROM admin WHERE telegram_crash_noti_bot!=?");
    $getexactdata->bind_param("s",$adminidis);
    $getexactdata->execute();
    $rresult2 = $getexactdata->get_result();
    $ddatasent=$rresult2->fetch_assoc();
    $chatId=$ddatasent['telegram_crash_noti_bot'];

    $errstr  = preg_replace('/[^A-Za-z0-9\-]/', ' ',$message);
    
    $botidtouse=$mainCardify_crash_noti_bot;
    $keyboard= [];
    $response="@habnarm1 \n*WORK FLOW CRASH*\n\nFrom: $from\nText:$errstr";
    replyuser($chatId, "0", $response,true, $keyboard, $botidtouse, "markdown");
}

function getUserFullname($connect, $userid){
        $query = "SELECT  `email`, `fname`, `lname` FROM `users` WHERE `id` = ?";
        $getUser = $connect->prepare($query);
        $getUser->bind_param("s", $userid);
        $getUser->execute();
        $result = $getUser->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row = $result->fetch_assoc();

            $fullname = $row['fname']. " ". $row['lname'];
        }
        else{
            $fullname = "";
        }

        return $fullname;

}

function countRow( $table, $field){
    global $connect;
    // check field
    $query = "SELECT $field FROM $table";
    $countRow = $connect->prepare($query);
    $countRow->execute();
    $result = $countRow->get_result();
    $num_row = $result->num_rows;

    if ($num_row > 0){
       return $num_row;
    }

    return 0;
} 
function countRowWithParam($table, $field, $data){
    global $connect;
    // check field
    $query = "SELECT id FROM $table WHERE $field = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $data);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_row = $result->num_rows;

    if ($num_row > 0){
       return $num_row;
    }

    return 0;
}

    function sumRow($table, $fieldToSum, $wherefield, $data ){
        global $connect;
        // check field
        //SELECT SUM(`walletbal`) as total FROM `userwallet` WHERE `currencytag` = "NGNT55"
        $query = "SELECT SUM($fieldToSum) AS total FROM $table WHERE $wherefield = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row = $result->fetch_assoc();
            $total = $row['total'];
            return $total;
        }

        return 0;
    }

    function countRowWhere($table, $where, $data){
        global $connect;
        // check field
        $query = "SELECT id FROM $table $where";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
           return $num_row;
        }

        return "0";
    }

    function countDistinct( $table, $field){
        global $connect;
        // check field
        $query = "SELECT id FROM $table GROUP BY  $field";
        $stmt = $connect->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
           return $num_row;
        }

        return "0";
    }
    
    
    // Team 2 Functions
    function minutesToAdd($minsToAdd){
        $mins = time() + $minsToAdd;
        return $mins;
        
    }

    function checkExpiry($expireAt){
        $currentTime = time();
        if($expireAt >= $currentTime){
            return true;
        }else {
            return false;
        }
    }

    function generateShortKey($strength){
        $input = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $output = generate_string($input, $strength);

        return $output;
    }

    function generateUniqueShortKey($connect, $tableName, $field){
        $loop = 0;
        while ($loop == 0){
            $userKey = "CNG".generateShortKey(5);
            if ( checkIfCodeisInDB($connect, $tableName, $field ,$userKey) ){
                $loop = 0;
            }else {
                $loop = 1;
                break;
            }
        }

        return $userKey;
    }

    function generateUniqueKey($connect, $tableName, $strength, $field){
        $loop = 0;
        while ($loop == 0){
            $userKey = "CNG-PDT".generateShortKey($strength);
            if ( checkIfCodeisInDB($connect, $tableName, $field ,$userKey) ){
                $loop = 0;
            }else {
                $loop = 1;
                break;
            }
        }

        return $userKey;
    }

    function generateNumericKey($strength){
        $input = "01234567890987654321";
        $output = generate_string($input, $strength);

        return $output;
    }
    function generateUniqueNumericKey($connect, $tableName, $field, $strength){
        $loop = 0;
        while ($loop == 0){
            $key = generateNumericKey($strength);
            if ( checkIfCodeisInDB($connect, $tableName, $field ,$key) ){
                $loop = 0;
            }else {
                $loop = 1;
                break;
            }
        }

        return $key;
    }

    function checkifFieldisUnique($connect, $table, $field, $phone){
        // check field
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $phone );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
           $error = $field. " already exist";
           return $error ;
        }
    }
    function getNameFromField($table, $field, $data){
        global $connect;
        // check field
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row = $result->fetch_assoc();
            $name = $row['name'];
            return $name;
        }

        return false;
    }

    function checkIfExist($connect, $table, $field, $data){
        // check field
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
           return true;
        }

        return false;
    }

    function getProductImage($connect, $table, $field, $data){
        // check field
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $all_images = [];
            while ($row = $result->fetch_assoc()){
                $image = $row['name'];
                array_push($all_images, array('image' => $image));
            }
           return $all_images;
        }

        return false;
    }

    function getFieldsDetails( $table, $field, $data){
        global $connect;
        $query = "SELECT * FROM $table WHERE $field = ?";
        
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $value = [];
            while ($row = $result->fetch_assoc()){
                $value = array('details' => $row);
            }
           return $value;
        }

        return false;
    }
    
    function activate_voucher_back($id){
        global $connect;
        $usedup=1;
        $sql = "UPDATE bill_voucher_prices SET status= ? WHERE id =?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param('ii',$usedup,$id);
        $stmt->execute();
    }

    function uploadImage($file, $path, $endpoint, $method){
        $img_name = $file['name'];
        $img_size = $file['size'];
        $tmp_name = $file['tmp_name'];
        $error = $file['error'];


        if ($error === 0){
            if ($img_size > 2097152) {
                $errordesc= "Image is too large";
                $linktosolve="htps://";
                $hint=["Ensure to use the method stated in the documentation."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text= "Image is too large";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }else{
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "webp");

               
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $path = "../../../assets/images/$path/";
                    $new_img_name = uniqid("CNG-IMG-", true). "." . $img_ex_lc;
                    $img_upload_path =  $path. $new_img_name;
                    if ( move_uploaded_file($tmp_name, $img_upload_path) ){
                        return $new_img_name;
                    }
                }else{
            
                    $errordesc= "Image type not allowed";
                    $linktosolve="htps://";
                    $hint=["Ensure to use the method stated in the documentation."];
                    $errordata=returnError7003($errordesc,$linktosolve,$hint);
                    $text= "Image type not allowed";
                    $method=getenv('REQUEST_METHOD');
                    $data=returnErrorArray($text,$method,$endpoint,$errordata);
                    respondBadRequest($data);
                }
            }
        }else{

            $errordesc= "Unknown error occurred";
            $linktosolve="htps://";
            $hint=["Ensure to use the method stated in the documentation."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text= "Unknown error occurred";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
    }

    function checkImg($file, $endpoint, $method){
        $img_size = $file['size'];
        $tmp_name = $file['tmp_name'];
        $error = $file['error'];

        if ($error === 0){
            if ($img_size > 2097152) {
                $errordesc= "Image is too large";
                $linktosolve="htps://";
                $hint=["Ensure to use the method stated in the documentation."];
                $errordata=returnError7003($errordesc,$linktosolve,$hint);
                $text= "Image is too large";
                $method=getenv('REQUEST_METHOD');
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondBadRequest($data);
            }
            
            return true;
        }else{

            $errordesc= "Unknown error occurred";
            $linktosolve="htps://";
            $hint=["Ensure to use the method stated in the documentation."];
            $errordata=returnError7003($errordesc,$linktosolve,$hint);
            $text= "Unknown error occurred";
            $method=getenv('REQUEST_METHOD');
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondBadRequest($data);

        }
    }

    function getShortCode($userIp){
        $url = "http://ipinfo.io/".$userIp."/geo";
        $json     = file_get_contents($url);
        $json     = json_decode($json, true);
        // $country  = ($json['country']) ?  $json['country'] : "";
        // $region   = ($json['region']) ? $json['region'] : "";
        // $city     = ($json['city']) ? $json['city'] : "";
        
        if (array_key_exists('country', $json) ){
            $country = ($json['country']) ? $json['country'] : "";

        }else{
            $country = "";
        }

        return $country;
    }

    function getAverageProductReview($product_id){
        global $connect;
        $query = "SELECT `ratestar` FROM `reviews` WHERE `product_id` = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $sum = 0;
            while($row = $result->fetch_assoc()){
                $sum += $row['ratestar'];
            }
            $average = number_format((float)($sum / $num_row), 2, '.', '');

            return array('num_of_reviews' => $num_row, "aver_review" => $average);

        }

        return false;
    }

    function getReviews($product_id){
        global $connect;
        // check field
        $query = "SELECT * FROM `reviews` WHERE `product_id` = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $product_id );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $reviews = [];
            $review_values = [];
            $sum_review = 0;
            while ($row = $result->fetch_assoc()){
                $user_id = $row['userid'];
                $fullname = getUserFullname($connect, $user_id);
                $user_photo = getUserProfilePic($connect, $user_id);
                array_push($review_values, $row['review'] );
                if ( $row['review'] == 1){
                    $review = "Cleanliness";
                }
                if ( $row['review'] == 2){
                    $review = "Communication";
                }
                if ( $row['review'] == 3){
                    $review = "Check-in";
                }
                if ( $row['review'] == 4){
                    $review = "Accuracy";
                }
                if ( $row['review'] == 5){
                    $review = "Location";
                }
                if ( $row['review'] == 6){
                    $review = "Value";
                }
                $review_details = $row['review_details'];
                $ratestar = $row['ratestar'];
                $sum_review = $sum_review + $ratestar;
                $created = ($row['created_at']) ? gettheTimeAndDate(strtotime($row['created_at'])) : null;
                array_push( $reviews ,array(
                    "user_id" => $user_id,
                    "user_fullname" => ($fullname)? $fullname : "",
                    "photo" => ($user_photo)? $user_photo : null,
                    'review_value' => $row['review'],
                    "review" => $review,
                    "review_details" => $review_details,
                    "rate_star" => $ratestar. ".0",
                    "created" => $created
                ));
            }
            $unique = array_unique( $review_values );
            $results = [];
            foreach ( $unique as $item ){
                $item_revew_sum = 0; 
                $count = 0;
                foreach ( $reviews as $values){
                    if ($values['review_value'] == $item ){
                        $count = $count + 1;
                        $item_revew_sum = $item_revew_sum + $values['rate_star'];
                    }
                }
                if ( $item == 1){
                    $review = "Cleanliness";
                }
                if ( $item == 2){
                    $review = "Communication";
                }
                if ( $item == 3){
                    $review = "Check-in";
                }
                if ( $item == 4){
                    $review = "Accuracy";
                }
                if ( $item == 5){
                    $review = "Location";
                }
                if ( $item == 6){
                    $review = "Value";
                }
                $item_average = round( $item_revew_sum / $count, 2);
                $item_perc = ($item_average / 5) * 100;;
                array_push($results, array(
                    'review' => $review,
                    'av_rate' => number_format( (float) $item_average, 1, ".", "" ),
                    'item_perc' => $item_perc
                ));
            }
            $average = round( $sum_review / $num_row, 1);
            $percentage = ($average / 5) * 100;

            $reviews = array("reviews" => $reviews, "average_review" => $average, "num_of_reviews" => $num_row, "percentage" => $percentage, 'review_head' => $results );

            return $reviews;
        }

        return false;
    }

    function getUserProfilePic($connect, $userid){
        $query = "SELECT `profile_pic` FROM `users` WHERE `id` = ?";
        $getUser = $connect->prepare($query);
        $getUser->bind_param("s", $userid);
        $getUser->execute();
        $result = $getUser->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row = $result->fetch_assoc();

            $photo = $row['profile_pic'];
        }
        else{
            $photo = false;
        }

        return $photo;

    }

    function getRelatedProducts($field, $data, $product_id ,$limit){
        global $connect;
        $query = "SELECT `id`, `product_id`, `price` ,`name`, `category_id`, `sub_category`, `method`, `image`,`featured`, `discount` `status` FROM `products` WHERE $field = ? AND product_id != ? LIMIT ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("sss", $data, $product_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $products = [];
            while ($row = $result->fetch_assoc()){
                $name = $row['name'];
                $product_id= $row['product_id'];
                $image = $row['image'];
                $featured = $row['featured'];
                $price = $row['price'];
                $average_review = getAverageProductReview($product_id);
                //$discount = $row['discount'];
                array_push($products, array(
                    'name' => $name, 
                    'product_id' => $product_id, 
                    'image' => $image, 
                    'featured'=>$featured,
                    'price' => $price,
                    'average_review' => ( $average_review )? $average_review : null
                ));
            }
           return $products;
        }

        return false;
    }
   
   

    function unique_multi_array($array, $key) { 
        $temp_array = array(); 
        $i = 0; 
        $key_array = array(); 
        
        foreach($array as $val) { 
            if (!in_array($val[$key], $key_array)) { 
                $key_array[$i] = $val[$key]; 
                $temp_array[$i] = $val; 
            } 
            $i++; 
        } 
        return $temp_array; 
    }



    function generateWallet($strength){
        $input = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $output = generate_string($input, $strength);

        return $output; 
    }

    function addMinToTime($min){
        $now = time();
        $ten_minutes = $now + ($min * 60);
        // $startDate = date('m-d-Y H:i:s', $now);
        $endDate = strtotime(date('m-d-Y H:i:s', $ten_minutes));

        return $endDate;
    }

    function getColumnFromField($tablename, $column ,$fieldname, $fieldvalue){
        global $connect;

        $query = "SELECT $column FROM $tablename WHERE $fieldname = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $fieldvalue );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        
        if ($num_row > 0){
            $row = $result->fetch_assoc();
            $value = $row["$column"];

            return $value;

        }else{
            return false;
        }


    }

    function getMultiColumnFromField($tablename, $columns ,$fieldname, $fieldvalue){
        global $connect;

        $query = "SELECT $columns FROM $tablename WHERE $fieldname = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $fieldvalue);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0) {
            $row = $result->fetch_assoc();
            // print_r($row);
            return $row;
        } else {
            return false;
        } 
    }

     function getFromField($table, $field, $data){
        global $connect;
        $query = "SELECT * FROM $table WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $data );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $row = $result->fetch_assoc();
            return $row;
        }

        return false;
    }
    
    function getCityFromState($state){
        $string=strtolower($state);
        $data="";
        if(str_contains($string, 'abia')){
           $data="Umuahia"; 
        }else if(str_contains($string, 'adamawa')){
           $data="Yola"; 
        }else if(str_contains($string, 'akwa')){
           $data="Uyo"; 
        }else if(str_contains($string, 'anambra')){
           $data="Awka"; 
        }else if(str_contains($string, 'bauchi')){
           $data="Bauchi"; 
        }else if(str_contains($string, 'bayelsa')){
           $data="Yenagoa"; 
        }else if(str_contains($string, 'benue')){
           $data="Makurdi"; 
        }else if(str_contains($string, 'borno')){
           $data="Maiduguri"; 
        }else if(str_contains($string, 'cross river')){
           $data="Calabar"; 
        }else if(str_contains($string, 'delta')){
           $data="Asaba"; 
        }else if(str_contains($string, 'ebonyi')){
           $data="abakaliki"; 
        }else if(str_contains($string, 'edo')){
           $data="Benin City"; 
        }else if(str_contains($string, 'ekiti')){
           $data="Ado Ekiti"; 
        }else if(str_contains($string, 'enugu')){
           $data="Enugu"; 
        }else if(str_contains($string, 'gombe')){
           $data="Gombe"; 
        }else if(str_contains($string, 'imo')){
           $data="Owerri"; 
        }else if(str_contains($string, 'jigawa')){
           $data="Dutse"; 
        }else if(str_contains($string, 'kaduna')){
           $data="Kaduna"; 
        }else if(str_contains($string, 'kano')){
           $data="kano"; 
        }else if(str_contains($string, 'katsina')){
           $data="Katsina"; 
        }else if(str_contains($string, 'kebbi')){
           $data="Birnin Kebbi"; 
        }else if(str_contains($string, 'kogi')){
           $data="Lokoja"; 
        }else if(str_contains($string, 'kwara')){
           $data="Ilorin"; 
        }else if(str_contains($string, 'lagos')){
           $data="ikeja"; 
        }else if(str_contains($string, 'nasarawa')){
           $data="Lafia"; 
        }else if(str_contains($string, 'niger')){
           $data="Minna"; 
        }else if(str_contains($string, 'ogun')){
           $data="abeokuta"; 
        }else if(str_contains($string, 'ondo')){
           $data="Akure"; 
        }else if(str_contains($string, 'osun')){
           $data="Oshogbo"; 
        }else if(str_contains($string, 'oyo')){
           $data="Ibadan"; 
        }else if(str_contains($string, 'plateau')){
           $data="Jos"; 
        }else if(str_contains($string, 'rivers')){
           $data="Port Harcourt"; 
        }else if(str_contains($string, 'sokoto')){
           $data="Sokoto"; 
        }else if(str_contains($string, 'taraba')){
           $data="Jalingo"; 
        }else if(str_contains($string, 'yobe')){
           $data="Damaturu"; 
        }else if(str_contains($string, 'zamfara')){
           $data="Gusau"; 
        }else if(str_contains($string, 'fct')){
           $data="Abuja"; 
        }else if(str_contains($string, 'federal')){
           $data="Abuja"; 
        }
        return $data;
    }
    
    function getPostalCodeFromState($state){
        $string=strtolower($state);
        $data="";
        if(str_contains($string, 'abia')){
           $data="440001"; 
        }else if(str_contains($string, 'adamawa')){
           $data="640001"; 
        }else if(str_contains($string, 'akwa')){
           $data="520001"; 
        }else if(str_contains($string, 'anambra')){
           $data="420001"; 
        }else if(str_contains($string, 'bauchi')){
           $data="740001"; 
        }else if(str_contains($string, 'bayelsa')){
           $data="561001"; 
        }else if(str_contains($string, 'benue')){
           $data="970001"; 
        }else if(str_contains($string, 'borno')){
           $data="600001"; 
        }else if(str_contains($string, 'river')){
           $data="540001"; 
        }else if(str_contains($string, 'delta')){
           $data="320001"; 
        }else if(str_contains($string, 'ebonyi')){
           $data="840001"; 
        }else if(str_contains($string, 'edo')){
           $data="300001"; 
        }else if(str_contains($string, 'ekiti')){
           $data="360001"; 
        }else if(str_contains($string, 'enugu')){
           $data="400001"; 
        }else if(str_contains($string, 'gombe')){
           $data="760001"; 
        }else if(str_contains($string, 'imo')){
           $data="460001"; 
        }else if(str_contains($string, 'jigawa')){
           $data="720001"; 
        }else if(str_contains($string, 'kaduna')){
           $data="700001"; 
        }else if(str_contains($string, 'kano')){
           $data="800001"; 
        }else if(str_contains($string, 'katsina')){
           $data="820001"; 
        }else if(str_contains($string, 'kebbi')){
           $data="860001"; 
        }else if(str_contains($string, 'kogi')){
           $data="260001"; 
        }else if(str_contains($string, 'kwara')){
           $data="240001"; 
        }else if(str_contains($string, 'lagos')){
           $data="100001"; 
        }else if(str_contains($string, 'nasarawa')){
           $data="962001"; 
        }else if(str_contains($string, 'niger')){
           $data="920001"; 
        }else if(str_contains($string, 'ogun')){
           $data="110001"; 
        }else if(str_contains($string, 'ondo')){
           $data="340001"; 
        }else if(str_contains($string, 'osun')){
           $data="230001"; 
        }else if(str_contains($string, 'oyo')){
           $data="200001"; 
        }else if(str_contains($string, 'plateau')){
           $data="930001"; 
        }else if(str_contains($string, 'rivers')){
           $data="500001"; 
        }else if(str_contains($string, 'sokoto')){
           $data="840001"; 
        }else if(str_contains($string, 'taraba')){
           $data="660001"; 
        }else if(str_contains($string, 'yobe')){
           $data="320001"; 
        }else if(str_contains($string, 'zamfara')){
           $data="860001"; 
        }else if(str_contains($string, 'fct')){
           $data="900001"; 
        }else if(str_contains($string, 'federal')){
           $data="900001"; 
        }
        return $data;
    }

    function floorp($val, $precision){
        $mult = pow(10, $precision); // Can be cached in lookup table        
        return floor($val * $mult) / $mult;
    }

    // End of Validating Using Regular Expression
   

    // Team 2 Functions End

  
    function  giveMarketerPointForEachUsers($userid,$transtype,$orderid){
       global $connect;
        // $transtype 1 Wallet, 2 Card ,3 Swap, 4 Bills
        // get team tag
        // give team point fee
        // save point history
        
        // CHECK IF TRANS IS SUCCESSFUL and bonus not already given to marketers
        $myloc=1;
        $sysgetdata =  $connect->prepare("SELECT id FROM userwallettrans WHERE orderid=? AND status=? AND userid=?");
        $sysgetdata->bind_param("sii", $orderid,$myloc,$userid);
        $sysgetdata->execute();
        $dsysresult7 = $sysgetdata->get_result();
        if($dsysresult7->num_rows>0){
            
        $sysgetdata =  $connect->prepare("SELECT id FROM marketer_bonus_history WHERE orderid=?");
        $sysgetdata->bind_param("s", $orderid);
        $sysgetdata->execute();
        $dsysresult7 = $sysgetdata->get_result();
        if($dsysresult7->num_rows==0){
                $amounttoPay=0;
                // get how much point cost
                $myloc=1;
                $sysgetdata =  $connect->prepare("SELECT marketer_1_point_cost FROM systemsettings WHERE id=?");
                $sysgetdata->bind_param("s", $myloc);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->fetch_assoc();
                $marketer_1_point_cost=$getsys['marketer_1_point_cost'];
                $sysgetdata->close();
               
                // GET POINT TO GIVE MARKETER
                $sysgetdata =  $connect->prepare("SELECT point FROM marketer_transtype_point WHERE transtype=?");
                $sysgetdata->bind_param("s", $transtype);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->fetch_assoc();
                $transpoint=$getsys['point'];
                $sysgetdata->close();
            
                // GET AMOUNT TO GIVE
                $amounttoPay=$transpoint*$marketer_1_point_cost;
           
                // GET TEAM TAG FROM USER
                $sysgetdata =  $connect->prepare("SELECT market_team_tag FROM users WHERE id=?");
                $sysgetdata->bind_param("s",$userid);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys = $dsysresult7->fetch_assoc();
                $teamtagIs=$getsys['market_team_tag'];
                $sysgetdata->close();
                
                // GIVE TEAM FOUND
                // $teamtagIss="%{$teamtagIs}%";
                $updateStmt = $connect->prepare("UPDATE marketers SET balance=balance+? WHERE team_tag =?");
                $updateStmt->bind_param("is",$amounttoPay,$teamtagIs);
                $updateStmt->execute();
                if ( $updateStmt->affected_rows > 0 ){
                    // SAVE history of fund given
                    $insert_data = $connect->prepare("INSERT INTO marketer_bonus_history (teamtag,userid,amount,point,cost_of_point,transtype,orderid) VALUES (?,?,?,?,?,?,?)");
                    $insert_data->bind_param("sssssss",$teamtagIs, $userid, $amounttoPay, $transpoint,$marketer_1_point_cost,$transtype,$orderid);
                    $insert_data->execute();
                    $insert_data->close();
                }
            }    
        }

    }

    function validateDate($date, $format = 'Y-m-d'){
            /**
     * Validates a given date string against a specified format.
     *
     * @param string $date The date string to validate.
     * @param string $format The format to use when validating the date string. Defaults to 'Y-m-d'.
     * @return bool True if the date string is valid, false otherwise.
     */
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // get multiple column from field
    function getColumsFromField($tableName, $columns, $whereClause, $whereValue=[] ){
        global $connect;
        $string= "";
        foreach( $whereValue as $item ){
            $string .= "s";
        }
        $query = "SELECT $columns FROM $tableName $whereClause";
        $stmt = $connect->prepare($query);
        if($whereValue){
            $stmt->bind_param("$string", ...$whereValue );
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
            $row = $result->fetch_assoc();
            return $row;
        }else{
            return false;
        }
    }

    // new function added
    // get column from where the variables to check is more than one
    function getColumnFromFieldWhere($tablename, $column ,$whereClause, $whereField=[]){
        global $connect;
        $string= "";
        foreach( $whereField as $item ){
            $string .= "s";
        }

        $query = "SELECT $column FROM $tablename $whereClause";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("$string", ...$whereField );
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            $stmt->close();
            $row = $result->fetch_assoc();

            // $value = $row["$column"];

            return $row;

        }else{
            return false;
        }


    }

    // bot id for VC Card
    $updateVcRateKey= "";
    function getAllVirtualCardType($user_response){
        global $connect, $updateVcRateKey;
        // user enter ./unload_exchange to get unload_convenience_rate
        // user enter ./fund_exchange to get fund_exchange_rate
        
       $keyboard = [];
       $chatId="-994395089";

        $column_to_get = "";
        $message = "";
        if ( $user_response == "/unload_exchange" || $user_response == "unload_exchange" ){
            $column_to_get = "naira_unload_exhange_rate, ";
            $message = "Naira unloading rates";

        }

        if ( $user_response == "/fund_exchange" || $user_response == "fund_exchange" ){
            $column_to_get = "naira_fund_exhange_rate,";
            $message = "Naira fund exchnage";
        }

        if ( !empty($column_to_get) && $column_to_get != '' ){
            $query = "SELECT $column_to_get `trackid`, cardbrand FROM `vc_type`";
            $getVctypeStmt = $connect->prepare($query);
            $getVctypeStmt->execute();
            $result = $getVctypeStmt->get_result();
            $num_row = $result->num_rows;

            $listOfCards = "";
            $index = 0;

            if ( $num_row > 0 ){
                while ( $row = $result->fetch_assoc() ){
                    $index++;
                    $brand = $row['cardbrand'];
                    $trackid = $row['cardbrand'];
                    $value = $row['column_to_get'];
                    $data = [
                        ['text' => "$brand -> $value", 'callback_data' => "trackid=$trackid&column=$column_to_get"]   
                    ];
                    array_push($keyboard, $data);
                    $listOfCards .= "$index. $brand -> $value\n\n";

                }
                $inline_keyboard = ['inline_keyboard' => $keyboard];

                $response = "*Here* are all the available rated for $message: \n\n$listOfCards";

                replyuser($chatId, "0", $response,true, $inline_keyboard, $updateVcRateKey, "markdown");

                
            }else{
                $response = "No record of this $message found";
                replyuser($chatId, "0", $response,true, $keyboard, $updateVcRateKey, "markdown");

            }

        }        

    }

    function updateVirtualCardRate($trackid, $column ,$newRate){
        global $connect;

        // update the column 
        $query = "UPDATE `vc_type` SET $column = ? WHERE trackid = ?";
        $updateVc = $connect->prepare($query);
        $updateVc->bind_param("ss", $newRate, $trackid);
        $executed =  $updateVc->execute();

    }

    function checkIfCodeisInDB($connect, $tableName, $field ,$pubkey) {
        // Check if the email or phone number is already in the database
        $query = "SELECT $field FROM $tableName WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $pubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;

        if ($num_row > 0){
            return true;
        }

        return false;
        
    }

    // function to insert new data to the bill_data_provider_table, need this due to multiple occurence
    function insertDataProvider($thirdParty, $code ,$networkId, $name, $productTrackId, $pro_price, $amountToAdd, $status, $cashback, $cryptocashback){
        global $connect;
        // couln to add code
        // thirdparty values = 1 - oneapp 2 - club connect 3 - safe haven 4 - vtpass
        if ( $thirdParty == 1 ){
            $columnName = "theenoapp";
            $networkIdColumnName = "theenoapp_netid";
        }elseif ( $thirdParty == 2 ){
            $columnName = "connec_lub";
            $networkIdColumnName = "connec_lub_netid";
        }elseif ( $thirdParty == 3 ){
            $columnName = "sh_network";
            $networkIdColumnName = "sh_network_netid";
        }elseif ( $thirdParty == 4 ){
            $columnName = "vtpass";
            $networkIdColumnName = "vtpass_netid";
        }else{
            return false;
        }


        $dataPrice = $pro_price + $amountToAdd;

        $trackId = "DTA" . generateUniqueShortKey($connect, "bill_data_provider", "provider_tid");

        // query to insert
        $query = "INSERT INTO `bill_data_provider`(`provider_tid`, `name`, `bill_main_prod_tid`, `cashback`, `crypto_cashback`, `status`, `pro_price`, `price`,`systemtype`, $columnName, $networkIdColumnName ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $dataStmt = $connect->prepare($query);
        $dataStmt->bind_param("sssssssssss", $trackId, $name, $productTrackId, $cashback, $cryptocashback, $status, $pro_price, $dataPrice, $thirdParty, $code, $networkId );
        $executed = $dataStmt->execute();

        if ( $executed ){
            return true;
        }else{
            return false;
        }
    }
    // bot id for Casback
    $cashbackRateKey= "";
    function updateCashBank($user_input, $bill_type, $newValue){
        // product_trackid params must not all be empty at the same time 
        
        global $connect, $cashbackRateKey;

        $keyboard = [];
        $chatId="-994395089";

        if ( empty($user_input = "") ){
            return false;
        }

        // cashback rate must not be more than 5%
        if ( $newValue > 5 ){
            $response = "Cashback percentage can't be more than 5 percent";
            replyuser($chatId, "0", $response,true, $keyboard, $cashbackRateKey, "markdown");
        
        }elseif ( $newValue < 0 ){
            $response = "Invalid cashback percentage sent";
            replyuser($chatId, "0", $response,true, $keyboard, $cashbackRateKey, "markdown");
        }else{

            // convert user input to uppercase
            $inputToUppercase = strtoupper($user_input);

            // if bill_type = 2 airtime, bill_type = 1 data
            $tablename = ($bill_type = 1)? "bill_data_provider": ( ($bill_type = 2)? "bill_airtime_provider" : false );
            // check if a valid bil; type is passed, if not send a response to the user
            if ( !$tablename ){
                $response = "Invalid bill type sent";
                replyuser($chatId, "0", $response,true, $keyboard, $cashbackRateKey, "markdown");
                return false;
            }

            // get column from field
            $productId = getColumnFromFieldWhere("bill_top_up_main_products", "product_trackid", "WHERE groupname = ? AND type = ?", [$inputToUppercase, $bill_type]);

            // send a notification if the input can't be found to the user
            if ( !$productId ){
                $response = "Can't find this service";
                replyuser($chatId, "0", $response,true, $keyboard, $cashbackRateKey, "markdown");
                return false;
            }

            // update the table with the new rate for MTN data
            $query = "UPDATE $tablename SET cashback = ? WHERE bill_main_prod_tid = ?";
            $updateVtpassIdStmt = $connect->prepare($query);
            $updateVtpassIdStmt->bind_param("ss", $newValue , $productId);
            $updateVtpassIdStmt->execute();

            $response = "$user_input data cashback percent has been successfully updated";
            replyuser($chatId, "0", $response,true, $keyboard, $cashbackRateKey, "markdown");

            return true;

            
        } 

        


    }

    function getSumofCashback($type, $user){
        global $connect;
        // type  1 - All Casback 2 - All withdrawn cashback
        
        if ( $type == 1  ){
            $total = 0;
            $query = "SELECT SUM(amount) as total FROM `cashback_history` WHERE userid = ?";
            $allCbStmt = $connect->prepare($query);
            $allCbStmt->bind_param("s", $user);
            $allCbStmt->execute();
            $result = $allCbStmt->get_result();
            $num_row = $result->num_rows;
            if ( $num_row ){
                $row = $result->fetch_assoc();
                if ( $row['total'] != null ){
                    $total = $row['total'];
                }
            }
    
            return $total;
    
        }
    
        if ( $type == 2 ){
            $total = 0;
            $withdraw = 1;
            $query = "SELECT SUM(amttopay) as total FROM `userwallettrans` WHERE userid = ? AND cashbacktrans = ?";
            $allcbWithdrawStmt = $connect->prepare($query);
            $allcbWithdrawStmt->bind_param("ss", $user, $withdraw);
            $allcbWithdrawStmt->execute();
            $result = $allcbWithdrawStmt->get_result();
            $num_row = $result->num_rows;
            if ( $num_row ){
                $row = $result->fetch_assoc();
                if ( $row['total'] != null ){
                    $total = $row['total'];
                }
            }
    
            return $total;
        }
    
       
        
    }



   
 
    
?>