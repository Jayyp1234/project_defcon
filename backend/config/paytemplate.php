<?php
require 'myaes/php/src/AES256.php';
use mervick\aesEverywhere\AES256;
//PAY FUNCTION below is where all functions related to payment is added
//  you dont have to edit this

function GetActivePayStackApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM paystackapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}

function getDojahBvnData(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM dojahapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function GetActiveOneappApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM oneappapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function GetActiveMonifyApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM monifyapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function payGetBankcode($bnkcode){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM bankaccountsallowed WHERE sysbankcode=?");
    $getdataemail->bind_param("s",$bnkcode);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}
function paygetAllSystemSetting(){
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
function getBvnDetails(){
    //Get the active payment system
    $alldata=[];
    $activepaysystem= paygetAllSystemSetting()['activebvnsystem'];
    if($activepaysystem==1){// dojah
        $alldata=getDojahBvnData();
    }
    else{
        $alldata=[]; 
    }
    return $alldata;
}

// BALANCE FUNCTION STARTS
// prevent users from using the platform
function allUserUserPlatform($userid){
    global $connect;
    $allow=1;
    $bloxkit=1;
    $query = "SELECT id FROM users WHERE prevent_usage = ? AND id=?";
    $updateStmt = $connect->prepare($query);
    $updateStmt->bind_param('ii',$bloxkit,$userid);
    $updateStmt->execute();
    $getresultemail = $updateStmt->get_result();
    if ($getresultemail->num_rows > 0){
       $allow=0;
    }
    return $allow;
}
function payAddUserNgnBillsBalance($userid,$amount) {
    global $connect;
    $amount=round($amount,2);
    if($amount >0 && allUserUserPlatform($userid)==1){
        $query = "UPDATE users SET billngnbal = billngnbal + ? WHERE  id = ?";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('ds',$amount,$userid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }
    }
    return false;
}
function payAddUserCashbackBalance($userid,$amount) {
    global $connect;
    $amount=round($amount,2);
    if($amount >0 && allUserUserPlatform($userid)==1){
        $query = "UPDATE users SET cashback_bal = cashback_bal + ? WHERE  id = ?";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('ds',$amount,$userid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }
    }
    return false;
}
function payAddUserBalance($userid,$amount,$currency,$wallettrackid) {
        global $connect;
        $amount=round($amount,2);
        if($amount >0  && allUserUserPlatform($userid)==1){
            $query = "UPDATE userwallet SET walletbal = walletbal + ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
            $updateStmt = $connect->prepare($query);
            $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
            $updateStmt->execute();
            if ($updateStmt->affected_rows > 0){
                return true;
            }
        }
        return false;
}
function payAddUserExchangeBal($userid,$amount) {
        global $connect;
        $amount=round($amount,2);
          if($amount >0  && allUserUserPlatform($userid)==1){
        $query = "UPDATE users SET exchangebalance = exchangebalance + ? WHERE id = ?";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('ds',$amount,$userid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        } 
              
          }
        
        return false;
    
}
function payAddUserPendExchangeBal($userid,$amount) {
        global $connect;
        $amount=round($amount,2);
          if($amount >0  && allUserUserPlatform($userid)==1){
        $query = "UPDATE users SET 	exchangependbal = 	exchangependbal + ? WHERE id = ?";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('ds',$amount,$userid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        } }
        return false;
}
function payAddUserSubBalance($userid,$amount,$currency,$wallettrackid) {
     global $connect;
     $amount=round($amount,8);
       if($amount >0  && allUserUserPlatform($userid)==1){
        $query = "UPDATE usersubwallet SET walletbal = walletbal + ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }}
        return false;
}
function payAddUserSubPendBalance($userid,$amount,$currency,$wallettrackid) {
     global $connect;
     $amount=round($amount,8);
       if($amount >0  && allUserUserPlatform($userid)==1){
        $query = "UPDATE usersubwallet SET walletpendbal = walletpendbal + ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
        $updateStmt = $connect->prepare($query);
        $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
        $updateStmt->execute();
        if ($updateStmt->affected_rows > 0){
            return true;
        }}
            return false;
}
function payAddUserEscrowBalance($userid,$amount,$currency,$wallettrackid) {
    global $connect;
    $amount=round($amount,2);
      if($amount >0  && allUserUserPlatform($userid)==1){
       //   echo  $userid."____".$amount."____".$currency."____".$wallettrackid;
       $query = "UPDATE userwallet SET walletescrowbal	 = walletescrowbal	 + ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
       $updateStmt = $connect->prepare($query);
       $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
       $updateStmt->execute();
       if ($updateStmt->affected_rows > 0){
           return true;
       } }
           return false;
}
function paySubtractUserPendExchangeBal($userid,$amount) {
    global $connect;
    $amount=round($amount,2);
      if($amount >0  && allUserUserPlatform($userid)==1){
    // check if user have the money
    $getUser = $connect->prepare("SELECT id FROM users WHERE 	exchangependbal >= ? AND id=?");
    $getUser->bind_param("ds",$amount,$userid);
    $getUser->execute();
    $result = $getUser->get_result();
    if($result->num_rows > 0){
            $query = "UPDATE users SET 	exchangependbal = 	exchangependbal - ? WHERE id = ?";
            $updateStmt = $connect->prepare($query);
            $updateStmt->bind_param('ds',$amount,$userid);
            $updateStmt->execute();
            if ($updateStmt->affected_rows > 0){
                $zero=0;
                $getUser = $connect->prepare("SELECT id FROM users WHERE 	exchangependbal >= ? AND id=?");
                $getUser->bind_param("ds",$zero,$userid);
                $getUser->execute();
                $result = $getUser->get_result();
                if($result->num_rows > 0){
                    return true;
                }else{
                    $query = "UPDATE users SET 	exchangependbal = 	exchangependbal + ? WHERE id = ?";
                    $updateStmt = $connect->prepare($query);
                    $updateStmt->bind_param('ds',$amount,$userid);
                    $updateStmt->execute();
                    $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                    notify_admin_noti_b_bot($message,$userid);
                  }
            } 
    }}
    return false;
    
}
function paySubtractUserExchangeBal($userid,$amount) {
    global $connect;
    $amount=round($amount,2);
    if($amount >0  && allUserUserPlatform($userid)==1){
    // check if user have the money
    $getUser = $connect->prepare("SELECT id FROM users WHERE exchangebalance >= ? AND id=?");
    $getUser->bind_param("ds",$amount,$userid);
    $getUser->execute();
    $result = $getUser->get_result();
    if($result->num_rows > 0){
            $query = "UPDATE users SET exchangebalance = exchangebalance - ? WHERE id = ?";
            $updateStmt = $connect->prepare($query);
            $updateStmt->bind_param('ds',$amount,$userid);
            $updateStmt->execute();
            if ($updateStmt->affected_rows > 0){
                $zero=0;
                $getUser = $connect->prepare("SELECT id FROM users WHERE exchangebalance >= ? AND id=?");
                $getUser->bind_param("ds",$zero,$userid);
                $getUser->execute();
                $result = $getUser->get_result();
                if($result->num_rows > 0){
                return true;
                }else{
                    $query = "UPDATE users SET exchangebalance = exchangebalance + ? WHERE id = ?";
                    $updateStmt = $connect->prepare($query);
                    $updateStmt->bind_param('ds',$amount,$userid);
                    $updateStmt->execute();
                    $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                    notify_admin_noti_b_bot($message,$userid);
                  }
            } 
    }}
    return false; 
}
function payDeductUserCashbackBal($userid,$amount) {
    global $connect;
    $amount=round($amount,2);
    if($amount >0  && allUserUserPlatform($userid)==1){
    // check if user have the money
    $getUser = $connect->prepare("SELECT id FROM users WHERE cashback_bal >= ? AND id=?");
    $getUser->bind_param("ds",$amount,$userid);
    $getUser->execute();
    $result = $getUser->get_result();
    if($result->num_rows > 0){
            $query = "UPDATE users SET cashback_bal = cashback_bal - ? WHERE id = ?";
            $updateStmt = $connect->prepare($query);
            $updateStmt->bind_param('ds',$amount,$userid);
            $updateStmt->execute();
            if ($updateStmt->affected_rows > 0){
                $zero=0;
                $getUser = $connect->prepare("SELECT id FROM users WHERE cashback_bal >= ? AND id=?");
                $getUser->bind_param("ds",$zero,$userid);
                $getUser->execute();
                $result = $getUser->get_result();
                if($result->num_rows > 0){
                return true;
                }else{
                    $query = "UPDATE users SET cashback_bal = cashback_bal + ? WHERE id = ?";
                    $updateStmt = $connect->prepare($query);
                    $updateStmt->bind_param('ds',$amount,$userid);
                    $updateStmt->execute();
                    $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                    notify_admin_noti_b_bot($message,$userid);
                  }
            } 
    }}
    return false; 
}
function payRemoveUserSubPendBalance($userid,$amount,$currency,$wallettrackid) {
    global $connect;
    $amount=round($amount,8);
    if($amount >0  && allUserUserPlatform($userid)==1){
     $getUser = $connect->prepare("SELECT walletpendbal FROM usersubwallet WHERE walletpendbal >= ? AND currencytag = ? AND userid = ? AND trackid=?");
     $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
     $getUser->execute();
     $result = $getUser->get_result();
     if($result->num_rows > 0){
          // get customer card trckid
             $getsys =$result->fetch_assoc();
             $theuserbalsis=round($getsys['walletpendbal'],8);
             if(($theuserbalsis-$amount)>=0){
                 $getUser = $connect->prepare("SELECT id FROM usersubwallet WHERE walletpendbal - ? >= 0 AND currencytag = ? AND userid = ? AND trackid=?");
                 $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                 $getUser->execute();
                 $result = $getUser->get_result();
                 if($result->num_rows > 0){  
                         $query = "UPDATE usersubwallet SET walletpendbal = walletpendbal - ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
                         $updateStmt = $connect->prepare($query);
                         $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                         $updateStmt->execute();
                         if ($updateStmt->affected_rows > 0){
                                 // get account and check if negative, if yes return false
                                 //  check if after deduction balance is not negative
                                 $zero=0;
                                 $getUser = $connect->prepare("SELECT walletpendbal FROM usersubwallet WHERE walletpendbal >= ? AND currencytag = ? AND userid = ? AND trackid=?");
                                 $getUser->bind_param('dsss',$zero,$currency,$userid,$wallettrackid);
                                 $getUser->execute();
                                 $result = $getUser->get_result();
                                 if($result->num_rows > 0){
                                      return true;
                                 }else{
                                    $query = "UPDATE usersubwallet SET walletpendbal = walletpendbal + ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
                                    $updateStmt = $connect->prepare($query);
                                    $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                    $updateStmt->execute();

                                    $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                                    notify_admin_noti_b_bot($message,$userid);
                                  }
                         } 
                 }
             }
     }
}
return false;

}
function payRemoveUserSubBalance($userid,$amount,$currency,$wallettrackid) {
    global $connect;
    $amount=round($amount,8);
    if($amount >0  && allUserUserPlatform($userid)==1){
             $getUser = $connect->prepare("SELECT walletbal FROM usersubwallet WHERE walletbal >= ? AND currencytag = ? AND userid = ? AND trackid=?");
             $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
             $getUser->execute();
             $result = $getUser->get_result();
             if($result->num_rows > 0){
                  // get customer card trckid
                     $getsys =$result->fetch_assoc();
                     $theuserbalsis=round($getsys['walletbal'],8);
                     if(($theuserbalsis-$amount)>=0){
                         $getUser = $connect->prepare("SELECT id FROM usersubwallet WHERE walletbal - ? >= 0 AND currencytag = ? AND userid = ? AND trackid=?");
                         $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                         $getUser->execute();
                         $result = $getUser->get_result();
                         if($result->num_rows > 0){  
                                 $query = "UPDATE usersubwallet SET walletbal = walletbal - ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
                                 $updateStmt = $connect->prepare($query);
                                 $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                 $updateStmt->execute();
                                 if ($updateStmt->affected_rows > 0){
                                         // get account and check if negative, if yes return false
                                         //  check if after deduction balance is not negative
                                         $zero=0;
                                         $getUser = $connect->prepare("SELECT walletbal FROM usersubwallet WHERE walletbal >= ? AND currencytag = ? AND userid = ? AND trackid=?");
                                         $getUser->bind_param('dsss',$zero,$currency,$userid,$wallettrackid);
                                         $getUser->execute();
                                         $result = $getUser->get_result();
                                         if($result->num_rows > 0){
                                              return true;
                                         }else{
                                            $query = "UPDATE usersubwallet SET walletbal = walletbal + ? WHERE (currencytag = ? AND userid = ? AND trackid=?)";
                                            $updateStmt = $connect->prepare($query);
                                            $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                            $updateStmt->execute();
                                            $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                                            notify_admin_noti_b_bot($message,$userid);
                                          }
                                 } 
                         }
                     }
             }
    }
    return false;
}
function payDeductUserBalance($userid,$amount,$currency,$wallettrackid) {
         global $connect;
            $amount=round($amount,2);
           if($amount >0  && allUserUserPlatform($userid)==1){
                    $getUser = $connect->prepare("SELECT walletbal FROM userwallet WHERE walletbal >= ? AND currencytag = ? AND userid = ? AND wallettrackid=?");
                    $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                    $getUser->execute();
                    $result = $getUser->get_result();
                    if($result->num_rows > 0){
                         // get customer card trckid
                            $getsys =$result->fetch_assoc();
                            $theuserbalsis=round($getsys['walletbal'],2);
                            if(($theuserbalsis-$amount)>=0){
                                $getUser = $connect->prepare("SELECT id FROM userwallet WHERE walletbal - ? >= 0 AND currencytag = ? AND userid = ? AND wallettrackid=?");
                                $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                $getUser->execute();
                                $result = $getUser->get_result();
                                if($result->num_rows > 0){  
                                        $query = "UPDATE userwallet SET walletbal = walletbal - ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
                                        $updateStmt = $connect->prepare($query);
                                        $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                        $updateStmt->execute();
                                        if ($updateStmt->affected_rows > 0){
                                                // get account and check if negative, if yes return false
                                                //  check if after deduction balance is not negative
                                                $zero=0;
                                                $getUser = $connect->prepare("SELECT walletbal FROM userwallet WHERE walletbal >= ? AND currencytag = ? AND userid = ? AND wallettrackid=?");
                                                $getUser->bind_param('dsss',$zero,$currency,$userid,$wallettrackid);
                                                $getUser->execute();
                                                $result = $getUser->get_result();
                                                if($result->num_rows > 0){
                                                     return true;
                                                }else{
                                                    // return the fund to user balance and cancle trans
                                                        $query = "UPDATE userwallet SET walletbal = walletbal + ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
                                                        $updateStmt = $connect->prepare($query);
                                                        $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                                        $updateStmt->execute();

                                                        $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                                                        notify_admin_noti_b_bot($message,$userid);
                                                }
                                        } 
                                }
                            }
                    }
           }
            return false;
}
function payDeductUserEscrowBalance($userid,$amount,$currency,$wallettrackid) {
     global $connect;
     
     $amount=round($amount,2);
     if($amount >0  && allUserUserPlatform($userid)==1){
              $getUser = $connect->prepare("SELECT walletescrowbal FROM userwallet WHERE walletescrowbal >= ? AND currencytag = ? AND userid = ? AND wallettrackid=?");
              $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
              $getUser->execute();
              $result = $getUser->get_result();
              if($result->num_rows > 0){
                   // get customer card trckid
                      $getsys =$result->fetch_assoc();
                      $theuserbalsis=round($getsys['walletescrowbal'],2);
                      if(($theuserbalsis-$amount)>=0){
                          $getUser = $connect->prepare("SELECT id FROM userwallet WHERE walletescrowbal - ? >= 0 AND currencytag = ? AND userid = ? AND wallettrackid=?");
                          $getUser->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                          $getUser->execute();
                          $result = $getUser->get_result();
                          if($result->num_rows > 0){  
                                  $query = "UPDATE userwallet SET walletescrowbal = walletescrowbal - ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
                                  $updateStmt = $connect->prepare($query);
                                  $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                  $updateStmt->execute();
                                  if ($updateStmt->affected_rows > 0){
                                          // get account and check if negative, if yes return false
                                          //  check if after deduction balance is not negative
                                          $zero=0;
                                          $getUser = $connect->prepare("SELECT walletescrowbal FROM userwallet WHERE walletescrowbal >= ? AND currencytag = ? AND userid = ? AND wallettrackid=?");
                                          $getUser->bind_param('dsss',$zero,$currency,$userid,$wallettrackid);
                                          $getUser->execute();
                                          $result = $getUser->get_result();
                                          if($result->num_rows > 0){
                                               return true;
                                          }else{
                                            $query = "UPDATE userwallet SET walletescrowbal = walletescrowbal + ? WHERE (currencytag = ? AND userid = ? AND wallettrackid=?)";
                                            $updateStmt = $connect->prepare($query);
                                            $updateStmt->bind_param('dsss',$amount,$currency,$userid,$wallettrackid);
                                            $updateStmt->execute();

                                            $message="@habnarm1 The user's balance is meant to have gone negative by $amount, but the transaction was cancelled. Please investigate the user's intended actions.";
                                            notify_admin_noti_b_bot($message,$userid);
                                          }
                                  } 
                          }
                      }
              }
     }
            return false;
}

// BALANCE FUNCTION ENDS

function payTransCancled($reference){
      global $connect;
        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
        $bankpaidwith=1;
        $systempaidwith=0;
        $companypayref="";
        $response=$paystackref=$paymenttoken="";
        $paystatus=0;
        $status = 3;
        $time = "";
        $approvedby="Automation";
        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
        $checkdata->execute();
}
function payJointTransCancled($reference){
      global $connect;
        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
        $bankpaidwith=1;
        $systempaidwith=0;
        $companypayref="";
        $response=$paystackref=$paymenttoken="";
        $paystatus=0;
        $status = 3;
        $time = "";
        $approvedby="Automation";
        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE joint_trans_tid=?");
        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
        $checkdata->execute();
}
function payTransScam($reference){
      global $connect;
        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
        $bankpaidwith=1;
        $systempaidwith=0;
        $companypayref="";
        $response=$paystackref=$paymenttoken="";
        $paystatus=0;
        $status = 4;
        $time = "";
        $approvedby="Automation";
        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
        $checkdata->execute();
}
function payTransInwallet($reference){
      global $connect;
        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
        $bankpaidwith=1;
        $systempaidwith=0;
        $companypayref="";
        $response=$paystackref=$paymenttoken="";
        $paystatus=0;
        $status = 2;
        $time = "";
        $approvedby="Automation";
        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
        $checkdata->execute();
}
//  code for all integrations
function getUserAccountName($bnkcode,$accno){
    //Get the active payment system
    global $connect;
    $alldata="";
    $bankcodes=payGetBankcode($bnkcode);
    $oneappcode=$bankcodes['oneappbankcode'];
    $monibankcode=$bankcodes['monifybankcode'];
    $psbankcode=$bankcodes['paystackbankcode'];
    $shbankcodes=$bankcodes['shbankcodes'];
    // if(getAccountNamePayStack($psbankcode,$accno)!=""&& strpos(getAccountNamePayStack($psbankcode,$accno),"Invalid")==false){
    //     $alldata=getAccountNamePayStack($psbankcode,$accno);
    // }else     if(getAccountNameMonify($monibankcode,$accno)!=""&& strpos(getAccountNameMonify($monibankcode,$accno),"Invalid")==false){
    //     $alldata=getAccountNameMonify($monibankcode,$accno);
    // }else  
    // if(getAccountNameOneApp($oneappcode,$accno)!=""&& strpos(getAccountNameOneApp($oneappcode,$accno),"Invalid")==false){
    //     $alldata=getAccountNameOneApp($oneappcode,$accno);
    // }
    if(getAccountNameSH($shbankcodes,$accno)!=""&& strpos(getAccountNameSH($shbankcodes,$accno),"Invalid")==false){
        $alldata=getAccountNameSH($shbankcodes,$accno);
    }
    return $alldata;
}
function payUserWithAnyBankSystem($amount, $accbnkcode, $accountname, $bnkname, $acctosendto, $userbanrefcode, $transorderid){
    $alldata=false;
    $activepaysystem= paygetAllSystemSetting()['activepaysystem'];
    $bankcodes=payGetBankcode($accbnkcode);
    $oneappcode=$bankcodes['oneappbankcode'];
    $monibankcode=$bankcodes['monifybankcode'];
    $psbankcode=$bankcodes['paystackbankcode'];
    
    if ($activepaysystem==1) {// paystack
        $alldata=payStackSendMoney($amount,$psbankcode,$bnkname,$acctosendto,$userbanrefcode,$transorderid);
    } elseif ($activepaysystem==2) {//monify
        $alldata=monifySendMoney($amount, $monibankcode, $transorderid, $bnkname, $acctosendto, $userbanrefcode, $transorderid);
    } 
    else if ($activepaysystem==3){//oneapp
        $alldata = oneAppSendMoney($amount,$oneappcode,$bnkname,$acctosendto,$userbanrefcode,$transorderid,$accountname);
    }
    else {
        //  in case telegram is to be added add the code here and return false for the tranaction to start in wallet
        //  ensure to update bank and system type if telegram is added on the system
        $alldata=false;
    }
     return $alldata;
}


//paystack functions
function getAllPayStackBank(){
    $allbnkarr=[];
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['apikey'];

    $url = "https://api.paystack.co/";
    // $params = json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url . "bank",
        // CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $activepaystackapi", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache",
        ),
    ));
    $allbanks = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $allbnkarr=[];
        throw new \Exception("Error getting bank names: $err");

    } else {
        $dbanks = json_decode($allbanks);
        if($dbanks->status==true){
            $banks = $dbanks->data;
            foreach ($banks as $abc) {
                $bankname= trim($abc->name);
                $bankcoode=trim($abc->code);
                array_push($allbnkarr, array( "name"=>$bankname, "code"=>$bankcoode,"combined"=>"$bankname^$bankcoode"));
            }
        }else{
           $allbnkarr=[];
         }
    }
    return $allbnkarr;

}
function addUserToPayStack($fullname,$accountnumber,$bankcode){
    $refcode="";
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['secretekey'];
    $arr = array(
    "type"=> "nuban",
    "name"=> "$fullname",
    "account_number"=> "$accountnumber",
    "bank_code"=> "$bankcode"
    );
    //below is the base url
    $url ="https://api.paystack.co/";
    $params =  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url . "transferrecipient",
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ),
    ));
    $userdetails = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $refcode="";
        // throw new \Exception("Error getting bank names: $err");

    } else {
        $ddata = json_decode($userdetails);
        $status=$ddata->status;
        
        if($status){
            $duserdet =$ddata->data;
            $refcode = $duserdet->recipient_code;
        }else{
            $refcode="";
        }
     
    }
    return $refcode;

}
function getAccountNamePayStack($bnkcode,$accno){
    $datatosend="";
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['secretekey'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=".$accno."&bank_code=".$bnkcode."",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        "Authorization: Bearer $activepaystackapi"
        ),
    ));

    $resp = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $datatosend="";
        throw new \Exception("Error getting account names: $err");
    } else {
        // print($resp);
        $responses = json_decode($resp);
        //var_dump($responses);
        if (isset($responses->data->account_name)) {
            $status = $responses->status;
            $msg = $responses->message;
            $acnt_no = $responses->data->account_number;
            $acnt_name = $responses->data->account_name;
            $bankid = $responses->data->bank_id;

            if ($status == 'true') {
                $datatosend=$acnt_name;
            } else {
                $datatosend='Invalid account number';
            }
        } else {
            $datatosend='Invalid account number';
        }
    }
        return $datatosend;

}
function checkPaystackBalanceAmount($moneytosend){
    $canpay=false;
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['apikey'];

    $url ="https://api.paystack.co/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url . "balance",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $activepaystackapi", //replace this with your own test key
    "content-type: application/json",
    "cache-control: no-cache"
    ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $canpay=false;
        throw new \Exception("Error getting account names: $err");
    } else {
        // print($resp);
        $responses = json_decode($response);
        //  var_dump($responses);
        if (isset($responses->status) && $responses->status==true) {
            $mybal= $responses->data[0]->balance;
            if(($mybal/100)>=$moneytosend){
                $canpay=true;
            } else {
                $canpay=false;
            }
        } else {
            $canpay=false;
        }
    }
        return $canpay;
}
function payStackSendMoney($amount,$accbnkcode,$bnkname,$acctosendto,$userbanrefcode,$transorderid){
 
    $canpay=false;
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['secretekey'];
    $banref = addUserToPayStack($bnkname,$acctosendto,$accbnkcode);
    $amount=$amount*100;
    # try to contact service sending the money
    $arr = array(
            "source"=> "balance",
            "reason"=> "services",
            "amount"=> $amount,
            "recipient"=> $banref,
        );
        //below is the base url
        $url ="https://api.paystack.co/";
        $params =  json_encode($arr);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url . "transfer",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $activepaystackapi", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache"
        ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    if ($err) {
        $canpay=false;
        throw new \Exception("Error getting account names: $err");
    } else {
        //print($resp);
        $responses = json_decode($resp);
        if (isset($responses->status) && $responses->status==true) {
            
            $paystackref= $responses->data->reference;
            $paymenttoken=$responses->data->id;
            $canpay=true;
            
            // update transaction ref as paid
            if (!empty($transorderid) && $transorderid != "") {
                // generating  token
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $companypayref = createUniqueToken(16,"userwallettrans","paymentref","PS",true,true,false);
                $valid=true; 
                // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                $bankpaidwith=1;
                $systempaidwith=1;
                $paystatus=1;
                $status = 1;
                $time = date("h:ia, d M");
                $approvedby="Automation";
                $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$resp,$paystackref,$paymenttoken,$approvedby,$transorderid);
                $checkdata->execute();
            }
           
        } else {
            $canpay='false';
        }
    }
    return $canpay;
        
}
function PayStackVerifyBVN($bvn,$accountno,$bankcode){
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['apikey'];
    $verified=false;
    $arr = array(
    "bvn"=> "$bvn",
    "account_number"=> "$accountno",
    "bank_code"=> "$bankcode",
    );
    //below is the base url
    $url ="https://api.paystack.co/";
    $params =  json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    //u change the url infront based on the request u want
    CURLOPT_URL => $url . "bvn/match",
    CURLOPT_POSTFIELDS => $params,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //change this based on what u need post,get etc
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $activepaystackapi", //replace this with your own test key
    "content-type: application/json",
    "cache-control: no-cache"
    ),
    ));
    $resp = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $verified=false;
        throw new \Exception("Error verifying BVN: $err");
    } else {
        $decodedresponse = json_decode($resp);
        $thestatus=$decodedresponse->status;
        if ($thestatus) {
            $verified=true;
        } else {
            $verified=false;
        }
    }
    return $verified;
}
function verifypaystackcardpay($reference,$useremail, $uname, $userid){
    global $connect;
    $valid=false;
    $activepaystackapi=GetActivePayStackApi()['secretekey'];
    $verified=false;
    $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode("$reference"),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer $activepaystackapi",
      "Cache-Control: no-cache",
    ),
  ));
  

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $tranx = json_decode($response);
    // print_r($tranx);
    if ($tranx->status && 'success' == $tranx->data->status) {
        $paystackref=$tranx->data->reference;
        $paymenttoken=$tranx->data->id;
        $notyetpaid=0;
        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE orderid=? AND status=?  AND userid=?");
        $checkdata->bind_param("sis",$reference, $notyetpaid,$userid);
        $checkdata->execute();
        $dresult = $checkdata->get_result(); 
       if(empty($reference)) {
            $valid=false;
       } else if($dresult ->num_rows == 0){
            $valid=false;
       }else{
                 // generating  token
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","PS",true,true,false);
            
           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
           $bankpaidwith=1;
           $systempaidwith=1;
           $paystatus=1;
           $status = 1;
           $time = date("h:ia, d M");
           $approvedby="Automation";
           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
           $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$reference);
           if($checkdata->execute()){
               $valid=true;
           }else{
            echo $checkdata->error;   
           }
           //save card
           paystacksavemycard($tranx, $useremail, $uname, $userid);
       }
    }
    return $valid;
}
function paystacksavemycard($tranx,$dashemail,$dashuname,$dashid){
    global $connect;
       //saving card
       $authcode= $tranx->data->authorization->authorization_code;
       $cardtype= $tranx->data->authorization->card_type;
       $last4= $tranx->data->authorization->last4;
       $expm=$tranx->data->authorization->exp_month;
       $expy= $tranx->data->authorization->exp_year;
       $bin = $tranx->data->authorization->bin;
       $dbank= $tranx->data->authorization->bank;
       $dchannel= $tranx->data->authorization->channel;
       $dsig = $tranx->data->authorization->signature;
       $reuse = $tranx->data->authorization->reusable;
       $countrycode= $tranx->data->authorization->country_code;

    $checkdata =  $connect->prepare("SELECT * FROM 	user_cards  WHERE last4=? AND useremail=? AND card_type=? AND username=? AND exp_month=? AND exp_yr=?");
    $checkdata->bind_param("ssssss",$last4,$dashemail,$cardtype,$dashuname,$expm,$expy);
    $checkdata->execute();
    $dresult = $checkdata->get_result();
    if($dresult->num_rows==0){

        $permitted_chars2 = '0123456789';
        $loop = 0;
        while($loop==0){
        $myrefcode= generate_string($permitted_chars2, 7);
        $check =  $connect->prepare("SELECT * FROM  user_cards WHERE  cardtrackid = ?");
        $check->bind_param("i",$myrefcode);
        $check->execute();
        $result2 =  $check->get_result();
        if($result2->num_rows > 0){
        $loop = 0;
        }else{
        $loop = 1;
        break;  
        }
        }
        $insert_data = $connect->prepare("INSERT INTO user_cards (username,userid,card_type,last4,authorization_code,exp_month,exp_yr,bin,bank,channel,signature,reusable,country_code,useremail,cardtrackid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $insert_data->bind_param("sssssssssssssss", $dashuname,$dashid,$cardtype,$last4,$authcode,$expm,$expy,$bin,$dbank,$dchannel,$dsig,$reuse,$countrycode,$dashemail,$myrefcode);
        $insert_data->execute();
        $insert_data->close();
        $checkdata->close();
    }
}
function paystackPaywithCard($amount,$dashemail,$transref){
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['secretekey'];
    $authlink="";

    $amounttodeduct=$amount;
    $email = $dashemail;
    if($amounttodeduct <=2500 ){
        $dadded= $amounttodeduct *(1.5/100);
        $dtobdeducted = $dadded + $amounttodeduct;
    }else if($amounttodeduct > 2500){
        $dadded= $amounttodeduct *(1.5/100);
        $dtobdeducted = 100+ $dadded + $amounttodeduct;
    }
    $damount=$dtobdeducted*100; 
     //the amount in kobo. This value is actually NGN 300
    $callback_url =BASEURL.'/dashboard/index.php';
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$damount,
        'email'=>$email,
        'reference' => $transref,
        'callback_url' => $callback_url,
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $authlink="";
    } else {
        $tranx = json_decode($response);
        if (!$tranx->status) {
            $authlink="";
        } else {
            // redirect to page so User can pay
            $authlink=$tranx->data->authorization_url;
        }
   }
   return $authlink;
}
function paywithsavedcardPayStack($dsavedemail,$amount,$dsavedauth,$reference,$wallettrackid){
    global $connect;
    $activepaystackapi=GetActivePayStackApi()['apikey'];
    $sent=false;
   if($amount <=2500 ){
       $dadded= $amount *(1.5/100);
       $dtobdeducted = $dadded + $amount;
   }else if($amount > 2500){
       $dadded= $amount *(1.5/100);
       $dtobdeducted = 100+ $dadded + $amount;
   }
   $damount=$dtobdeducted*100;
   //paystack calculation
   $savecardpay= $dtobdeducted*100;
   $curl = curl_init();
   $arr =  array(
       "email" => "$dsavedemail",
       "amount" => "$damount",
       'authorization_code' => "$dsavedauth"
     );
       //below is the base url
       $url ="https://api.paystack.co/transaction/charge_authorization";
       $params =  json_encode($arr);
       $curl = curl_init();
       curl_setopt_array($curl, array(
         //u change the url infront based on the request u want
         CURLOPT_URL => $url,
         CURLOPT_POSTFIELDS => $params,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 60,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         //change this based on what u need post,get etc
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_HTTPHEADER => array(
           "authorization: Bearer $activepaystackapi", //replace this with your own test key
           "content-type: application/json",
           "cache-control: no-cache"
         ),
     ));
       $response = curl_exec($curl);
       $err = curl_error($curl);
       curl_close($curl);
       if ($err) {
          $sent=false;
       } else {
           $respondecode =  json_decode($response);
               if ($respondecode->status==true && $respondecode->data->status=="success") {
                   $sent=true;
                   $apiref=$respondecode->data->reference;
                   $paystackref=$apiref;
                   $transreffrom1app=0;

                   $bankpaidwith=1;
                   $systempaidwith=1;
                   $paystatus=1;
                   $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=? WHERE orderid=?");
                   $checkdata->bind_param("ssss", $paystackref, $paystatus,$systempaidwith,$reference);
                   $checkdata->execute();

                   $update_data = $connect->prepare("UPDATE userwallet SET walletbal=walletbal+? WHERE wallettrackid=?");
                   $update_data->bind_param("ss", $amount, $wallettrackid);
                   $update_data->execute();
            }
    }
    return $sent;
}

    // Monify functions change https://sandbox.monnify.com to https://api.monnify.com when done testing
