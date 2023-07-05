<?php
    // syste, function
    
    function billgetAllSystemSetting(){
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
    function GetActive1APPApi(){
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

      //  1app
     function buy1appAirtime($amount, $networkid, $order_id, $phone){
           global $connect;
                $bought=false;
                $platform = GetActive1APPApi();
                $secret = $platform['secretekey'];
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.oneappgo.com/v1/airtime',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('phoneno' => $phone,'network_id' => $networkid,'reference' => $order_id,'amount' => $amount),
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: Bearer $secret"
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                
                $allresp="$response";
                $paymentidisni="1APP AIRTIME";
                $orderidni="$order_id";
                $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
                $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
                $insert_data->execute();
                $insert_data->close();
                
               
        
                $response = json_decode($response);
                if ( isset($response->status) && $response->status==true ){
                    $bought=true;
                    $paystackref=$response->txref;
                    // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                    $companypayref = createUniqueToken(16,"userwallettrans","paymentref","AIRONEA",true,true,false);
                    // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                    $bankpaidwith=1;
                    $systempaidwith=3;
                    $paystatus=1;
                    $status = 1;
                    $time = date("h:ia, d M");
                    $approvedby="Automation";
                    $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                    $checkdata->bind_param("ssssssss",$companypayref, $paystatus,$systempaidwith,$allresp,$paystackref,$paystackref,$approvedby,$order_id);
                    $checkdata->execute();
                }
    
                return $bought;
    
    }
     function buyDataWith1app($datacode,$networkid, $phoneno, $reference){
            global $connect;
            $bought=false;
            $platform = GetActive1APPApi();
            $secret = $platform['secretekey'];
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.oneappgo.com/v1/databundle',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('datacode' => $datacode ,'network_id' =>$networkid,'phoneno' => $phoneno,'reference' => $reference),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $secret"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $allresp="$response";
            $paymentidisni="1APP DATA";
            $orderidni="$reference";
            $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
            $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
            $insert_data->execute();
            $insert_data->close();
    
            $response = json_decode($response);
            if ( isset($response->status) && $response->status==true ){
                $bought=true;
                
                $paystackref=$response->txref;
                // $length,$tablename,$tablecolname,$tokentag,$addnumbers,$addcapitalletters,$addsmalllletters
                $companypayref = createUniqueToken(16,"userwallettrans","paymentref","DATAONEA",true,true,false);
                $valid=true; 
                // $syspaytype=1; // systemtype 1 paystack,2 monify 3 1app
                $bankpaidwith=1;
                $systempaidwith=3;
                $paystatus=1;
                $status = 1;
                $time = date("h:ia, d M");
                $approvedby="Automation";
                $checkdata = $connect->prepare("UPDATE userwallettrans SET paymentref=?,paymentstatus=?,systempaidwith=?,payapiresponse=?,apipayref=?,apiorderid=?,approvedby=?  WHERE orderid=?");
                $checkdata->bind_param("ssssssss",$companypayref, $paystatus,$systempaidwith,$allresp,$paystackref,$paystackref,$approvedby,$reference);
                $checkdata->execute();
            }
            return $bought;
    }
     function getAll1AppDataPlans($network){
            $curl = curl_init();
    
            $oneapp_data = GetActive1APPApi();
    
            if ( $oneapp_data ){
                $public_key = $oneapp_data['key']; //pb -publickey
            }
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.oneappgo.com/v1/getdataplans?provider=$network",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $public_key"
            ),
            ));
    
            $response = curl_exec($curl);
            $response = json_decode($response);
            if($response->status){
                return $response->data;
            }
            return false;
    
            curl_close($curl);
            
        }

   
    // buy data plan vtpass
    function buyDataPlanVtpass($variation_code, $provider ,$phone, $order_id){
        $bought = false;

        global $connect;
        $activeVtpass= GetActiveVtPassApi();
        if(!$activeVtpass){
           return $bought; 
        }


        // generate vtpass id
        $current_date = str_replace(":", "", (string) date("Y-m-d H:i:s", time()) );
        $current_date = str_replace("-", "", $current_date );
        $current_date = str_replace(" ", "", $current_date );
        $transa_id = $current_date . $order_id;

        // update transaction vtpass_id column you can change the query details with the table values
        // $query = "UPDATE transactions SET vtpass_id = ? WHERE order_id = ?";
        // $updateVtpassIdStmt = $connect->prepare($query);
        // $updateVtpassIdStmt->bind_param("ss", $transa_id , $order_id);
        // $updateVtpassIdStmt->execute();

        $api_key = $activeVtpass['apikey'];
        $secret_key = $activeVtpass['secretekey'];
        $vtpass_url = $activeVtpass['baseurl'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $vtpass_url.'pay',
        // CURLOPT_URL => 'https://sandbox.vtpass.com/api/pay',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "request_id=$transa_id&serviceID=$provider&billersCode=$phone&variation_code=$variation_code&phone=$phone",
        CURLOPT_HTTPHEADER => array(
            "api-key: $api_key",
            "secret-key: $secret_key",
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: laravel_session=eyJpdiI6IkpcL3RFbFNyWkF0NEdGdnFFNDNqYmhBPT0iLCJ2YWx1ZSI6IkNwakhSK21lZXl6emZNYlR1VTBrN0htR3J6V2JFa3JGNHd3cG55ZGs3d0s3TndPNlYzVVd1U2oxSFoyd1lGZDVrdVdiT05wS1hcL094ZHErV3FFWWg0dz09IiwibWFjIjoiMTJlYzk4ZGE5OWI0MTg5MzhmZjdmOGY4ZjE4ZDNkZmJmYzJmZTM2ZmMwMTg5YTE5MjZiMzA2YTE4ZTM1ZDQ3MSJ9'
        ),
        ));

        $result = curl_exec($curl);
        // echo $result;
        curl_close($curl);

        $response = json_decode($result);

        if ( isset($response->code) ){
            if( $response->code && $response->content->transactions->status == 'delivered' ){
                $bought = true; 
            }
        }

        return $bought;

    }
    // Vtpass provider value
    // Airtime = mtn => mtn, glo => glo, airtel => airtel, 9mobile  => etisalat   
    // Data = mtn => mtn-data, glo => glo-data, airtel => airtel-data, 9mobile  => etisalat-data   

    
    //safeHaven
    function buyAirtimeSH($amount, $networkid, $order_id, $phone){
        global $connect;
        //Airtime_id=61efaba1da92348f9dde5f6c
        // MTN= 61efacbcda92348f9dde5f92 GLO=61efacc8da92348f9dde5f95 Airtel=61efacd3da92348f9dde5f98  ETISALAT = 61efacdeda92348f9dde5f9b

        $token=getActiveSHBearerAccessToken();
        $activeshis=GetActiveSHApi();
        $baseurl=$activeshis['baseurl'];
        $clientid= $activeshis['client_id'];
        $debitAccountNumber = $activeshis['debit_account_number'];
        $url ="$baseurl/vas/pay/airtime";
        $serviceCategoryId = $networkid;

        // $amount = number_format($amount,2);
        echo $amount;
        $amount = (float) $amount;
        
        //data to send to endpoint
        $postdatais=array (
            'amount' => $amount,
            'channel' => 'WEB',
            'serviceCategoryId' => "$serviceCategoryId",
            'phoneNumber' => $phone,
            'debitAccountNumber' => "$debitAccountNumber",
        );

        
        $jsonpostdata=json_encode($postdatais);

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
        $response = json_decode($response);
        $allresp="$response";
        $paymentidisni="SH AIRTIME";
        $orderidni="$order_id";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();

        $response = json_decode($response);
        if ( isset($response->status) && $response->status==true ){
            $bought=true;
        }

        return $bought;
    }
    function buyDataeSH($datacode,$networkid, $phoneno, $reference, $amount){
        global $connect;
        //Data=61efabb2da92348f9dde5f6e
        // MTN= 61efacfada92348f9dde5f9e GLO=61efad06da92348f9dde5fa1 Airtel=61efad12da92348f9dde5fa4  ETISALAT = 61efad1dda92348f9dde5fa7

        $token=getActiveSHBearerAccessToken();
        $activeshis=GetActiveSHApi();
        $baseurl=$activeshis['baseurl'];
        $clientid= $activeshis['client_id'];
        $debitAccountNumber = $activeshis['debit_account_number'];
        $url ="$baseurl/vas/pay/data";
        $serviceCategoryId = $networkid;
        $amount = getColumnFromField('bill_data_provider', 'pro_price', 'sh_network_netid', $datacode);

        //data to send to endpoint
        $postdatais=array (
            'amount' => "$amount",
            'channel' => 'WEB',
            'serviceCategoryId' => "$serviceCategoryId",
            "bundleCode"=> "$datacode",
            'phoneNumber' => $phoneno,
            'debitAccountNumber' => "$debitAccountNumber",
        );
        $jsonpostdata=json_encode($postdatais);
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
        curl_close($curl);

        // success response
        // "statusCode": 200,
        // "data": {
        //     "clientId": "63d26175b6a218001ec1247f",
        //     "serviceCategoryId": "61efacfada92348f9dde5f9e",
        //     "reference": "994a3d0b9d57484cb18147f819a8e4a7",
        //     "status": "processing",
        //     "amount": 300,
        //     "id": "6478d1fd1e2ae81a6ab5d5a0"
        // },
        // "message": "Data Bundle purchased successfully."
        
        $allresp="$response";
        $paymentidisni="SH DATA";
        $orderidni="$reference";
        $insert_data = $connect->prepare("INSERT INTO jsonresponsefromcallback (orderid,payid,jsonresp) VALUES (?,?,?)");
        $insert_data->bind_param("sss", $orderidni, $paymentidisni, $allresp);
        $insert_data->execute();
        $insert_data->close();

        $response = json_decode($response);
        if ( isset($response->status) && $response->status==true ){
            $bought=true;
        }

        return $bought;
    }
    function getSHDataPlans($serviceid){
        // service id of each network provider MTN= 61efacfada92348f9dde5f9e GLO=61efad06da92348f9dde5fa1 Airtel=61efad12da92348f9dde5fa4  ETISALAT = 61efad1dda92348f9dde5fa7
        $token=getActiveSHBearerAccessToken();
        $activeshis=GetActiveSHApi();
        $baseurl=$activeshis['baseurl'];
        $clientid= $activeshis['client_id'];
        $url ="$baseurl/vas/service-category/$serviceid/products";
        // /vas/service/mnmrrrr/service-categories
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
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        if ( isset($response->statusCode) && $response->statusCode==200 ){
            // print_r($response->data);
            return $response->data;
        }
        return false;
    }

     //club Konnect
     function GetActiveClubConnect(){
        global $connect;
        $alldata=[];
        $active=1;
        $getdataemail =  $connect->prepare("SELECT * FROM clubkonnectdetails WHERE status=?");
        // $getdataemail->bind_param("s",$active);
        $getdataemail->execute();
        $getresultemail = $getdataemail->get_result();
        if( $getresultemail->num_rows> 0){
            $getthedata= $getresultemail->fetch_assoc();
            $alldata=$getthedata;
        }
        return $alldata;
    }
    function getClubKonnectAirtime($amount, $networkid, $order_id, $phone){
        // On Club Konnect,the minimun amount of airtime is 100 and the maximum amount is  50,000
        if ($amount < 100){
            return false;
        }elseif ($amount > 50000){
            return false;
        }

        // get the user_id and apikey from the database
        $activeClubConnect= GetActiveClubConnect();
        if ( !$activeClubConnect ){
            return false;
        }
        $activeClubUserId = $activeClubConnect['user_id'];
        $activeClubConnectApiKey = $activeClubConnect['apikey'];

        $curl = curl_init();
        // 01 for MTN, 02 for Glo, 04 for Airtel , 03 for 9mobile
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.nellobytesystems.com/APIAirtimeV1.asp?UserID=$activeClubUserId&APIKey=$activeClubConnectApiKey&MobileNetwork=$networkid&Amount=$amount&MobileNumber=$phone&RequestID=$order_id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: ASPSESSIONIDAWSRDTDA=IIFAMFMDMAGKKAEBCJFFPBLF'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        
        if($response ->stauts){
            return $response;
        }
        return false;

        curl_close($curl);

    };
    function getAllClubkonnectDataPlans($provider){
        //fetch network provider from db
        $network_providers = ["MTN", "Glo", "9mobile", "Airtel"];
        if(!in_array($provider, $network_providers)){
            return false;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.nellobytesystems.com/APIDatabundlePlansV1.asp',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: ASPSESSIONIDQUABBTDA=GEKEKMPAHAKJJOJELAKLKKGI'
        ),
        ));

        $result = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($result);


        if($response->response_description == "000"){
            return $response->content->varations;
        }
        return false;
    }
    
    
    function buyUserAirtime($amount, $networkid, $order_id,$phone){// send to is phone number, smsto send (call the function in the smstemplate)
        // 1 1app, 2 klub 3 SH, 4 vtpass
        $smssent=false;
        $activemailsystem=billgetAllSystemSetting()['activebillsystem'];
        if($activemailsystem==1){
           $smssent= buy1appAirtime($amount, $networkid, $order_id, $phone);
        }else if($activemailsystem==2){
        }else if($activemailsystem==3){
        }else if($activemailsystem==4){
        }
        return $smssent;
    }
    function buyUserData($datacode,$networkid, $phoneno, $reference){// send to is phone number, smsto send (call the function in the smstemplate)
        // 1 1app, 2 klub 3 SH, 4 vtpass
        $smssent=false;
        $activemailsystem=billgetAllSystemSetting()['activebillsystem'];
        if($activemailsystem==1){
           $smssent= buyDataWith1app($datacode,$networkid, $phoneno, $reference);
        }else if($activemailsystem==2){
        }else if($activemailsystem==3){
            $smssent= buyDataeSH($datacode,$networkid, $phoneno, $reference,'');
        }else if($activemailsystem==4){
        }
        return $smssent;
    }

  
?>