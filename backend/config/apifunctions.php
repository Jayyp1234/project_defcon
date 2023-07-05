<?php
include("Firebase/src/JWT.php");
use \Firebase\JWT\JWT; 
// activate this to prevnt errors from breaking the API , but remove it when debugging
// error_reporting(E_ERROR | E_PARSE);
// LATER UPGRADEs
/*
***Convert ALL API to class
*** Add all link to documentation
*** add more hint
Status Code 201 – This is the status code that confirms that the request was successful and, as a result, a new resource was created. 
Typically, this is the status code that is sent after a POST/PUT request.
100	Continue	[RFC7231, Section 6.2.1]
101	Switching Protocols	[RFC7231, Section 6.2.2]
102	Processing	[RFC2518]
103	Early Hints	[RFC8297]
104-199	Unassigned	
200	OK	[RFC7231, Section 6.3.1]
201	Created	[RFC7231, Section 6.3.2]
202	Accepted	[RFC7231, Section 6.3.3]
203	Non-Authoritative Information	[RFC7231, Section 6.3.4]
204	No Content	[RFC7231, Section 6.3.5]
205	Reset Content	[RFC7231, Section 6.3.6]
206	Partial Content	[RFC7233, Section 4.1]
207	Multi-Status	[RFC4918]
208	Already Reported	[RFC5842]
209-225	Unassigned	
226	IM Used	[RFC3229]
227-299	Unassigned	
300	Multiple Choices	[RFC7231, Section 6.4.1]
301	Moved Permanently	[RFC7231, Section 6.4.2]
302	Found	[RFC7231, Section 6.4.3]
303	See Other	[RFC7231, Section 6.4.4]
304	Not Modified	[RFC7232, Section 4.1]
305	Use Proxy	[RFC7231, Section 6.4.5]
306	(Unused)	[RFC7231, Section 6.4.6]
307	Temporary Redirect	[RFC7231, Section 6.4.7]
308	Permanent Redirect	[RFC7538]
309-399	Unassigned	
400	Bad Request	[RFC7231, Section 6.5.1]
401	Unauthorized	[RFC7235, Section 3.1]
402	Payment Required	[RFC7231, Section 6.5.2]
403	Forbidden	[RFC7231, Section 6.5.3]
404	Not Found	[RFC7231, Section 6.5.4]
405	Method Not Allowed	[RFC7231, Section 6.5.5]
406	Not Acceptable	[RFC7231, Section 6.5.6]
407	Proxy Authentication Required	[RFC7235, Section 3.2]
408	Request Timeout	[RFC7231, Section 6.5.7]
409	Conflict	[RFC7231, Section 6.5.8]
410	Gone	[RFC7231, Section 6.5.9]
411	Length Required	[RFC7231, Section 6.5.10]
412	Precondition Failed	[RFC7232, Section 4.2][RFC8144, Section 3.2]
413	Payload Too Large	[RFC7231, Section 6.5.11]
414	URI Too Long	[RFC7231, Section 6.5.12]
415	Unsupported Media Type	[RFC7231, Section 6.5.13][RFC7694, Section 3]
416	Range Not Satisfiable	[RFC7233, Section 4.4]
417	Expectation Failed	[RFC7231, Section 6.5.14]
418-420	Unassigned	
421	Misdirected Request	[RFC7540, Section 9.1.2]
422	Unprocessable Entity	[RFC4918]
423	Locked	[RFC4918]
424	Failed Dependency	[RFC4918]
425	Too Early	[RFC8470]
426	Upgrade Required	[RFC7231, Section 6.5.15]
427	Unassigned	
428	Precondition Required	[RFC6585]
429	Too Many Requests	[RFC6585]
430	Unassigned	
431	Request Header Fields Too Large	[RFC6585]
432-450	Unassigned	
451	Unavailable For Legal Reasons	[RFC7725]
452-499	Unassigned	
500	Internal Server Error	[RFC7231, Section 6.6.1]
501	Not Implemented	[RFC7231, Section 6.6.2]
502	Bad Gateway	[RFC7231, Section 6.6.3]
503	Service Unavailable	[RFC7231, Section 6.6.4]
504	Gateway Timeout	[RFC7231, Section 6.6.5]
505	HTTP Version Not Supported	[RFC7231, Section 6.6.6]
506	Variant Also Negotiates	[RFC2295]
507	Insufficient Storage	[RFC4918]
508	Loop Detected	[RFC5842]
509	Unassigned	
510	Not Extended	[RFC2774]
511	Network Authentication Required	[RFC6585]
512-599	Unassigned	


SAVERTECH CODE STRUCTURE
Seprate class file for Mail/sms to send
Sperate class file for payment functions
Seprate class file for error/error code to display to user
Seprate class file for constant data
Seprate class file for conection to db
Seprate class file for db calls
Seprate class file for utility functions
Single function to show error in UI

Error code that starts with 1 is from us,2 is from third party

FROM WHAT SYSTEM____FROM WHERE__ERRORTYPE

FROM WHAT SYSTEM
internal(our code) -1
external(Third party API) -2

FROM WHERE INTERNAL
database insert error-->1
databse update error-->2
database delete error-->3
user wrong action error ---> 4 (insufficient fund, empty data,authorization)
Hacker attempt--->5 (wrong method/user not found)

FROM WHERE EXTERNAL
Call to API failed -->6
Sent wrong data to API->7
Failed to satisfy API need on their dashboard ->8(Insufficinet fund)

ERRORTYPE
1--Fatal
2--Warning


*/
//  ALL RESPONSE CODE
function respondOK($data){
    header('HTTP/1.1 200 OK');
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondNotCompleted($data){
    header('HTTP/1.1 202 OK');
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        // 202 Accepted Indicates that the request has been received but not completed yet.
    exit;
}
function respondURLChanged($data){
    header('HTTP/1.1 302 URL changed');
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
       // The URL of the requested resource has been changed temporarily
    exit;
}
function respondNotFound($data){
    header('HTTP/1.1 404 Not found');
      //  Not found
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondForbiddenAuthorized($data){
    header("HTTP/1.1 403 Forbidden");
        // 403 Forbidden
    // Unauthorized request. The client does not have access rights to the content. Unlike 401, the client’s identity is known to the server.
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondUnAuthorized($data){
    header("HTTP/1.1 401 Unauthorized");
     // the client’s identity is known to the server.
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondInternalError($data){
    header("HTTP/1.1 500 Internal Server Error");
        //  internal server error
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondBadRequest($data){
    header("HTTP/1.1 400 Bad request");
        // 400 Bad Request
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
function respondMethodNotAlowed($data){
    header("HTTP/1.1 405 Method Not allowed");
        // 405 Method Not Allowed
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    exit;
}
// ALL RESPONSE CODE
// ALL RESPONSE ERROR
function returnError7001($errordesc,$linktosolve,$hint){
    $data = ["code"=>7001,"text"=>$errordesc,"link"=>"$linktosolve","hint"=>$hint];
     // bad request
    return $data;
}
function returnError7002($errordesc,$linktosolve,$hint){
    $data = ["code"=>7002,"text"=>$errordesc,"link"=>"$linktosolve","hint"=>$hint];
    // Unauthorized
    return $data;
}
function returnError7003($errordesc,$linktosolve,$hint){
    $data = ["code"=>7003,"text"=>$errordesc,"link"=>"$linktosolve","hint"=>$hint];
    // Method Not allowed
    return $data;
}
// ALL ERROR RESPONSE
// RETURN ERROR
function returnErrorArray($text,$method,$endpoint,$errordata,$maindata=[]){
    $text = empty($text) ? '': $text;
    $data = ["status"=>false,"text" => $text,"data" => $maindata, "time" => date("d-m-y H:i:sA",time()), "method" => $method, "endpoint" => $endpoint,"error"=>$errordata];
    return $data;
}
//  RETURN DATA 
function returnSuccessArray($text,$method,$endpoint,$errordata,$data,$status){
    $data = ["status"=>$status,"text" => $text,"data" => $data, "time" => date("d-m-y H:i:sA",time()), "method" => $method, "endpoint" => $endpoint,"error"=>$errordata];
    return $data;
}
// Generated a unique pub key for all users
// generate Unique prive key for company from admin panel
// set Server name on admin $serverName
function getTokenToSendAPI($userPubkey,$companyprivateKey,$minutetoend,$serverName){
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify("+$minutetoend minutes")->getTimestamp();  
    $username   = "$userPubkey";
    $data = [
        'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
        'iss'  => $serverName,                       // Issuer
        'nbf'  => $issuedAt->getTimestamp(),         // Not before
        'exp'  => $expire,                           // Expire
        'usertoken' => $username,                     // User name
    ];

    // Encode the array to a JWT string.
    //  get token below
    $auttokn= JWT::encode(
        $data,
        $companyprivateKey,
        'HS512'
    );
    return $auttokn;
}
function prevent_multiple_api_call($usertoken,$fullurl,$method){
                global $connect;
                $endpoint=$fullurl;
                $query = 'INSERT INTO apicalllog (user_id,apilink,apimethod) Values (?, ?, ?)';
                $stmt = $connect->prepare($query);
                $stmt->bind_param("sss",$usertoken ,$fullurl,$method);
                $stmt->execute();
                
                // block multiple call at same time, allow some not in same millisec
                $likeits="%{$fullurl}%";
                $sysgetdata =  $connect->prepare("SELECT COUNT(*) AS totalcall FROM apicalllog WHERE (apilink=? OR  apilink LIKE ?) AND user_id=?  GROUP BY created_at,apilink ORDER BY `totalcall`,created_at DESC LIMIT 1");
                $sysgetdata->bind_param("sss", $fullurl,$fullurl,$usertoken);
                $sysgetdata->execute();
                $dsysresult7 = $sysgetdata->get_result();
                $getsys2 = $dsysresult7->num_rows;
                if($getsys2 > 0){
                    $getuserdata= $dsysresult7->fetch_assoc();
                    $totalcall=$getuserdata['totalcall'];
                    if($totalcall>=2){
                        // cancle trans
                        $errordesc="Kindly wait for the ongoing process to complete before attempting it again. You can try again in a couple of minutes.";
                        $linktosolve="htps://";
                        $hint=["It is not allowed to call the same API multiple times within a short time frame..","Follow the format stated in the documentation","All letters in upper case must be in upper case","Ensure the correct method is used"];
                        $errordata=returnError7001($errordesc,$linktosolve,$hint);
                        $text="Kindly wait for the ongoing process to complete before attempting it again. You can try again in a couple of minutes.";
                        $data=returnErrorArray($text,$method,$endpoint,$errordata);
                        respondNotCompleted($data);
                        exit;
                    }
                }
                
                $totalBtw=100000;//long seconds
                // API urls that users should rest 5 seconds before recalling
                $specialAPis=array("http://app.cardify.co:80/api/user/auth/register.php","http://app.cardify.co:80/api/user/exchange/generate_exchange_address.php","http://app.cardify.co:80/api/user/kyc/verify_bvn.php","http://app.cardify.co:80/api/user/swap/swap_user_coin.php"
                ,"http://app.cardify.co:80/api/user/exchange/exchange_external.php","http://app.cardify.co:80/api/user/systems/addUserBanks.php","http://app.cardify.co:80/api/user/systems/verifyBanks.php","http://app.cardify.co:80/api/user/systems/redeemcode.php","http://app.cardify.co:80/api/user/transaction/addTransaction.php"
                ,"http://app.cardify.co:80/api/user/transaction/generatecryptoAddress.php","http://app.cardify.co:80/api/user/transaction/getpersonalbankaccount.php","http://app.cardify.co:80/api/user/transaction/initiateDeposit.php",
                "http://app.cardify.co:80/api/user/transaction/sendswapcrypto.php","http://app.cardify.co:80/api/user/transaction/verifyPayments.php","http://app.cardify.co:80/api/user/virtual_card/create_card.php","http://app.cardify.co:80/api/user/virtual_card/fund_vc.php",
                "http://app.cardify.co:80/api/user/virtual_card/unload_vc.php","http://app.cardify.co:80/api/user/wallet/generate_user_subwallet.php","http://app.cardify.co:80/api/user/bills/topup/buy_top_up_product.php","http://app.cardify.co:80/api/user/bills/voucher/buy_voucher_product.php");
                
                if(in_array($fullurl,$specialAPis)){
                    // block callss less than 20 seconds for same apiink
                    $sysgetdata =  $connect->prepare("SELECT created_at,apilink,id FROM apicalllog WHERE (apilink=? OR  apilink LIKE ?)  AND user_id=? ORDER BY `id` DESC LIMIT 2");
                    $sysgetdata->bind_param("sss", $fullurl,$fullurl,$usertoken);
                    $sysgetdata->execute();
                    $dsysresult7 = $sysgetdata->get_result();
                    $getsys2 = $dsysresult7->num_rows;
                    if($getsys2>=2){
                        $i=0;
                        $firstno=0;
                        $secno=0;
                        while($getuserdata= $dsysresult7->fetch_assoc()){
                            $totalcall=strtotime($getuserdata['created_at']);
                            if($i==0){
                                $firstno=$totalcall;
                            }else{
                              $secno=$totalcall;
                              break;
                            }
                            $i++;
                        }
                        
                        $totalBtw=$firstno-$secno;
                        // print("DIfference: $totalBtw");
                        // if seconds between thw call is less than 20 seconds cancle it due to constant notification checker APi
                        if($totalBtw<4){
                            // cancle trans
                            $errordesc="Please allow the current process to finish before trying again. You can try again in a few minutes.";
                            $linktosolve="htps://";
                            $hint=["It is not allowed to call the same API multiple times within a shorter time frame.","Follow the format stated in the documentation","All letters in upper case must be in upper case","Ensure the correct method is used"];
                            $errordata=returnError7001($errordesc,$linktosolve,$hint);
                            $text="Please allow the current process to finish before trying again. You can try again in a few minutes.";
                            $data=returnErrorArray($text,$method,$endpoint,$errordata);
                            respondNotCompleted($data);
                            exit;
                        }
                    }
                }
                 
                
                // delete user last 30 calls that has passed 10min
                $last30=70;
                $howmanyminutepass=4;
                $query = 'DELETE FROM apicalllog WHERE TIMESTAMPDIFF(MINUTE, created_at, NOW()) >= ? ORDER BY id ASC LIMIT ? ';
                $stmt = $connect->prepare($query);
                $stmt->bind_param("ss", $howmanyminutepass,$last30);
                $stmt->execute();
                
}
function ValidateAPITokenSentIN($serverName,$companyprivateKey,$method,$endpoint){
        global $connect;
    
        $method = getenv('REQUEST_METHOD');
        $fullurl=getCurrentFullURL();

    
        $headerName = 'Authorization';
        $headers = getallheaders();
        $signraturHeader = isset($headers[$headerName]) ? $headers[$headerName] : null;
        if($signraturHeader==null){
            $signraturHeader= isset($_SERVER['Authorization'])?$_SERVER['Authorization']:"";
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $signraturHeader = trim($_SERVER["HTTP_AUTHORIZATION"]);
    }
    try{
        if (! preg_match('/Bearer\s(\S+)/',$signraturHeader, $matches)) {
            $errordesc="The format sent in does not match the correct format for the API";
            $linktosolve="htps://";
            $hint=["Check if all header values are sent correctly.","Follow the format stated in the documentation","All letters in upper case must be in upper case","Ensure the correct method is used"];
            $errordata=returnError7001($errordesc,$linktosolve,$hint);
            $text="Bad request";
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondUnAuthorized($data);
            exit;
        }

        $jwt = $matches[1];

        if (! $jwt) {
                // No token was able to be extracted from the authorization header
                $errordesc="The format sent in does not match the correct format for the API";
                $linktosolve="htps://";
                $hint=["Check if all header values are sent correctly.","Follow the format stated in the documentation","All letters in upper case must be in upper case","Ensure the correct method is used"];
                $errordata=returnError7001($errordesc,$linktosolve,$hint);
                $text="Bad request";
                $data=returnErrorArray($text,$method,$endpoint,$errordata);
                respondUnAuthorized($data);
                exit;
        }
        $secretKey  = $companyprivateKey;
        $token = JWT::decode($jwt, $secretKey, ['HS512']);
        $now = new DateTimeImmutable();

        if ($token->iss !== $serverName || $token->nbf > $now->getTimestamp() || $token->exp < $now->getTimestamp() || empty($token->usertoken)) {
            $errordesc="Uauthorized";
            $linktosolve="htps://";
            $hint=["Check if all header values are sent correctly.","Ensure token has not expired","Regenerate token","Ensure the correct method is used","Token is case sensitve"];
            $errordata=returnError7001($errordesc,$linktosolve,$hint);
            $text="Unauthorized";
            $data=returnErrorArray($text,$method,$endpoint,$errordata);
            respondUnAuthorized($data);
            exit;
        }
        
        $usertoken= $token->usertoken;
        // if($usertoken=="CardifycBZdgHl6LFMS3SzuWU1kZ7kNGEgHA"){
        prevent_multiple_api_call($usertoken,$fullurl,$method);
        // }
        
        return $token;
    }
    //catch exception
    catch(Exception $e) {
    // echo 'Message: '.$e->getMessage();
     // No token was able to be extracted from the authorization header
     $errordesc="The format sent in does not match the correct format for the API";
     $linktosolve="htps://";
     $hint=["Check if all header values are sent correctly.","Ensure token has not expired","Regenerate token","Follow the format stated in the documentation","All letters in upper case must be in upper case","Ensure the correct method is used"];
     $errordata=returnError7001($errordesc,$linktosolve,$hint);
     $text="Bad request";
     $data=returnErrorArray($text,$method,$endpoint,$errordata);
     respondUnAuthorized($data);
     exit;
  }
}
?>