function MonifyVerifyBVN($bvn,$accountno,$bankcode){
        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $verified=false;

        $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
        # try to contact service sending the money
        //below is the base url
        $url = "https://sandbox.monnify.com/api/v1/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic $encodekey", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache",
            ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $gtdata = json_decode($resp);
        if (isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true) {
            $accestoken = $gtdata->responseBody->accessToken;

            $arr = array(
            "bvn"=> "$bvn",
            "accountNumber"=> "$accountno",
            "bankCode"=> "$bankcode",
            );
            //below is the base url
            $url ="https://sandbox.monnify.com/api/v1/";
            $params =  json_encode($arr);
            $curl = curl_init();
            curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url . "vas/bvn-account-match",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $accestoken", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
            ),
            ));
            $resp = curl_exec($curl);
            $err = curl_error($curl);
            // print($resp)
            // curl_close($curl);
            if ($err) {
                $verified=false;
                throw new \Exception("Error verifying BVN: $err");
            } else {
                $decodedresponse = json_decode($resp);
                $thestatus=$decodedresponse->requestSuccessful;
                if ($thestatus) {
                    $verified=true;
                } else {
                    $verified=false;
                }
            }
        }else{
            $verified=false;
        }
        return $verified;
}
function getallMonifyBanklist(){ 
        $allbnkarr=[];
        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $verified=false;

        $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
            # try to contact service sending the money
            //below is the base url
            $url ="https://sandbox.monnify.com/api/v1/auth/login";
            $curl = curl_init();
            curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Basic $encodekey", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache"
            ),
            ));
            $resp = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $gtdata=json_decode($resp);
            if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
                $accestoken=$gtdata->responseBody->accessToken;
    
                //below is the base url
                $url ="https://sandbox.monnify.com/api/v1/banks";
                $curl = curl_init();
                curl_setopt_array($curl, array(
             //u change the url infront based on the request u want
             CURLOPT_URL => $url,
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 60,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             //change this based on what u need post,get etc
             CURLOPT_CUSTOMREQUEST => "GET",
             CURLOPT_HTTPHEADER => array(
             "authorization: Bearer $accestoken", //replace this with your own test key
             "content-type: application/json",
             "cache-control: no-cache"
             ),
             ));
                $resp = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    $allbnkarr=[];
                    throw new \Exception("Error getting bank names: $err");
                } else {
                    $dbanks = json_decode($resp);
                    if ($dbanks->requestSuccessful==true) {
                        $banks = $dbanks->responseBody;
                        foreach ($banks as $abc) {
                            $bankname= trim($abc->name);
                            $bankcoode=trim($abc->code);
                            array_push($allbnkarr, array( "name"=>$bankname, "code"=>$bankcoode,"combined"=>"$bankname^$bankcoode"));
                        }
                    } else {
                        $allbnkarr=[];
                    }
                }
            }else{
                $allbnkarr=[];
            }
            return $allbnkarr;
}
function getAccountNameMonify($bnkcode,$accno){
            $accname="";
            global $connect;
            $monfydata=GetActiveMonifyApi();
            $activepaystackapi=$monfydata['apikey'];
            $activemonifysecrete=$monfydata['secretekey'];
            $moniaccno=$monfydata['apiaccno'];
            $verified=false;

            $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
            # try to contact service sending the money
            //below is the base url
            $url ="https://sandbox.monnify.com/api/v1/auth/login";
            $curl = curl_init();
            curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Basic $encodekey", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache"
            ),
            ));
            $resp = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $gtdata=json_decode($resp);
            if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
                $accestoken=$gtdata->responseBody->accessToken;
                //below is the base url
                $url ="https://sandbox.monnify.com/api/v1/disbursements/account/validate?accountNumber=$accno&bankCode=$bnkcode";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                //u change the url infront based on the request u want
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //change this based on what u need post,get etc
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $accestoken", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
                ),
                ));
                $resp = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    $accname="Account not found";
                    throw new \Exception("Error getting account names: $err");
                } else {
                    $alldatain = json_decode($resp);
                    if ($alldatain->requestSuccessful==true) {
                        $accname=$alldatain->responseBody->accountName;
                    } else {
                        $accname="Account not found";
                    }
                }
            }else{
                $accname="Account not found";
            }
            return $accname;
}
function checkMonifyBalanceAmount($moneytosend){

        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $canpay=false;

        $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
        # try to contact service sending the money
        //below is the base url
        $url ="https://sandbox.monnify.com/api/v1/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
        "authorization: Basic $encodekey", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
        ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $gtdata=json_decode($resp);
        if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
            $accestoken=$gtdata->responseBody->accessToken;

             //below is the base url
            $url ="https://sandbox.monnify.com/api/v2/disbursements/wallet-balance?accountNumber=$moniaccno";
            $curl = curl_init();
            curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $accestoken", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache"
            ),
            ));
            $resp = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $canpay=false;
                throw new \Exception("Error getting account names: $err");
            } else {
                $alldatain = json_decode($resp);
                if ($alldatain->requestSuccessful==true) {
                    $mybal=$alldatain->responseBody->availableBalance;
                    if($mybal>=$moneytosend){
                        $canpay=true;
                    }else{
                        $canpay=false;
                    }
                } else {
                    $canpay=false;
                }
            }
    
        }else{
            $canpay=false;
        }
        return $canpay;
}
function monifySendMoney($amount,$accbnkcode,$paymentref,$bnkname,$acctosendto,$userbanrefcode,$transorderid){
        $paymentref = $transorderid;
        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $canpay=false;

        $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
        # try to contact service sending the money
        //below is the base url
        $url ="https://sandbox.monnify.com/api/v1/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
        "authorization: Basic $encodekey", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
        ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $gtdata=json_decode($resp);
        if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
            $accestoken=$gtdata->responseBody->accessToken;

            if (strtolower($bnkname)==strtolower("PalmPay")) {
                $accbnkcode=100033;
            } elseif (strtolower($bnkname)==strtolower("Paycom")) {
                $accbnkcode=304;
                $acctosendto=substr($acctosendto,1);
            } elseif (strtolower($bnkname)==strtolower("ALAT by WEMA")) {
                $accbnkcode=035;
            }
            

            $naration="$paymentref"."Services";
        
            $arr = array(
                "amount"=> $amount,
                "reference"=>"$paymentref",
                "narration"=>"$naration",
                "destinationBankCode"=>"$accbnkcode",
                "destinationAccountNumber"=> "$acctosendto",
                "currency"=>"NGN",
                "sourceAccountNumber"=> "$moniaccno"
                );
            //below is the base url
       //below is the base url
       $url ="https://sandbox.monnify.com/api/v2/disbursements/single";
       $params =  json_encode($arr);
       $curl = curl_init();
       curl_setopt_array($curl, array(
           //u change the url infront based on the request u want
           CURLOPT_URL => $url,
           CURLOPT_POSTFIELDS => $params,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 60,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           //change this based on what u need post,get etc
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_HTTPHEADER => array(
           "authorization: Bearer $accestoken", //replace this with your own test key
           "content-type: application/json",
           "cache-control: no-cache"
           ),
           ));
       $resp = curl_exec($curl);
       $err = curl_error($curl);
       curl_close($curl);
            if ($err) {
                $canpay=false;
                throw new \Exception("Error getting account names: $err");
            } 
            else {
                // print($resp);
                $alldatain = json_decode($resp);
               // print_r($alldatain);

                if (strtolower($alldatain->responseBody->status)=="failed"||$alldatain->requestSuccessful==false) {
                    $canpay=false;
                } 
                else {
                    $canpay=true;// update transaction ref as paid
                    if (!empty($transorderid)&&$transorderid!="") {
                        // generating  token
                        // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                        $companypayref = createUniqueToken(16,"userwallettrans","paymentref","MN",true,true,false);
                        $paystackref=$alldatain->responseBody->reference;
                        $paymenttoken=" ";
                        $valid=true; 
                        // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                        $bankpaidwith=1;
                        $systempaidwith=2;
                        $paystatus=1;
                        $status = 1;
                        $time = date("h:ia, d M");
                        $approvedby="Automation";
                        $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                        $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$resp,$paystackref,$paymenttoken,$approvedby,$transorderid);
                        $checkdata->execute();
                    }
                }
            }

        }else{
            $canpay=false;
        }
        
        return $canpay;
}
function monifygenerateAccNumber($fname,$lname,$useremail,$banktype,$userid){// enusure the user has filled his name before he can generate an account number
    // $banktype  1=Moniepoint 2=Wema Bank, 3=Sterling Bank
    $bantypecode="";
    $banktypename="";
    if($banktype==1){
        $bantypecode=50515;
        $banktypename="Moniepoint";
    }else if($banktype==2){
        $bantypecode=035;
        $banktypename="Wema Bank";

    }else if($banktype==3){
        $bantypecode=232;
        $banktypename="Sterling Bank";
    }

        $dashname="$lname $fname";
        $dashemail=$useremail;
        $generated=false;
        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $monicontractcode = $monfydata['apiwallet'];
        $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
        # try to contact service sending the money
        //below is the base url
        $url ="https://sandbox.monnify.com/api/v1/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic $encodekey", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
            ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $gtdata=json_decode($resp);
        if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
                $accestoken=$gtdata->responseBody->accessToken;
                //creating user account
                $reserveaccounturl="https://sandbox.monnify.com/api/v1/bank-transfer/reserved-accounts";
                //getting uniq acc ref no
                $permitted_chars2 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $loop = 0;
                while ($loop==0) {
                    $myrefcode= generate_string($permitted_chars2, 6);
                    $check =  $connect->prepare("SELECT id FROM userpersonalbnkacc WHERE accrefcode=?");
                    $check->bind_param("s", $myrefcode);
                    $check->execute();
                    $result2 =  $check->get_result();
                    if ($result2->num_rows > 0) {
                        $loop = 0;
                    } else {
                        $loop = 1;
                        break;
                    }
                }
                $check->close();
                $arr = array(
                    "accountReference"=> "$myrefcode",
                    "accountName"=> "$dashname",
                    "currencyCode"=> "NGN",
                    "contractCode"=> "".$monicontractcode."",
                    "customerEmail"=> "$dashemail",
                    "customerName"=> "$dashname",
                    "getAllAvailableBanks"=> false,
                    "preferredBanks"=> ["$bantypecode"]

                );
                $params =  json_encode($arr);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                        CURLOPT_URL => $reserveaccounturl,
                        CURLOPT_POSTFIELDS => $params,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 60,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => array(
                                "Authorization: Bearer $accestoken",
                                "Content-Type:application/json"
                        ),
                ));
        
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                $myresp = json_decode($response);
                // print_r(  $response);
                $responsestatus = $myresp->requestSuccessful;
                $responsemsg = $myresp->responseMessage;
                if (($responsestatus==true ||$responsestatus==1) && $responsemsg=="success") {
                    // $newbankaccno = $myresp->responseBody->accounts[0]->accountNumber;
                     $newbankaccno = $myresp->responseBody->accountNumber;
                    $newreseverref = $myresp->responseBody->reservationReference;
                    $mainprovidusref = $myresp->responseBody->accountReference;
                    $accountName = $myresp->responseBody->accountName;
        
                    $type = 2;
                    $insert_data = $connect->prepare("INSERT INTO userpersonalbnkacc (userid,bankname,accno,accrefcode,accserverrefcode,banksystemtype,acctname,banktypeis) VALUES (?,?,?,?,?,?,?,?)");
                    $insert_data->bind_param("ssssssss", $userid, $banktypename, $newbankaccno,$myrefcode,$newreseverref,$type,$accountName,$type);
                    $insert_data->execute();
                    $insert_data->close();
                    $generated=true;
                }else{
                    $generated=false;
                }
        }else{
                 $generated=false;
        }
        return $generated;
}
function verifymonifypay($reference,$useremail, $uname, $userid,$systemtransref){
    global $connect;
    $monfydata=GetActiveMonifyApi();
    $activepaystackapi=$monfydata['apikey'];
    $activemonifysecrete=$monfydata['secretekey'];
    $moniaccno=$monfydata['apiaccno'];
    $verified=false;

    $encodekey = base64_encode("$activepaystackapi:$activemonifysecrete");
        # try to contact service sending the money
        //below is the base url
        $url ="https://sandbox.monnify.com/api/v1/auth/login";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //u change the url infront based on the request u want
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //change this based on what u need post,get etc
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
            "authorization: Basic $encodekey", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache"
            ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $gtdata=json_decode($resp);
        if(isset($gtdata->requestSuccessful) && $gtdata->requestSuccessful==true){
            $accestoken=$gtdata->responseBody->accessToken;

            //below is the base url
            $encoded = rawurlencode($reference);
            $statuscheckurl =  "https://sandbox.monnify.com/api/v2/transactions/$encoded";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                //u change the url infront based on the request u want
                CURLOPT_URL => $statuscheckurl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //change this based on what u need post,get etc
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "authorization: Bearer $accestoken", //replace this with your own test key
                "content-type: application/json",
                "cache-control: no-cache"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $myresp = json_decode($response);
            print_r($response);
            $responsestatus = $myresp->requestSuccessful;
            $responsemsg = $myresp->responseMessage;
            if (($responsestatus==true ||$responsestatus==1) && $responsemsg=="success" &&  $myresp->responseBody->paymentStatus=="PAID") {//check if transacon is good
                $valid=true; 
                // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                $paystackref= $myresp->responseBody->transactionReference;
                $paymenttoken=$myresp->responseBody->paymentReference;
                
                $notyetpaid=1;
                $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE apipayref=? AND status=?  AND userid=?");
                $checkdata->bind_param("sis",$paystackref, $notyetpaid,$userid);
                $checkdata->execute();
                $dresult = $checkdata->get_result(); 
               if(empty($reference)) {
                    $valid=false;
               } else if($dresult ->num_rows > 0){
                    $valid=false;
               }else{
                    // generating  token
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $companypayref = createUniqueToken(16,"userwallettrans","paymentref","MBANKT",true,true,false);
                   $valid=true; 
                   // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                   $bankpaidwith=1;
                   $systempaidwith=2;
                   $paystatus=1;
                   $status = 1;
                   $time = date("h:ia, d M");
                   $approvedby="Automation";
                   $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                   $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$systemtransref);
                   $checkdata->execute();
               }
            }
        }
    return $valid;
}
function giveUserTheirPayOnMonify($transref,$useremail, $uname,$userid,$wallettrackid){
    global $connect;
    $successful=false;
    // data from webhook 
    // $json = file_get_contents('php://input');
    // // Converts it into a PHP object
    // $data = json_decode($json);
    // //if nothing pass null
    // $transactionref = cleanme($data->transactionReference);
    // $paymentref = cleanme($data->paymentReference);
    // $amtpaid = cleanme($data->amountPaid);
    // $paiddate = cleanme($data->paidOn);
    // $paymentStatus = cleanme($data->paymentStatus);
    // $paymentdescription = cleanme($data->paymentDescription);
    // $transachash = cleanme($data->transactionHash);
    // $paymentmethod =  cleanme($data->paymentMethod);
    // $customerdet = $data->customer;
    // $customeremail = cleanme($customerdet->email);
    // $customername = cleanme($customerdet->name);
           
    //check if the transaction and the email coming and amount exist
    $notyetpaid=0;
    $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE orderid=? AND status=?  AND userid=?");
    $checkdata->bind_param("sis",$transref, $notyetpaid,$userid);
    $checkdata->execute();
    $dresult = $checkdata->get_result(); 
       if(empty($transref)) {
            $successful=false;
       } else if($dresult ->num_rows == 0){
            $successful=false;
       }else{
           $checkdata->close();
           //get the transaction ref to use from DB
           $rr = $dresult->fetch_assoc();
           $tref = $rr['orderid'];
           $amnt = $rr['amttopay'];
           //call back action process
               if (verifymonifypay($transref,$useremail, $uname, $userid,$transref)) {
                        $successful=true;
                       $update_data = $connect->prepare("UPDATE userwallet SET walletbal=walletbal+? WHERE wallettrackid=?");
                       $update_data->bind_param("is", $amnt, $wallettrackid);
                       $update_data->execute();
                }
        }
     return $successful;
}
function monifycomputeSHA512TransactionHash($stringifiedData) {
        global $connect;
        $monfydata=GetActiveMonifyApi();
        $activepaystackapi=$monfydata['apikey'];
        $activemonifysecrete=$monfydata['secretekey'];
        $moniaccno=$monfydata['apiaccno'];
        $clientSecret= $activemonifysecrete;
        $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
        return $computedHash;
}

