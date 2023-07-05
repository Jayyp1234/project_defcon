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
    
    
//Return Invidual Blog By Category Segment
function academy($category_id){
    $newArray = [];
    global $connect;
        $sqlQuery = "SELECT * FROM `academy` WHERE category_id = ? ORDER BY academy.id DESC LIMIT 10";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->bind_param("s",$category_id);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            while($users = $result->fetch_assoc()){
                $data = array(
                            'id'=> $users['id'],
                            'headline' => $users['headline'],
                            'header'=> $users['header'],
                            'header_type' => $users['header_type'],
                            'text'=> $users['text'],
                            'category_id' => $users['category_id'], 
                            'readmin'=> $users['readmin'],
                            'authorid' => $users['authorid'],
                            'tag' => $users['tag'],
                            'date_created' => $users['date_created'],
                            'date_updated'=> $users['date_updated']
                              );
                array_push($newArray,json_decode(json_encode($data), true));
            }
        }
    $stmt->close();
    return $newArray;
}

if ($method == 'GET') {
        $sqlQuery = "SELECT * FROM `academy_category` WHERE status = 1";
        $stmt= $connect->prepare($sqlQuery);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
            while($users = $result->fetch_assoc()){
                array_push($allResponse,json_decode(json_encode( 
                        array('id'=> $users['id'],
                              'title'=> $users['title'],
                              'intro'=> $users['intro'],
                              'academy'=> academy($users['id']) 
                              )
                          ),true));
            }
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