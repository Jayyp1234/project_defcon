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
    
        if (isset($_GET['name'])){
            $name = cleanme($_GET['name'],1);
        }
        else{
            
        }
        $sqlQuery = "SELECT * FROM `academy` LEFT JOIN authors ON academy.authorid = authors.id WHERE authors.type = 2 AND academy.headline = '$name'";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                $data = explode(',', $users['tag']);
                array_push($allResponse,json_decode(json_encode($users), true));
            }
            $maindata['userdata']= $allResponse[0];
            $maindata['tags']= $data;
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
        else {
            $errordesc = "No News Content Available";
            $linktosolve = "https://";
            $hint = ["Ensure to use the method stated in the documentation."];
            $errordata = returnError7003($errordesc, $linktosolve, $hint);
            $text = "Method used not allowed";
            $method = getenv('REQUEST_METHOD');
            $data = returnErrorArray($text, $method, $endpoint, $errordata);
            respondMethodNotAlowed($data);
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