// 1app
function oneappPaywithCard($amount,$dashemail,$transref,$phoneno,$fname,$lname){
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $authlink="";

    $amounttodeduct=$amount;
    $email = $dashemail;
    if($amounttodeduct <=2500 ){
        $dadded= $amounttodeduct *(1.5/100);
        $dtobdeducted = $dadded + $amounttodeduct;
    }else if($amounttodeduct > 2500){
        $dadded= $amounttodeduct *(1.5/100);
        $dtobdeducted = 100+ $dadded + $amounttodeduct;
    }
    $damount=$dtobdeducted; 
    $callback_url =BASEURL.'dashboard/index.php';


    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/business/initiatetrans",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'amount'=>$damount,
        'customer_email'=>$email,
        'currency' => 'NGN',
        'reference' => $transref,
        'redirecturl' => $callback_url,
        'phone' => $phoneno,
        'fname' => $fname,
        'lname' => $lname
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $authlink="";
    } else {
        $tranx = json_decode($response);
        if (!$tranx->status) {
            $authlink="";
        } else {
            // redirect to page so User can pay
            $authlink=$tranx->authorization_url;
        }
   }
   return $authlink;
}
function verify1appcardpay($reference,$useremail, $uname, $userid,$orderid){
    global $connect;
    $valid=false;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $verified=false;
  
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/business/verifytrans",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'reference' => $reference,
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    // print_r($response);
    $err = curl_error($curl);
    curl_close($curl);
    $tranx = json_decode($response);
    if ($tranx->status) {
        // check if it new and never exist, update payapiresponse,apipayref,apiorderid 
        // print_r($tranx);
        $paystackref=$tranx->data->reference;
        $paymenttoken=$tranx->data->transaction_token;
            //check if the transaction and the email coming and amount exist
        $notyetpaid=0;
        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE orderid=? AND status=?  AND userid=?");
        $checkdata->bind_param("sis",$orderid, $notyetpaid,$userid);
        $checkdata->execute();
        $dresult = $checkdata->get_result(); 
       if(empty($orderid)) {
            $valid=false;
       } else if($dresult ->num_rows == 0){
            $valid=false;
       }else{
            // generating  token
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","1APP",true,true,false);
           $valid=true; 
           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
           $bankpaidwith=1;
           $systempaidwith=3;
           $paystatus=1;
           $status = 1;
           $time = date("h:ia, d M");
           $approvedby="Automation";
           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
           $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$orderid);
           $checkdata->execute();
       }
    }
    return $valid;
}
function verify1appDedicatedAccpay($reference,$useremail, $uname, $userid,$orderid){
    global $connect;
    $valid=false;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $verified=false;
  
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/business/verifytrans",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'reference' => $reference,
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    // print_r($response);
    $err = curl_error($curl);
    curl_close($curl);
    $tranx = json_decode($response);
    if ($tranx->status) {
        // check if it new and never exist, update payapiresponse,apipayref,apiorderid 
        // print_r($tranx);
        $paystackref=$tranx->data->reference;
        $paymenttoken=$tranx->data->transaction_token;
            //check if the transaction and the email coming and amount exist
        $notyetpaid=1;
        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE  apipayref=? AND status=?  AND userid=?");
        $checkdata->bind_param("sis",$reference, $notyetpaid,$userid);
        $checkdata->execute();
        $dresult = $checkdata->get_result(); 
       if(empty($orderid)) {
            $valid=false;
       } else if($dresult ->num_rows > 0){
            $valid=false;
       }else{
            // generating  token
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","1APP",true,true,false);
           $valid=true; 
           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
           $bankpaidwith=1;
           $systempaidwith=3;
           $paystatus=1;
           $status = 1;
           $time = date("h:ia, d M");
           $approvedby="Automation";
           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
           $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$orderid);
           $checkdata->execute();
       }
    }
    return $valid;
}
function getAllOneAppBank(){
    $allbnkarr=[];
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['apikey'];

    $url = "https://api.oneappgo.com/v1/";
    // $params = json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url . "listbanks",
        // CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $activepaystackapi", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache",
        ),
    ));
    $allbanks = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $allbnkarr=[];
        throw new \Exception("Error getting bank names: $err");

    } else {
        $dbanks = json_decode($allbanks);
        if($dbanks->status==true){
            $banks = $dbanks->transactions;
            foreach ($banks as $abc) {
                $bankname= trim($abc->bname);
                $bankcoode=trim($abc->code);
                array_push($allbnkarr, array( "name"=>$bankname, "code"=>$bankcoode,"combined"=>"$bankname^$bankcoode"));
            }
        }else{
           $allbnkarr=[];
         }
    }
    return $allbnkarr;

}
function checkOneAppBalanceAmount($moneytosend){
    $canpay=false;
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];

    $url ="https://api.oneappgo.com/v1/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $url . "balance",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $activepaystackapi", //replace this with your own test key
    "content-type: application/json",
    "cache-control: no-cache"
    ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $canpay=false;
        throw new \Exception("Error getting account names: $err");
    } else {
        // print($resp);
        $responses = json_decode($response);
        //  var_dump($responses);
        if (isset($responses->status) && $responses->status==true) {
            $mybal= $responses->available_bal;
            if(($mybal)>=$moneytosend){
                $canpay=true;
            } else {
                $canpay=false;
            }
        } else {
            $canpay=false;
        }
    }
        return $canpay;
}
function getAccountNameOneApp($bnkcode,$accno){
    $datatosend="";
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['apikey'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/validate-acctname?acctno=".$accno."&bankcode=".$bnkcode."",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        "Authorization: Bearer $activepaystackapi"
        ),
    ));

    $resp = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $datatosend="";
        throw new \Exception("Error getting account names: $err");
    } else {
        // print($resp);
        $responses = json_decode($resp);
        //var_dump($responses);
        if (isset($responses->account_name)) {
            $status = $responses->status;
            $acnt_name = $responses->account_name;

            if ($status) {
                $datatosend=$acnt_name;
            } else {
                $datatosend='Invalid account number';
            }
        } else {
            $datatosend='Invalid account number';
        }
    }
        return $datatosend;

}
function oneAppSendMoney($amount,$accbnkcode,$bnkname,$acctosendto,$userbanrefcode,$transorderid,$accname){
    $canpay=false;
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];

    # try to contact service sending the money
   
    $arr =  array(
        'amount' => $amount, 
        'acctname' => $accname,
        'bankcode' => $accbnkcode,
        'bankname' => $bnkname,
        'reference' => $transorderid,
        'accountno' => $acctosendto,
        'narration' => 'Transer to client',
        'currency' => 'NGN');
        //print_r($arr);
        //below is the base url
        $url ="https://api.oneappgo.com/v1/sendmoney";
        $params =  json_encode($arr);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
        ),
        ));
        $resp = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    if ($err) {
        $canpay=false;
        throw new \Exception("Error getting account names: $err");
    } else {
        // print($resp);
        $responses = json_decode($resp);
        //var_dump($responses);
        if (isset($responses->status) && $responses->status==true) {
           $paystackref= $responses->txref;
           $paymenttoken=" ";
            $canpay=true;
            // update transaction ref as paid
            if (!empty($transorderid)&&$transorderid!="") {
                     // generating  token
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $companypayref = createUniqueToken(16,"userwallettrans","paymentref","1APP",true,true,false);
                $valid=true; 
                // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                $bankpaidwith=1;
                $systempaidwith=3;
                $paystatus=1;
                $status = 1;
                $time = date("h:ia, d M");
                $approvedby="Automation";
                $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$resp,$paystackref,$paymenttoken,$approvedby,$transorderid);
                $checkdata->execute();
            }
           
        } else {
            $canpay=false;
        }
    }
        return $canpay;
}
function oneappVerifyBVN($acctno,$bankcode,$bvnno){
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $authlink="";
    $valid=false;

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/business/initiatetrans",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'acctno'=>$acctno,
        'bankcode'=>$bankcode,
        'bvnno' => $bvnno,
    ]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $valid=false;
    } else {
        $tranx = json_decode($response);
        if (!$tranx->status) {
            $valid=false;
        } else {
            $valid=true;
        }
   }
   return  $valid;
}
function oneappVerifyBasicBVN($bvnno,$userid){
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $authlink="";
    $valid=false;

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.oneappgo.com/v1/bvnkyc",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode(['bvnno' => $bvnno,'verify_type'=> "basic"]),
    CURLOPT_HTTPHEADER => [
        "authorization: Bearer $activepaystackapi", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
    ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $valid=false;
    } else {
        // print_r($response);
        $tranx = json_decode($response);
        if (!$tranx->status) {
            $valid=false;
            $insert_data = $connect->prepare("INSERT INTO  bvncalls (userid,jsonresponse) VALUES (?,?)");
            $insert_data->bind_param("ss", $userid, $response);
            $insert_data->execute();
            $insert_data->close();
        } else {
            $valid=true;
            $pno="".$tranx->data->phone_number."";
            $title="".$tranx->data->title."";
            $fname ="".$tranx->data->firstname."";
            $lname ="".$tranx->data->lastname."";
            $mname ="".$tranx->data->middlename."";
            $fullname ="".$tranx->data->fullname."";
         
        
            $insert_data = $connect->prepare("INSERT INTO  bvncalls (userid,jsonresponse,pno,title,fname,middlename,lastname,fullname) VALUES (?,?,?,?,?,?,?,?)");
            $insert_data->bind_param("ssssssss", $userid, $response, $pno,$title,$fname,$mname,$lname,$fullname);
            $insert_data->execute();
            $insert_data->close();
        }
   }
   return  $valid;
}



