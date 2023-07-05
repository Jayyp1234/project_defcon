<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");// OPTIONS,GET,POST,PUT,DELETE
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    
    
    include "../../../config/utilities.php";
    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);


if ($method == 'GET') {
    if (isset($_GET['filter'])){
        $filter = cleanme($_GET['filter']);
        if ($filter == null || $filter == 'all' || $filter == 'null'){
            $allResponse = array('Wallets'=> [],'Store'=> [],'Exchange'=> [],'Cards'=> []);
            $category_id = 3; //How To's Category
            $sqlQuery = "SELECT * FROM `academy` WHERE category_id = ? ORDER BY academy.id DESC LIMIT 10";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("s",$category_id);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){
                while($users = $result->fetch_assoc()){ 
                    if ($users['type'] == 'Wallets'){
                        array_push( $allResponse['Wallets'],json_decode(json_encode($users), true));
                    }
                    if ($users['type'] == 'Store'){
                        array_push( $allResponse['Store'],json_decode(json_encode($users), true));
                    }
                    if ($users['type'] == 'Exchange'){
                        array_push( $allResponse['Exchange'],json_decode(json_encode($users), true));
                    }
                    if ($users['type'] == 'Cards'){
                        array_push( $allResponse['Cards'],json_decode(json_encode($users), true));
                    }
                }
                $stmt->close();
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
        else{
            $allResponse = array($filter=> []);
            $category_id = 3; //How To's Category
            $sqlQuery = "SELECT * FROM `academy` WHERE category_id = ? ORDER BY academy.id DESC LIMIT 10";
            $stmt= $connect->prepare($sqlQuery);
            $stmt->bind_param("s",$category_id);
            $stmt->execute();
            $result= $stmt->get_result();
            $numRow = $result->num_rows;
            if($numRow > 0){
                while($users = $result->fetch_assoc()){ 
                    if ($users['type'] == $filter){
                        array_push( $allResponse[$filter],json_decode(json_encode($users), true));
                    }
                }
                $stmt->close();
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
    }
    else{
        
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