// not used again
function GetBankCodeFromBankName($bankname,$sendbankcode){
    $bankcodeis="";
    $getallbanks=getAllPayStackBank();
    foreach ($getallbanks as $bank) {
        if(strtolower($bankname)==strtolower($bank['name'])){
            $bankcodeis=$bank['code'];
            break;
        }
    }
    if(empty($bankcodeis)||$bankcodeis=""){
        $bankcodeis=$sendbankcode;
    }
    return $bankcodeis;
}
function giveUserTheirPayOnOneApp($transref,$useremail, $uname,$userid,$wallettrackid,$orderid){
    global $connect;
    $successful=false;
           
    //check if the transaction and the email coming and amount exist
    $notyetpaid=0;
    $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE orderid=? AND status=?  AND userid=?");
    $checkdata->bind_param("sis",$transref, $notyetpaid,$userid);
    $checkdata->execute();
    $dresult = $checkdata->get_result(); 
       if(empty($transref)) {
            $successful=false;
       } else if($dresult ->num_rows == 0){
            $successful=false;
       }else{
           $checkdata->close();
           //get the transaction ref to use from DB
           $rr = $dresult->fetch_assoc();
           $tref = $rr['orderid'];
           $amnt = $rr['amttopay'];
           //call back action process
               if (verify1appcardpay($transref,$useremail, $uname, $userid,$orderid)) {
                    $successful=true;
                    $update_data = $connect->prepare("UPDATE userwallet SET walletbal=walletbal+? WHERE wallettrackid=?");
                    $update_data->bind_param("is", $amnt, $wallettrackid);
                    $update_data->execute();
                }
        }
     return $successful;
} 
function giveUserTheirPayOnPayStack($transref,$useremail, $uname,$userid,$wallettrackid){
    global $connect;
        $successful=false;
           
    //check if the transaction and the email coming and amount exist
    $notyetpaid=0;
    $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE orderid=? AND status=?  AND userid=?");
    $checkdata->bind_param("sis",$transref, $notyetpaid,$userid);
    $checkdata->execute();
    $dresult = $checkdata->get_result(); 
       if(empty($transref)) {
            $successful=false;
       } else if($dresult ->num_rows == 0){
            $successful=false;
       }else{
           $checkdata->close();
           //get the transaction ref to use from DB
           $rr = $dresult->fetch_assoc();
           $tref = $rr['orderid'];
           $amnt = $rr['amttopay'];
           //call back action process
               if (verifypaystackcardpay($transref,$useremail, $uname, $userid)) {
                       $successful=true;
                       $confirmtime = date("h:ia, d/M");
                       $update_data = $connect->prepare("UPDATE userwallet SET walletbal=walletbal+? WHERE wallettrackid=?");
                       $update_data->bind_param("is", $amnt, $wallettrackid);
                       $update_data->execute();
                }
        }
     return $successful;
}
function getAllBankList(){
    //Get the active payment system
    $alldata=[];
    $activepaysystem= paygetAllSystemSetting()['activepaysystem'];
    if($activepaysystem==1){// paystack
        $alldata=getAllPayStackBank();
    }else if($activepaysystem==2){//monify
        $alldata=getallMonifyBanklist();
    }
    else if($activepaysystem==3){//1app
        $alldata=getAllOneAppBank();
    }
    else{
        $alldata=[]; 
    }
    return $alldata;
}


//Dynamic Bank Account
function getAllOneAppBankGeneratedList(){
    $allbnkarr=[];
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['apikey'];

    $url = "https://api.oneappgo.com/v1/";
    // $params = json_encode($arr);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url . "partnerbank",
        // CURLOPT_POSTFIELDS => $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $activepaystackapi", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache",
        ),
    ));
    $allbanks = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $allbnkarr=[];
        throw new \Exception("Error getting bank names: $err");

    } else {
        $dbanks = json_decode($allbanks);
        if($dbanks->status==true){
            $banks = $dbanks->data;
            foreach ($banks as $abc) {
                $bankname= trim($abc->bankname);
                $bankcoode=trim($abc->bankcode);
                $combined = "$bankname: $bankcoode";
                $status = 1;
                if (!checkIfCodeisInDB($connect, 'oneappbankgenerate', 'name',$bankname)){
                    $addQuery =  "INSERT INTO `oneappbankgenerate` (`name`,`code`, `combined`,`status`) VALUES (?,?,?,?)";
                    $stmt = $connect->prepare($addQuery);
                    $stmt->bind_param("ssss", $bankname, $bankcoode,$combined, $status);
                    
                    if($stmt->execute()){
                        array_push($allbnkarr, array( "name"=>$bankname, "code"=>$bankcoode,"combined"=>"$bankname: $bankcoode"));
                    }
                }
                else{
                    array_push($allbnkarr, array( "name"=>$bankname, "code"=>$bankcoode,"combined"=>"$bankname: $bankcoode"));
                }
                
            }
        }else{
          $allbnkarr=[];
         }
    }
    return $allbnkarr;
}
function OneAppBankUpdateBankList($bankcode,$bankname){
    $allbnkarr=[];
    global $connect;
    $activepaystackapi=GetActiveOneappApi()['secretekey'];
    $data = '{
            "bankcode": "'.$bankcode.'",
            "bankname": "'.$bankname.'"
        }';
    $url = "https://api.oneappgo.com/v1/";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url . "updatebankprefer",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            "authorization: Bearer $activepaystackapi", //replace this with your own test key
            "content-type: application/json",
            "cache-control: no-cache",
        ),
      
    ));
    $allbanks = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $allbnkarr=[];
        throw new \Exception("Error getting bank names: $err");
    } else {
        $dbanks = json_decode($allbanks);
        if($dbanks->status==true){
           $allbnkarr = $dbanks->message;
        }else{
          $allbnkarr='Error Updating Preferred Bank';
        }
    }
    return $allbnkarr;
}
function oneappgenerateAccNumber($fname,$lname,$useremail,$phone,$bvn,$bankcode,$banktypename,$userid){// enusure the user has filled his name before he can generate an account number
        $dashname="$lname $fname";
        $dashemail=$useremail;
        $generated=false;
        global $connect;
        
        $activepaystackapi=GetActiveOneappApi()['secretekey'];
        //echo $activepaystackapi;
        
        # try to contact service sending the money
        //below is the base url
        //getting uniq acc ref no
        $permitted_chars2 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $loop = 0;
        while ($loop==0) {
            $myrefcode= generate_string($permitted_chars2, 6);
            $check =  $connect->prepare("SELECT id FROM userpersonalbnkacc WHERE accrefcode=?");
            $check->bind_param("s", $myrefcode);
            $check->execute();
            $result2 =  $check->get_result();
            if ($result2->num_rows > 0) {
                $loop = 0;
            } else {
                $loop = 1;
                break;
            }
        }
        $data = '{
            "trackingid": "'.$myrefcode.'",
            "firstname": "'.$fname.'",
            "lastname": "'.$lname.'",
            "userbvn": "'.$bvn.'",
            "useremail": "'.$useremail.'",
            "userphone": "'.$phone.'",
            "bankcode": "'.$bankcode.'"
            
          }';
          //echo $data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.oneappgo.com/v1/dedicated-account',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$activepaystackapi,
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        // print_r($response);
        curl_close($curl);
        $gtdata=json_decode($response);
        //print_r($gtdata);
        //$res = json_decode($err);
        if(isset($gtdata->status) && $gtdata->status==true){
            $newbankaccno = $gtdata->acctno;
            $newreseverref = $gtdata->trackingref;
            $acctname =$gtdata->acctname;
            $type = 3;
            $insert_data = $connect->prepare("INSERT INTO userpersonalbnkacc (userid,bankname,accno,accrefcode,accserverrefcode,banksystemtype,acctname,banktypeis) VALUES (?,?,?,?,?,?,?,?)");
            $insert_data->bind_param("ssssssss", $userid, $banktypename, $newbankaccno,$myrefcode,$newreseverref,$bankcode,$acctname,$type);
            $insert_data->execute();
            $generated=true;
            $insert_data->close();
            $generated=true;
        }else{
            $generated=false;
        }
        return $generated;
}

//Fecthing User Bvn Details
function fetchBvnDetails($bvn){
    $secret_key = getBvnDetails()['secretekey']; 
    $appid = getBvnDetails()['appid'];
    $url ="https://api.dojah.io/api/v1/kyc/bvn/full?bvn=$bvn";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => '',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
            "authorization: $secret_key",
            "appid: $appid",
            "cache-control: no-cache"
        ),
    ));
    $userdetails = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($userdetails);
    return $response; 
}
function verifyBvnwith_DOj($bvn,$userid){
       global $connect;
    $secret_key = getBvnDetails()['secretekey']; 
    $appid = getBvnDetails()['appid']; 
    $url ="https://api.dojah.io/api/v1/kyc/bvn/full?bvn=$bvn";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        //u change the url infront based on the request u want
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => '',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //change this based on what u need post,get etc
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
            "authorization: $secret_key",
            "appid: $appid",
            "cache-control: no-cache"
        ),
    ));
    $userdetails = curl_exec($curl);
    // print_r($userdetails);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($userdetails);
    if (isset($response->entity->phone_number1)) {
            $valid=true;
            $pno="".$response->entity->phone_number1."";
            $title="";
            if(isset($response->entity->title)){
            $title="".$response->entity->title."";
            }
            $fname ="".$response->entity->first_name."";
            $lname ="".$response->entity->last_name."";
            $mname ="".$response->entity->middle_name."";
            $fullname ="$fname $mname $lname";
            
            $insert_data = $connect->prepare("INSERT INTO  bvncalls (userid,jsonresponse,pno,title,fname,middlename,lastname,fullname) VALUES (?,?,?,?,?,?,?,?)");
            $insert_data->bind_param("ssssssss", $userid, $userdetails, $pno,$title,$fname,$mname,$lname,$fullname);
            $insert_data->execute();
            $insert_data->close();
    } else {
            $valid=false;
            $data = json_decode($userdetails, true);
            // {"error":"Unable to reach service"}
            if (isset($data['error']) && ($data['error'] === 'Your balance is low, pls visit the dashboard to top up' || $data['error'] === 'Unable to reach service')) {
                // dont do anything
            }else{
                $insert_data = $connect->prepare("INSERT INTO  bvncalls (userid,jsonresponse) VALUES (?,?)");
                $insert_data->bind_param("ss", $userid, $userdetails);
                $insert_data->execute();
                $insert_data->close();
            }
    }
    return $valid; 
}




// VIRTUAL CARD
function GetActiveVirtualCardApi($currency){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM vc_access_keys WHERE status=? AND currency=?");
    $getdataemail->bind_param("ss",$active,$currency);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}

function createVC_customer($userid,$currency){
    global $connect;
        $valid=false;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        
        $active=1;
        $getdataemail =  $connect->prepare("SELECT * FROM kyc_details WHERE user_id=?");
        $getdataemail->bind_param("s",$userid);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if( $getresultemail->num_rows> 0){
                $getthedata= $getresultemail->fetch_assoc();
                
                  // create customer 
                $customerFname=$getthedata['fname'];
                $customerLname=$getthedata['lname'];
                $customerFullname=$getthedata['fullname'];
                $customerAddress=$getthedata['full_address'];
                $customerCity=$getthedata['city'];
                $customerState=$getthedata['stateorigin'];
                $customerCountry=$getthedata['country'];
                $customerPostalCode=$getthedata['postalcode'];
                $customerphonenumber=$getthedata['phoneno'];
                $customerEmail=$getthedata['email'];
                $customerDob=$getthedata['dob'];
                $customerBVN=$getthedata['bvn'];
                $customertype='individual';
                $postdatais=array (
                    'type' => $customertype,//'individual',
                    'name' => $customerFullname,
                    'status' => 'active',
                    'phoneNumber' => $customerphonenumber,
                    'emailAddress' => $customerEmail,
                    'individual' => array (
                        'firstName' => $customerFname,
                        'lastName' => $customerLname,
                        'dob' => $customerDob,
                        'identity' => array (
                            'type' => 'BVN',
                            'number' => $customerBVN,
                        ),
                     ),
                    'billingAddress' => array (
                        'line1' => $customerAddress,
                        'line2' => '',
                        'city' =>$customerCity,
                        'state' =>$customerState,
                        'country' =>$customerCountry,
                        'postalCode' =>$customerPostalCode,
                    ),
                );
               
                $jsonpostdata=json_encode($postdatais);
                //  print($jsonpostdata);
               // print_r($postdatais);
                $url ="$baseurl/customers";
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
                            "Authorization: Bearer $token",
                            "content-type: application/json",
                            'accept: application/json',
                             
                        ),
                    ));
                $userdetails = curl_exec($curl);
                
                $allresp="$userdetails";
                $paymentidisni="SD CEATE VC CUST";
                $orderidni="$jsonpostdata";
                $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                $insert_data->execute();
                $insert_data->close();
                
                // print_r($userdetails);
                $err = curl_error($curl);
                // print_r($err);
                curl_close($curl);
                $breakdata = json_decode($userdetails);
                if($breakdata->statusCode==200){
                    
                    $accountid=$breakdata->data->_id;
                    $valid=true;
                    
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $trackid= createUniqueToken(5,"vc_customers","trackid","$currency",true,true,false);
                    $active=1;
                    $supplier=1;
                    $insert_data = $connect->prepare("INSERT INTO  vc_customers (user_id,customer_id,customer_type,trackid,status,json,supplier) VALUES (?,?,?,?,?,?,?)");
                    $insert_data->bind_param("sssssss",$userid, $accountid,$customertype,$trackid,$active,$userdetails,$supplier);
                    $insert_data->execute();
                }
        }
        return $valid;
}

function generate_User_VC($userid,$currency,$cardtype_tid,$customerId,$amount){
        global $connect;
        $valid=false;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        $creationfee=1;
                
    
        // fund user wallet for card creation fee deposit
        $fundtype="";
        if($currency=="USD"){
            $fundtype="fund_usd=?";
        }else{
            $fundtype="fund_naira=?";
        }
                // get account with fund in it
        $accountidto_fund_with=0;
        //  get all account 
        $active=1;
        $getdataemail =  $connect->prepare("SELECT account_id,currency,id FROM vc_main_accounts WHERE status=?");
        $getdataemail->bind_param("i",$active);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                while($getthedata= $getresultemail->fetch_assoc()){
                      //  get it id
                        $companyUsdWalletid=$getthedata['account_id'];
                    $accountidto_fund_with=$getthedata['id'];
                    $fundcurrency=$getthedata['currency'];
                       // check the one with sufficient fund
                              // get account balance
                    $ourmainwalletbal=getMainAccountBalance($currency,$companyUsdWalletid);
                    
                    if($fundcurrency=="USD"){
                        $converttocurrency=$amount;
                        if($ourmainwalletbal>=$converttocurrency){
                               break;
                        }
                    }else if($fundcurrency=="NGN"){
                        $converttocurrency=$amount*getLiveNGNtoUSDRate($currency,1);
                        if($ourmainwalletbal>=$converttocurrency){
                              break;
                        }
                    }
                }
        }

        $active=1;
        // 1 USD 2 NGN FUNDAUTO
        $accountidto_fund_with=2;
        $getdataemail =  $connect->prepare("SELECT account_id,currency FROM vc_main_accounts WHERE id=?");
        $getdataemail->bind_param("i",$accountidto_fund_with);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                            $getthedata= $getresultemail->fetch_assoc();
                            $companyUsdWalletid=$getthedata['account_id'];
                            $fundcurrency=$getthedata['currency'];
                            $ourmainwalletbal=0;
                            
                            $active=1;
                            $getdataemail =  $connect->prepare("SELECT cardbrand,country,cardtype,daily_limit,monthly_limit,weekly_limit FROM vc_type WHERE trackid=? AND status=?");
                            $getdataemail->bind_param("si",$cardtype_tid,$active);
                            $getdataemail->execute();
                            $getresultemail = $getdataemail->get_result();
                            if($getresultemail->num_rows> 0){
                                    $getthedata= $getresultemail->fetch_assoc();
                                    $brand=$getthedata['cardbrand'];
                                    $country=$getthedata['country'];
                                    $cardType=$getthedata['cardtype'];
                                    $maxdaily=$getthedata['daily_limit'];
                                    $maxMonthly=$getthedata['monthly_limit'];
                                    $maxweekly=$getthedata['weekly_limit'];
                     
                      
                                     // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                                    $trackid= createUniqueToken(5,"vc_customer_card","trackid","$currency",true,true,false);    
                                    $postdatais=array (
                                        'customerId' => $customerId,
                                        'debitAccountId'=>$companyUsdWalletid,// => $walletid,
                                        'issuerCountry' => $country,
                                        'brand' => $brand,
                                        'amount'=>intval($amount),
                                        'type' => $cardType,
                                        'currency' => $currency,
                                        'status' => 'active',
                                        'metadata' => 
                                        array ("trackid"=>$trackid),
                                        'spendingControls' => 
                                        array (
                                            'channels' =>   array (
                                              'atm' => true,
                                              'pos' => true,
                                              'web' => true,
                                              'mobile' => true,
                                            ),
                                            'allowedCategories' => array ( ),
                                            'blockedCategories' => array ( ),
                                            'spendingLimits' => array (
                                                  0 => 
                                                  array (
                                                    'amount' => intval($maxdaily),
                                                    'interval' => 'daily',
                                                  ),
                                                  1 => 
                                                  array (
                                                    'amount' => intval($maxweekly),
                                                    'interval' => 'weekly',
                                                  ),
                                                  2 => 
                                                  array (
                                                    'amount' => intval($maxMonthly),
                                                    'interval' => 'monthly',
                                                  ),
                                            ),
                                      ),
                                    );
                                    $jsonpostdata=json_encode($postdatais);
                                    // print($jsonpostdata);
                                    $url ="$vaulturl/cards";
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
                                                "Authorization: Bearer $token",
                                                "content-type: application/json",
                                                'accept: application/json',
                                                 
                                            ),
                                        ));
                                    $userdetails = curl_exec($curl);
                                    
                                    $allresp="$userdetails";
                                    $paymentidisni="SD CREATE VC";
                                    $orderidni="$jsonpostdata";
                                    $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                                    $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                                    $insert_data->execute();
                                    $insert_data->close();
 
                                    
                                    
                                    // print_r($userdetails);
                                    $err = curl_error($curl);
                                    // print_r($err);
                                    curl_close($curl);
                                      $breakdata = json_decode($userdetails);
                                    if(isset($breakdata->statusCode) && $breakdata->statusCode==200){
                                        $accountid=$breakdata->data->_id;
                                        $brand=$breakdata->data->brand;
                                        $last4=$breakdata->data->last4;
                                        $walletid=$breakdata->data->account->_id;
                                        $cvv="***";
                                        $maskedPan=$breakdata->data->maskedPan;
                                        $expiryMonth=$breakdata->data->expiryMonth;
                                        $expiryYear=$breakdata->data->expiryYear;
                                        // $expiryYear=substr_replace($expiryYear,"",0,2);
                                        $valid=$trackid;
                                        $currentYear = date('Y');
                                        if (strlen($expiryYear) == 2) {
                                            $expiryYear = substr($currentYear, 0, 2) . $expiryYear;
                                        }
                                        
                                       
                                        $active=1;
                                        $empty=0;
                                        $insert_data = $connect->prepare("INSERT INTO  vc_customer_card (vc_card_id,user_id,customer_id,wallet_id,status,trackid,balance,vc_type_tid,json_response,brand,last4,cvv,pan,expireMonth,expireyear,freeze,activated,cansetpin) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                        $insert_data->bind_param("ssssssssssssssssss",$accountid,$userid,$customerId,$walletid,$active,$trackid,$empty,$cardtype_tid,$userdetails,$brand,$last4, $cvv,$maskedPan,$expiryMonth,$expiryYear,$empty,$active,$empty);
                                        $insert_data->execute();
                                    }else{
                                        // message
                                        $from="SUDO CARD CREATION $userid";
                                         $message="An error occured when generating card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
                                    }
                                           
                        }
        }
        
        return $valid;
}

function generateVC_MainAndSubWallet($walletype,$customerId,$currency,$userid){
          global $connect;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        $postdatais=[];
        // $walletype 1 main wallet 2 user wallet
        if($walletype==1){
            $account="account";//"wallet";//account
            $postdatais=array (
                'currency' => $currency,
                'type' => $account,
                'accountType' => $accountType,
            );
        }else if($walletype==2){
           $account="wallet";
           $postdatais=array (
                'currency' => $currency,
                'type' => $account,
                'accountType' => $accountType,
                "customerId" => $customerId
            ); 
        }
        $jsonpostdata=json_encode($postdatais);
        $url ="$baseurl/accounts";
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
                    "Authorization: Bearer $token",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        $allresp="$userdetails";
        $paymentidisni="SD VC GEN ADDRESS";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if($breakdata->statusCode==200){
            $accountid=$breakdata->data->_id;
            
            if($walletype==1){//main wallet
                        $name="Main $currency account";
                          // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                        $trackid= createUniqueToken(5,"vc_main_accounts","trackid","$currency",true,true,false);
                        $active=1;
                        $insert_data = $connect->prepare("INSERT INTO vc_main_accounts (name,account_id,currency,trackid,status,account_json) VALUES (?,?,?,?,?,?)");
                        $insert_data->bind_param("ssssss",$name, $accountid,$currency,$trackid,$active,$userdetails);
                        $insert_data->execute();
            }else if($walletype==2){// user wallet
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $trackid= createUniqueToken(5,"vc_customer_wallets","trackid","$currency",true,true,false);
                $active=1;
                $insert_data = $connect->prepare("INSERT INTO vc_customer_wallets (user_id,customer_id,wallet_id, status,trackid, account_type,currency,json_response) VALUES (?,?,?,?,?,?,?,?)");
                $insert_data->bind_param("ssssssss",$userid,$customerId, $accountid,$active,$trackid,$accountType, $currency,$userdetails);
                $insert_data->execute();
            } 
            $success=true;
        }else{
            
        }
        
        return $success;
}

function getNGNtoUSDRate($currency,$fund,$addprofit=1){
        $vc_data=GetActiveVirtualCardApi($currency);
        $amount=0;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        
        $systemsettings=paygetAllSystemSetting();

         if($fund==1){
              $amount=$systemsettings['ngn_load_rate'];
         }else{
              $amount=$systemsettings['ngn_unload_rate'];
         }

        // $url ="$baseurl/accounts/transfer/rate/USDNGN";
        // $curl = curl_init();
        // curl_setopt_array(
        //         $curl, array(
        //         CURLOPT_URL => $url,
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => "",
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 60,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => "GET",
        //         CURLOPT_HTTPHEADER => array(
        //             "Authorization: Bearer $token",
        //             "content-type: application/json",
        //             'accept: application/json',
                     
        //         ),
        //     ));
        // $userdetails = curl_exec($curl);
        // // print_r($userdetails);
        // $err = curl_error($curl);
        // // print_r($err);
        // curl_close($curl);
        // $breakdata = json_decode($userdetails);
        // if($breakdata->statusCode==200){
        //     if($fund==1){
        //         // getrate
        //         if($addprofit==1){
        //         $amount=$breakdata->data->buy + 10; //usd to naira
        //         }else{
        //           $amount=$breakdata->data->buy; //usd to naira  
        //         }
        //     }else{
        //         $amount=$breakdata->data->sell-10;// naira to usd
        //     }
        //     // {"statusCode":200,"message":"Exchange rate fetched successfully.","data":{"rate":"756.00","sell":"737.00","buy":"756.00"}}
        // }
                   
     return $amount;
}

function getLiveNGNtoUSDRate($currency,$fund){
        $vc_data=GetActiveVirtualCardApi($currency);
        $amount=0;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];

        $url ="$baseurl/accounts/transfer/rate/USDNGN";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if(isset($breakdata->statusCode) &&     $breakdata->statusCode==200){
            if($fund==1){
                // getrate
                   $amount=$breakdata->data->buy; //usd to naira  
            }else{
                $amount=$breakdata->data->sell;// naira to usd
            }
        }
                   
     return $amount;
}
function getMainAccountBalance($currency,$accountid){
        $vc_data=GetActiveVirtualCardApi($currency);
        $amount=0;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];


        $url ="$baseurl/accounts/$accountid/balance";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if(isset($breakdata->statusCode) && $breakdata->statusCode==200){
                $amount=$breakdata->data->availableBalance; 
        }
                   
     return $amount;
}

function fundUserWallet($walletid,$amount,$narration,$payref,$currency,$userid){
    global  $connect;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;//0= not started at all,1=success, 2= no fund,3 = server error 4 empty order id 5 duplicate trans blocked
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];

        $fundtype="";
        if($currency=="USD"){
            $fundtype="fund_usd=?";
        }else{
            $fundtype="fund_naira=?";
        }
        
        // get account with fund in it
        $accountidto_fund_with=0;
        //  get all account 
        $active=1;
        $getdataemail =  $connect->prepare("SELECT account_id,currency,id FROM vc_main_accounts WHERE status=?");
        $getdataemail->bind_param("i",$active);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                while($getthedata= $getresultemail->fetch_assoc()){
                      //  get it id
                        $companyUsdWalletid=$getthedata['account_id'];
                    $accountidto_fund_with=$getthedata['id'];
                    $fundcurrency=$getthedata['currency'];
                       // check the one with sufficient fund
                              // get account balance
                    $ourmainwalletbal=getMainAccountBalance($currency,$companyUsdWalletid);
                    
                    if($fundcurrency=="USD"){
                        $converttocurrency=$amount;
                        if($ourmainwalletbal>=$converttocurrency){
                               break;
                        }else{
                            $success=2;
                        }
                    }else if($fundcurrency=="NGN"){
                        $converttocurrency=$amount*getLiveNGNtoUSDRate($currency,1);
                        if($ourmainwalletbal>=$converttocurrency){
                              break;
                        }else{
                            $success=2;
                        }
                    }
                }
        }
     
      
        // then fund card with it
        $active=1;
        // 1 USD 2 NGN FUNDAUTO
        $accountidto_fund_with=2;
        $getdataemail =  $connect->prepare("SELECT account_id,currency FROM vc_main_accounts WHERE id=?");
        $getdataemail->bind_param("i",$accountidto_fund_with);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                $getthedata= $getresultemail->fetch_assoc();
                $companyUsdWalletid=$getthedata['account_id'];
                $fundcurrency=$getthedata['currency'];
                $ourmainwalletbal=0;
                
                    //check account balance
                //  if funding usd and its naira wallet 
                //  call exchange rate and convert to usd and check if fund is enough
                // else check if fund is enough directly 
                //
                
                // get account balance
                $ourmainwalletbal=getMainAccountBalance($currency,$companyUsdWalletid);
                
                $enoughfund=false;
                if($fundcurrency=="USD"){
                    $converttocurrency=$amount;
                    if($ourmainwalletbal>=$converttocurrency){
                            $enoughfund=true;
                    }
                }else if($fundcurrency=="NGN"){
                    $converttocurrency=$amount*getLiveNGNtoUSDRate($currency,1);
                    if($ourmainwalletbal>=$converttocurrency){
                            $enoughfund=true;
                    }
                }
            
                if($enoughfund){
                    $postdatais=array (
                        'debitAccountId' => $companyUsdWalletid,
                        'creditAccountId' =>$walletid ,
                        'amount' =>floatval($amount),
                        "narration"=> $narration,
                        "paymentReference"=>$payref
                    );
                    $jsonpostdata=json_encode($postdatais);
                    // print($jsonpostdata);
                    $url ="$baseurl/accounts/transfer";
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
                                "Authorization: Bearer $token",
                                "content-type: application/json",
                                'accept: application/json',
                                 
                            ),
                        ));
                    $userdetails = curl_exec($curl);
                    
                    $allresp="$userdetails";
                    $paymentidisni="SD VC FUND";
                    $orderidni="$jsonpostdata";
                    $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                    $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                    $insert_data->execute();
                    $insert_data->close();
        
                    // print_r($userdetails);
                    $err = curl_error($curl);
                    // print_r($err);
                    curl_close($curl);
                    $breakdata = json_decode($userdetails);
                    if(isset($breakdata->statusCode) && $breakdata->statusCode==200){
                        $orderidis=$breakdata->data->paymentReference;
                        $paymenttoken=$breakdata->data->_id;
                        $notyetpaid=1;
                        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE apipayref=? AND status=?  AND userid=?");
                        $checkdata->bind_param("sis",$orderidis, $notyetpaid,$userid);
                        $checkdata->execute();
                        $dresult = $checkdata->get_result(); 
                        if(empty($orderidis)) {
                                 $success=false;
                                 $success=4;
                       } else if($dresult ->num_rows > 0){
                             $success=false;
                             $success=5;
                       }else{
                            // generating  token
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","FVC",true,true,false);
                            $success=1; 
                           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                           $bankpaidwith=1;
                           $systempaidwith=2;
                           $paystatus=1;
                           $status = 1;
                           $time = date("h:ia, d M");
                           $approvedby="Automation";
                           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                           $checkdata->bind_param("sssssssss",$companypayref, $paystatus,$status,$time,$userdetails,$orderidis,$paymenttoken,$approvedby,$payref);
                           $checkdata->execute();
                       }
                    }else{
                          // message
                                        $from="SUDO FUND CARD ERROR $userid";
                                         $message="An error occured when funding card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                       $success=3;
                                        system_notify_crash_handler($message,$from);
                    }
                }
                else{
                    // message
                    $from="SUDO FUND CARD ERROR $userid";
                     $message="Insufficient fund, error funding virtual card";
                    $success=2;
                    system_notify_crash_handler($message,$from);
                }
                
                
        }
        return $success;
}

function fundCompanyWallet($walletid,$amount,$narration,$payref,$currency,$userid){
    global  $connect;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $currency=$vc_data['currency']; 
         
        $active=1;
        $getdataemail =  $connect->prepare("SELECT account_id,currency FROM vc_main_accounts WHERE currency=? AND status=?");
        $getdataemail->bind_param("si",$currency,$active);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                $getthedata= $getresultemail->fetch_assoc();
                $companyUsdWalletid=$getthedata['account_id'];
                $fundcurrency=$getthedata['currency'];
                
                $enoughfund=true;
            
                if($enoughfund){
                    $postdatais=array (
                        'debitAccountId' => $walletid,
                        'creditAccountId' => $companyUsdWalletid,
                        'amount' =>floatval($amount),
                        "narration"=> $narration,
                        "paymentReference"=>$payref
                    );
                    $jsonpostdata=json_encode($postdatais);
                    // print($jsonpostdata);
                    $url ="$baseurl/accounts/transfer";
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
                                "Authorization: Bearer $token",
                                "content-type: application/json",
                                'accept: application/json',
                                 
                            ),
                        ));
                    $userdetails = curl_exec($curl);
                    
                    $allresp="$userdetails";
                    $paymentidisni="SD VC UNLOAD ping";
                    $orderidni="$jsonpostdata";
                    $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                    $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                    $insert_data->execute();
                    $insert_data->close();
        
                    // print_r($userdetails);
                    $err = curl_error($curl);
                    // print_r($err);
                    curl_close($curl);
                    $breakdata = json_decode($userdetails);
                    if($breakdata->statusCode==200){
                        $orderidis=$breakdata->data->paymentReference;
                        $paymenttoken=$breakdata->data->_id;
                        $notyetpaid=1;
                        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE apipayref=? AND status=?  AND userid=?");
                        $checkdata->bind_param("sis",$orderidis, $notyetpaid,$userid);
                        $checkdata->execute();
                        $dresult = $checkdata->get_result(); 
                        if(empty($orderidis)) {
                                 $success=false;
                       } else if($dresult ->num_rows > 0){
                             $success=false;
                       }else{
                            // generating  token
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","UVC",true,true,false);
                            $success=true; 
                           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                           $bankpaidwith=1;
                           $systempaidwith=2;
                           $paystatus=1;
                           $status = 2;
                           $time = date("h:ia, d M");
                           $approvedby="Automation";
                           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                           $checkdata->bind_param("sssssssss",$companypayref, $paystatus,$status,$time,$userdetails,$orderidis,$paymenttoken,$approvedby,$payref);
                           $checkdata->execute();
                       }
                    }else{
                          // message
                                        $from="SUDO CARD UNLOAD ERROR $userid";
                                         $message="An error occured when unloading card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
                    }
                }
                
        }
        return $success;
}


function revealCardFullData($currency,$cardid){
    global $connect;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        $url ="$vaulturl/cards/$cardid?reveal=true";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        
        $allresp="$userdetails";
        $paymentidisni="SD VC REVEL CARD";
        $orderidni="SD VC REVEAL CARD";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if(isset($breakdata->statusCode) && $breakdata->statusCode==200){
            $success=$userdetails;
        }else{
              // message
                                        $from="SUDO CARD REVEAL DETAIL";
                                         $message="An error occured when getting card data";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
        }

        return $success;
}



function changeCardStatus($currency,$cardid,$status){
    global $connect;
        $vc_data=GetActiveVirtualCardApi($currency);
        $success=false;
        $token=$vc_data['token'];
        $baseurl=$vc_data['base_url']; 
        $vaulturl=$vc_data['vault_url']; 
        $currency=$vc_data['currency']; 
        $accountType=$vc_data['account_type'];
        
        //   $status="active";//inactive,canceled,active
        $postdatais=array (
            'status' => $status,
        );
        $jsonpostdata=json_encode($postdatais);
        // print($jsonpostdata);
        $url ="$baseurl/cards/$cardid";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => trim($jsonpostdata),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        
                $allresp="$userdetails";
        $paymentidisni="SD VC CHANGE STATUS";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails);
        if($breakdata->statusCode==200){
            $success=true;
        }else{
              // message
                                        $from="SUDO FREEZE/DELETE CARD";
                                         $message="An error occured when trying to freeze/delete a card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
        }

        return $success;
}


// BC card supplier

function GetActiveBCVirtualCardApi($currency){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM vc_bc_access_key WHERE status=? AND currency=?");
    $getdataemail->bind_param("ss",$active,$currency);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}

function createBCVC_customer($userid,$currency){
     global $connect;
        $valid=false;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $currency=$vc_data['currency']; 
     
        
        $active=1;
        $getdataemail =  $connect->prepare("SELECT * FROM kyc_details WHERE user_id=?");
        $getdataemail->bind_param("s",$userid);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if( $getresultemail->num_rows> 0){
                $getthedata= $getresultemail->fetch_assoc();
                
                  // create customer 
                $customerFname=$getthedata['fname'];
                $customerLname=$getthedata['lname'];
                $customerFullname=$getthedata['fullname'];
                $customerAddress=$getthedata['full_address'];
                $customerCity=$getthedata['city'];
                $customerState=$getthedata['stateorigin'];
                $customerCountry=$getthedata['country'];
                $customerPostalCode=$getthedata['postalcode'];
                $customerhouse_number=$getthedata['house_number'];
                $customerreg_id_number=$getthedata['reg_id_number'];
                $customerreg_type=$getthedata['reg_type'];
                
                $customerphonenumber=$getthedata['phoneno'];
                $customerEmail=$getthedata['email'];
                $customerDob=$getthedata['dob'];
                $customerBVN=$getthedata['bvn'];
                $customerfront_regcard=$getthedata['vc_verify_img'];
                $redcardUrl="https://app.cardify.co/assets/images/userregulatorycards/$customerfront_regcard";
                $customertype='individual';
                if(empty($customerfront_regcard)){
                    $valid=false;
                }else{
                    $identify_array=array();
                    
                    if(strtolower($customerCountry)=="nigeria"){
                        $regtypetext="";
                        if($customerreg_type==1){
                           $regtypetext="NIGERIAN_NIN"; 
                        }else if($customerreg_type==2){
                           $regtypetext="NIGERIAN_DRIVERS_LICENSE"; 
                        }if($customerreg_type==3){
                           $regtypetext="NIGERIAN_PVC"; 
                        }if($customerreg_type==4){
                           $regtypetext="NIGERIAN_INTERNATIONAL_PASSPORT"; 
                        }
                        // you are to send 1 for National id card, 2 for drivers license 3 for Voters card and 4 for international passport
                        // "NIGERIAN_NIN" or "NIGERIAN_INTERNATIONAL_PASSPORT" or "NIGERIAN_PVC" or "NIGERIAN_DRIVERS_LICENSE",
                        $identify_array=  array (
                            'id_type' => $regtypetext,
                            'id_no' => $customerreg_id_number,
                            'id_image' => $redcardUrl,
                            'bvn' => $customerBVN,
                        );
                    }
                    
                    $postdatais=array (
                                'first_name' => $customerFname,
                                'last_name' => $customerLname,
                                'address' => array (
        'address' =>$customerAddress,
        'city' => $customerCity,
        'state' => $customerState,
        'country' =>  $customerCountry,
        'postal_code' => $customerPostalCode,
        'house_no' => $customerhouse_number,
      ),
                                'phone' => $customerphonenumber,
                                'email_address' => $customerEmail,
                                'identity' => $identify_array,
                                'meta_data' => array (
        'userid' => $userid,
      ),
                    );
                    $jsonpostdata=json_encode($postdatais);
                    //  print($jsonpostdata);
                    $url ="$baseurl/cardholder/register_cardholder";
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
                                "token: Bearer  $authkey",
                                "content-type: application/json",
                                'accept: application/json',
                            ),
                        ));
                    $userdetails = curl_exec($curl);
                    
                    
                            $allresp="$userdetails";
        $paymentidisni="BC CREATE CUST";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
        
                    // print_r($userdetails);
                    $err = curl_error($curl);
                    // print_r($err);
                    curl_close($curl);
                    $breakdata = json_decode($userdetails);
                    if(isset($breakdata->status)){
                        $valid=true;
                        $cardhlderidis=$breakdata->data->cardholder_id;
                        // save data in a tabel for webhook reference
                        // start here
                        $update_data = $connect->prepare("UPDATE users SET vc_card_token=? WHERE id=?");
                        $update_data->bind_param("ss",$cardhlderidis,$userid);
                        $update_data->execute();
                        $update_data->close();
                    }else{
                       $valid= $breakdata->message;
                    }
                }
        }
        return $valid;
}

function generate_User_BcVC($userid,$currency,$cardtype_tid,$customerId,$amount){
        global $connect;
        $valid=false;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
        $creationfee=1;
            
        $active=1;
        $getdataemail =  $connect->prepare("SELECT cardbrand,country,cardtype,daily_limit,monthly_limit,weekly_limit,currency,need_activation FROM vc_type WHERE trackid=? AND status=?");
        $getdataemail->bind_param("si",$cardtype_tid,$active);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if($getresultemail->num_rows> 0){
                $getthedata= $getresultemail->fetch_assoc();
                
                $need_activation=$getthedata['need_activation'];
                $brand="Visa2";
                if($need_activation==0){
                    $brand="Visa";
                }
                // $brand=$getthedata['cardbrand'];
                
                 $mainbrand=$getthedata['cardbrand'];
                $country=$getthedata['country'];
                $cardType=$getthedata['cardtype'];
                $maxdaily=$getthedata['daily_limit'];
                $maxMonthly=$getthedata['monthly_limit'];
                $maxweekly=$getthedata['weekly_limit'];
                $currency=$getthedata['currency'];
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $trackid= createUniqueToken(5,"vc_customer_card","trackid","$currency",true,true,false);    
                $postdatais=array (
                    'cardholder_id' => $customerId,
                    'card_type' => $cardType,
                    'card_brand' => $brand,
                    'card_currency' => $currency,
                    'meta_data' => array (
                        'user_id' => $userid,
                      ),
                );
                $jsonpostdata=json_encode($postdatais);
                $url ="$baseurl/cards/create_card";
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
                            "token: Bearer  $authkey",
                            "content-type: application/json",
                            'accept: application/json',
                             
                        ),
                    ));
                $userdetails = curl_exec($curl);
                
                        $allresp="$userdetails";
        $paymentidisni="BC GEN VC";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
                // print_r($userdetails);
                $err = curl_error($curl);
                // print_r($err);
                curl_close($curl);
                  $breakdata = json_decode($userdetails);
                if(isset($breakdata->status) && $breakdata->status== "success"){

                                $accountid=$breakdata->data->card_id;
                                $valid=$trackid;
                               
                                // GET CARD DETAILS API
                                $url ="$relay_url/cards/get_card_details?card_id=$accountid";
                                $curl = curl_init();
                                curl_setopt_array(
                                $curl, array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 60,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_HTTPHEADER => array(
                                    "token: Bearer  $authkey",
                                    "content-type: application/json",
                                    'accept: application/json',
                                ),
                                ));
                                $userdetails = curl_exec($curl);
                                // print_r($userdetails);
                                $breakdata = json_decode($userdetails);
                                if(isset($breakdata->status) && $breakdata->status== "success"){
                                        $walletid=$breakdata->data->issuing_app_id;
                                        $last4=$breakdata->data->last_4;
                                        $cvv="***";
                                        $maskedPan=$breakdata->data->card_number;
                                        $expiryMonth=$breakdata->data->expiry_month;
                                        if(strlen($expiryMonth)==1){
                                            $expiryMonth="0$expiryMonth";
                                        }
                                        $expiryYear=$breakdata->data->expiry_year;
                                   
                                        $maskedPan=substr_replace($maskedPan,"*",6,6);
                                        $breakitup=explode("*",$maskedPan);
                                        $maskedPan=$breakitup[0]."******".$breakitup[1];
                                        //  $expiryYear=substr_replace($expiryYear,"",0,2);
                                }else{
                                        $walletid=$issueid;
                                        $last4="****";
                                        $cvv="***";
                                        $maskedPan="419292*******44566";
                                        $expiryMonth="02";
                                        $expiryYear=date("Y")+4;
                                }
                                 // GET JSON FORMAT OF CARD DETAILS ENCRYPTED
                                $url ="$baseurl/cards/get_card_details?card_id=$accountid";
                                $curl = curl_init();
                                curl_setopt_array(
                                $curl, array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 60,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_HTTPHEADER => array(
                                    "token: Bearer  $authkey",
                                    "content-type: application/json",
                                    'accept: application/json',
                                ),
                                ));
                                $userdetails = curl_exec($curl);
                                $breakdata = json_decode($userdetails);
                                if(isset($breakdata->status) && $breakdata->status== "success"){
                                      $last4=$breakdata->data->last_4;
                                 }
                                
                                $active=1;
                                $empty=0;
                                $activated=0;
                                $cansetpin=1;
                                if($need_activation==0){
                                    $activated=1;
                                    $cansetpin=0;
                                }
                                
                                $insert_data = $connect->prepare("INSERT INTO  vc_customer_card (vc_card_id,user_id,customer_id,wallet_id,status,trackid,balance,vc_type_tid,json_response,brand,last4,cvv,pan,expireMonth,expireyear,freeze,activated,cansetpin,deleted) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                $insert_data->bind_param("sssssssssssssssssss",$accountid,$userid,$customerId,$walletid,$active,$trackid,$empty,$cardtype_tid,$userdetails, $mainbrand,$last4, $cvv,$maskedPan,$expiryMonth,$expiryYear,$empty,$activated,$cansetpin,$empty);
                                $insert_data->execute();
                                
                                // FUND CARD
                                $usdvalue=$amount;
                                $cardid=$accountid;
                                $amount=strval($usdvalue*100);//amount(in cents) 1usd = 100 cent
                                $orderid="$trackid";
  
                                $postdatais=array (
                                    'card_id' => $cardid,
                                    'amount' =>$amount,
                                     'currency' =>$currency,
                                       'transaction_reference' =>$orderid,
                                );
                                $jsonpostdata=json_encode($postdatais);
                                $url ="$baseurl/cards/fund_card";
                                //  $url ="$baseurl/cards/fund_issuing_wallet";
                                $curl = curl_init();
                                curl_setopt_array(
                                $curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 60,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PATCH",
                        CURLOPT_POSTFIELDS => trim($jsonpostdata),
                        CURLOPT_HTTPHEADER => array(
                            "token: Bearer  $authkey",
                            "content-type: application/json",
                            'accept: application/json',
                             
                        ),
                    ));
                                $userdetails = curl_exec($curl);
                                
                                        $allresp="$userdetails";
        $paymentidisni="BC FUND VC";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
                                // print_r($userdetails);
                                $err = curl_error($curl);
                                // print_r($err);
                                curl_close($curl);
                }else{
                      // message
                                        $from="BC CARD CREATION $userid";
                                         $message="An error occured when generating card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
                }
                                   
    }

        
        return $valid;
}

function revealBCCardFullData($currency,$cardid){
        global $connect;
        $valid=false;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency'];
        
        
         // GET CARD DETAILS API
        $url ="$relay_url/cards/get_card_details?card_id=$cardid";
        $curl = curl_init();
        curl_setopt_array(
        $curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "token: Bearer  $authkey",
            "content-type: application/json",
            'accept: application/json',
        ),
        ));
        $userdetails = curl_exec($curl);
        $allresp="$userdetails";
        $paymentidisni="BC REVEAL VC";
        $orderidni="BC REVEAL VC";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
        // print_r($userdetails);
        $breakdata = json_decode($userdetails);
        if(isset($breakdata->status) &&$breakdata->status== "success"){
          $valid=$userdetails;
        }
        return $valid;
}

function activate_update_pin($cardid,$userpin){
       
        $valid=false;
        $currency="USD";
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
        $creationfee=1;
        
        $Cardpinencrypt = AES256::encrypt($userpin, $secretekey);

        $postdatais=array (
            'card_id' => $cardid,
            'card_pin' =>$Cardpinencrypt,
        );
        $jsonpostdata=json_encode($postdatais);
        $url ="$baseurl/cards/set_3d_secure_pin";
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
                    "token: Bearer  $authkey",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
          $breakdata = json_decode($userdetails);
        if(isset($breakdata->status) && $breakdata->status== "success"){
            $valid=true;
        }
            return $valid;
}

function fundUserBCWallet($walletid,$amount,$narration,$payref,$currency,$userid){
        global  $connect;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;//0= not started at all,1=success, 2= no fund,3 = server error 4 empty order id 5 duplicate trans blocked
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
      
                
                // get account balance
                $enoughfund=false;
                $ourmainwalletbal=getMainAccountBcBalance($currency);
                if($ourmainwalletbal>=$amount){
                    $enoughfund=true;
                }
           
             
                if($enoughfund){
                    $usdvalue=$amount;
                    $cardid=$walletid;
                    $amount=strval($usdvalue*100);//amount(in cents) 1usd = 100 cent
                    $orderid=$payref;

                    $postdatais=array (
                        'card_id' => $cardid,
                        'amount' =>$amount,
                        'currency' =>$currency,
                        'transaction_reference' =>$orderid,
                    );
                    $jsonpostdata=json_encode($postdatais);
                    $url ="$baseurl/cards/fund_card";
                    //  $url ="$baseurl/cards/fund_issuing_wallet";
                    $curl = curl_init();
                    curl_setopt_array(
                            $curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 60,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "PATCH",
                            CURLOPT_POSTFIELDS => trim($jsonpostdata),
                            CURLOPT_HTTPHEADER => array(
                                "token: Bearer  $authkey",
                                "content-type: application/json",
                                'accept: application/json',
                                 
                            ),
                        ));
                    $userdetails = curl_exec($curl);
                    
                    $allresp="$userdetails";
                    $paymentidisni="BC VC FUND";
                    $orderidni="$jsonpostdata";
                    $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                    $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                    $insert_data->execute();
                    $insert_data->close();
        
                    // print_r($userdetails);
                    $err = curl_error($curl);
                    // print_r($err);
                    curl_close($curl);
                    $breakdata = json_decode($userdetails);
                    if( isset($breakdata->status) && $breakdata->status== "success"){
                        $orderidis=$breakdata->data->transaction_reference;
                        $paymenttoken="  ";
                        $notyetpaid=1;
                        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE apipayref=? AND status=?  AND userid=?");
                        $checkdata->bind_param("sis",$orderidis, $notyetpaid,$userid);
                        $checkdata->execute();
                        $dresult = $checkdata->get_result(); 
                        if(empty($orderidis)) {
                                //  $success=false;
                                 $success=4;
                       } else if($dresult ->num_rows > 0){
                            //  $success=false;
                             $success=5;
                       }else{
                            // generating  token
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","FVC",true,true,false);
                            $success=1; 
                           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                           $bankpaidwith=1;
                           $systempaidwith=2;
                           $paystatus=1;
                           $status = 1;
                           $time = date("h:ia, d M");
                           $approvedby="Automation";
                           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                           $checkdata->bind_param("sssssssss",$companypayref, $paystatus,$status,$time,$userdetails,$orderidis,$paymenttoken,$approvedby,$payref);
                           $checkdata->execute();
                       }
                    }else{
                             // message
                                        $from="BC FUND CARD";
                                         $message="An error occured when trying to fund card";
                                        if(isset($breakdata->message)){
                                             $message=$breakdata->message;
                                        }
                                        $success=3;
                                       $message.=" $err $userdetails";
                                        system_notify_crash_handler($message,$from);
                    }
                }else{
                         // message
                         $success=2;
                                        $from="BC FUND CARD";
                                         $message="Insufficient fund to fund card";
                                       
                                        system_notify_crash_handler($message,$from);
                }
                
        
        return $success;
}

function getMainAccountBcBalance($currency){
           global  $connect;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $balance=0;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
        
        $url ="$baseurl/cards/get_issuing_wallet_balance";
        $curl = curl_init();
        curl_setopt_array(
                $curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "token: Bearer  $authkey",
                    "content-type: application/json",
                    'accept: application/json',
                     
                ),
            ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
          $breakdata = json_decode($userdetails);
        if(isset($breakdata->status) && $breakdata->status== "success"){
             $balance=$breakdata->data->issuing_balance_USD/100;
        }
                
                return  $balance;
}


function freezeCardbc_card($currency,$cardid,$status){
        global $connect;
        $valid=false;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency'];
        
        
         // GET CARD DETAILS API
         if($status==0){
        $url ="$relay_url/cards/freeze_card?card_id=$cardid";
         }else{
            $url ="$relay_url/cards/unfreeze_card?card_id=$cardid";  
         }
        $curl = curl_init();
        curl_setopt_array(
        $curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_HTTPHEADER => array(
            "token: Bearer  $authkey",
            "content-type: application/json",
            'accept: application/json',
        ),
        ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $breakdata = json_decode($userdetails);
        if(isset($breakdata->status) && $breakdata->status== "success"){
          $valid=true;
        }
        return $valid;
}


function fundBcCompanyWallet($walletid,$amount,$narration,$payref,$currency,$userid){
         global  $connect;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
                
                $enoughfund=true;
            
                if($enoughfund){
                      $usdvalue=$amount;
                    $cardid=$walletid;
                    $amount=strval($usdvalue*100);//amount(in cents) 1usd = 100 cent
                    $orderid=$payref;

                    $postdatais=array (
                        'card_id' => $cardid,
                        'amount' =>$amount,
                        'currency' =>$currency,
                        'transaction_reference' =>$orderid,
                    );
                    $jsonpostdata=json_encode($postdatais);
                    $url ="$baseurl/cards/unload_card";
                    //  $url ="$baseurl/cards/fund_issuing_wallet";
                    $curl = curl_init();
                    curl_setopt_array(
                            $curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 60,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "PATCH",
                            CURLOPT_POSTFIELDS => trim($jsonpostdata),
                            CURLOPT_HTTPHEADER => array(
                                "token: Bearer  $authkey",
                                "content-type: application/json",
                                'accept: application/json',
                                 
                            ),
                        ));
                    $userdetails = curl_exec($curl);
                            $allresp="$userdetails";
        $paymentidisni="BC FUND VC";
        $orderidni="BC FUND VC";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
                    // print_r($userdetails);
                    $err = curl_error($curl);
                    // print_r($err);
                    curl_close($curl);
                    $breakdata = json_decode($userdetails);
                    if(isset($breakdata->status) && $breakdata->status== "success"){
                        $orderidis=$breakdata->data->transaction_reference;
                        $paymenttoken="  ";
                        $notyetpaid=1;
                        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE apipayref=? AND status=?  AND userid=?");
                        $checkdata->bind_param("sis",$orderidis, $notyetpaid,$userid);
                        $checkdata->execute();
                        $dresult = $checkdata->get_result(); 
                        if(empty($orderidis)) {
                                 $success=false;
                       } else if($dresult ->num_rows > 0){
                             $success=false;
                       }else{
                            // generating  token
                            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","FVC",true,true,false);
                            $success=true; 
                           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                           $bankpaidwith=1;
                           $systempaidwith=2;
                           $paystatus=1;
                           $status = 1;
                           $time = date("h:ia, d M");
                           $approvedby="Automation";
                           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                           $checkdata->bind_param("sssssssss",$companypayref, $paystatus,$status,$time,$userdetails,$orderidis,$paymenttoken,$approvedby,$payref);
                           $checkdata->execute();
                       }
                    }
                }
                
        
        return $success;
}

function getLiveBCNGNtoUSDRate($currency,$fund){
               global  $connect;
        $vc_data=GetActiveBCVirtualCardApi($currency);
        $success=false;
        $authkey=$vc_data['authkey'];
        $secretekey=$vc_data['secretekey'];
        $issueid=$vc_data['issueid'];
        $baseurl=$vc_data['baseurl']; 
        $relay_url=$vc_data['relay_url'];
        $currency=$vc_data['currency']; 
                  
        $url ="$baseurl/cards/fx-rate";
        //  $url ="$baseurl/cards/fund_issuing_wallet";
        $curl = curl_init();
        curl_setopt_array(
            $curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "token: Bearer  $authkey",
                "content-type: application/json",
                'accept: application/json',
                 
            ),
        ));
        $userdetails = curl_exec($curl);
        // print_r($userdetails);
        $err = curl_error($curl);
        // print_r($err);
        curl_close($curl);
        $breakdata = json_decode($userdetails,true);
        if(isset($breakdata['status']) && $breakdata['status']== "success"){
            $rate=$breakdata['data']['NGN-USD'];
            $amount= $rate/100;
        }
                   
     return $amount;
}




// SH API CONSUMPTION
function GetActiveSHApi(){
    global $connect;
    $alldata=[];
    $active=1;
    $getdataemail =  $connect->prepare("SELECT * FROM shapidetails WHERE status=?");
    $getdataemail->bind_param("s",$active);
    $getdataemail->execute();
    $getresultemail = $getdataemail->get_result();
    if( $getresultemail->num_rows> 0){
        $getthedata= $getresultemail->fetch_assoc();
        $alldata=$getthedata;
    }
    return $alldata;
}

function getActiveSHBearerAccessToken(){
    $activeshis=GetActiveSHApi();
    $clientid= $activeshis['client_id'];
    $baseurl=$activeshis['baseurl'];
    $client_assertion=$activeshis['client_assertion'];
    
    $postdatais=array (
    'grant_type' => "client_credentials",
    'client_id' => $clientid,
    'client_assertion'=>$client_assertion,
    'client_assertion_type' =>"urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
    );
    $jsonpostdata=json_encode($postdatais);
    // print($jsonpostdata);
    $url ="$baseurl/oauth2/token";
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
            "content-type: application/json",
            'accept: application/json',
             
        ),
    ));
    $userdetails = curl_exec($curl);
    $err = curl_error($curl);
    // print_r($err);
    curl_close($curl);
    $breakdata = json_decode($userdetails);
    if($err){
        return " ";
    }else{
        return $breakdata->access_token;
    }
}

function getSHbankAccList(){
    $token=getActiveSHBearerAccessToken();
    $activeshis=GetActiveSHApi();
    $baseurl=$activeshis['baseurl'];
    $clientid= $activeshis['client_id'];
    $url ="$baseurl/transfers/banks";
    $curl = curl_init();
    curl_setopt_array(
            $curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "content-type: application/json",
                'accept: application/json',
                "ClientID: $clientid",
                 
            ),
        ));
    $userdetails = curl_exec($curl);
    print_r($userdetails);
}

function getAccountNameSH($bankcode,$accno){
    $token=getActiveSHBearerAccessToken();
    $activeshis=GetActiveSHApi();
    $baseurl=$activeshis['baseurl'];
    $clientid= $activeshis['client_id'];
    
    $postdatais=array (
    'bankCode' => "$bankcode",
    'accountNumber' => $accno
    );
    $jsonpostdata=json_encode($postdatais);
    // print($jsonpostdata);
    $url ="$baseurl/transfers/name-enquiry";
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
                "Authorization: Bearer $token",
                "content-type: application/json",
                'accept: application/json',
                "ClientID: $clientid",
                 
        ),
    ));
    $userdetails = curl_exec($curl);
    $err = curl_error($curl);
    // print_r($err);
    curl_close($curl);
    // print_r($userdetails);
    if ($err) {
        $datatosend="";
        throw new \Exception("Error getting account names: $err");
    } else {
        $responses = json_decode($userdetails);
        if (isset($responses->data->accountName)) {
            $status = $responses->statusCode;
            $acnt_name = $responses->data->accountName;

            if ($status==200) {
                $datatosend=$acnt_name;
            } else {
                $datatosend='Invalid account number';
            }
        } else {
            $datatosend='Invalid account number';
        }
    }
        return $datatosend;
}

function shgenerateAccNumber($fname,$lname,$phoneno,$email,$bvn,$userid){
    global $connect;
     $generated=false;
    $token=getActiveSHBearerAccessToken();
    $activeshis=GetActiveSHApi();
    $baseurl=$activeshis['baseurl'];
    $clientid= $activeshis['client_id'];
    
     //getting uniq acc ref no
    $permitted_chars2 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $loop = 0;
    while ($loop==0) {
        $myrefcode= generate_string($permitted_chars2, 6);
        $check =  $connect->prepare("SELECT id FROM userpersonalbnkacc WHERE accrefcode=?");
        $check->bind_param("s", $myrefcode);
        $check->execute();
        $result2 =  $check->get_result();
        if ($result2->num_rows > 0) {
            $loop = 0;
        } else {
            $loop = 1;
            break;
        }
    } 

    $postdatais=array (
    'firstName'=> $fname,//1year
    'lastName' =>$lname,
    'phoneNumber'=>$phoneno,
    'emailAddress'=>$email,
    'externalReference'=>$myrefcode,
    'bvn'=>$bvn,
    'autoSweep'=>true,
    'autoSweepDetails' => array(
        "schedule"=> "Instant",
        "accountNumber"=>"0117240133"
        )
    );
    $jsonpostdata=json_encode($postdatais);
    // print($jsonpostdata);
    $url ="$baseurl/accounts/subaccount";
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
                "Authorization: Bearer $token",
                "content-type: application/json",
                'accept: application/json',
                "ClientID: $clientid",
                 
        ),
    ));
    $userdetails = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    // print_r($err);
    // print_r($userdetails);
     $allresp="$userdetails";
          $paymentidisni="SH_GEN_ACC";
        $orderidni="$jsonpostdata";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();
        
     if ($err) {
        $datatosend="";
        throw new \Exception("Error generating account: $err");
    } else {
        $responses = json_decode($userdetails);
        if (isset($responses->data->accountName)) {
            $status = $responses->statusCode;
            $acnt_name = $responses->data->accountName;

            if ($status==200) {
                $banktypename="SafeHaven Microfinance Bank";
                $newbankaccno=$responses->data->accountNumber;
                $newreseverref=$responses->data->_id;
                $bankcode=4;
                $acctname=$responses->data->accountName;
                $expireDay="";
                $type = 4;
                $insert_data = $connect->prepare("INSERT INTO userpersonalbnkacc (userid,bankname,accno,accrefcode,accserverrefcode,banksystemtype,acctname,banktypeis,expireat) VALUES (?,?,?,?,?,?,?,?,?)");
                $insert_data->bind_param("ssssssssi", $userid, $banktypename, $newbankaccno,$myrefcode,$newreseverref,$bankcode,$acctname,$type,$expireDay);
                $insert_data->execute();
                $generated=true;
                $insert_data->close();
                $generated=true;
            }
        }
    }
   
    return $generated;          
}

function verifySHDedicatedAccpay($reference, $userid,$orderid,$doneonce=0){
    global $connect;
    $valid=false;
    $token=getActiveSHBearerAccessToken();
    $activeshis=GetActiveSHApi();
    $baseurl=$activeshis['baseurl'];
    $clientid= $activeshis['client_id'];

    $postdatais=array (
    'sessionId'=>$reference,//1year
    );
    $jsonpostdata=json_encode($postdatais);
    // print($jsonpostdata);
    $url ="$baseurl/transfers/status";
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
                "Authorization: Bearer $token",
                "content-type: application/json",
                'accept: application/json',
                "ClientID: $clientid",
                 
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $allresp="$response";
    $paymentidisni="SH VERIFY TRANS";
    $orderidni="$jsonpostdata";
    $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
    $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
    $insert_data->execute();
    $insert_data->close();
    
    $tranx = json_decode($response);
    if ($tranx->statusCode==200) {
        // check if it new and never exist, update payapiresponse,apipayref,apiorderid 
        // print_r($tranx);
        $paystackref=$tranx->data->sessionId;
        $paymenttoken=$tranx->data->paymentReference;
            //check if the transaction and the email coming and amount exist
        $notyetpaid=1;
        $checkdata =  $connect->prepare("SELECT * FROM  userwallettrans WHERE  apipayref=? AND status=?  AND userid=?");
        $checkdata->bind_param("sis",$reference, $notyetpaid,$userid);
        $checkdata->execute();
        $dresult = $checkdata->get_result(); 
       if(empty($orderid)) {
            $valid=false;
       } else if($dresult ->num_rows > 0){
            $valid=false;
       }else{
            // generating  token
            // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
            $companypayref = createUniqueToken(16,"userwallettrans","paymentref","SH",true,true,false);
           $valid=true; 
           // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
           $bankpaidwith=1;
           $systempaidwith=3;
           $paystatus=1;
           $status = 1;
           $time = date("h:ia, d M");
           $approvedby="Automation";
           $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,status=?,confirmtime=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
           $checkdata->bind_param("ssssssssss",$companypayref, $paystatus,$systempaidwith,$status,$time,$response,$paystackref,$paymenttoken,$approvedby,$orderid);
           $checkdata->execute();
       }
    }
    else if($tranx->statusCode==403 && $doneonce==0){
        // incase token expires
        $doneonce=$doneonce+1;
        verifySHDedicatedAccpay($reference, $userid,$orderid,1);
    }
    return $valid;
}
?>