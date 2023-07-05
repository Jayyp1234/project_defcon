<?php
    // pass cors header to allow from cross-origin
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");// OPTIONS,GET,POST,PUT,DELETE
    // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: no-cache");
    
    
    include "../../../config/utilities.php";
    $method = getenv('REQUEST_METHOD');
    $endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);
if ($method == 'GET') {
//   function showpost($text){
//     $text = str_replace("\\r\\n", "", $text);
//     $text = trim(preg_replace('/\t+/', '', $text));
//     $text = htmlspecialchars_decode($text, ENT_QUOTES);
//     $text = nl2br($text);
//     return $text;
// }       
         if (isset($_GET['name'])){
            $name = cleanme($_GET['name'],1);
        }
        else{
            
        }
        $sqlQuery = "SELECT `name`, `intro`,`dateadded`,`image`, `blogimage`, `blogheadline`, `howmanyminread`, `blogcontent`, `category`, `tags`, `created_at`, `updated_at`, `draft` FROM `blog` LEFT JOIN authors ON blog.authorid = authors.id WHERE authors.type = 1 AND blog.blogheadline LIKE '%$name%' ";
    
        $stmt= $connect->prepare($sqlQuery);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;
        if($numRow > 0){
            $allResponse = [];
           
            while($users = $result->fetch_assoc()){
                array_push($allResponse,json_decode(json_encode(array(
                              'name'=> $users['name'],
                              'intro'=> $users['intro'],
                              'dateadded'=> $users['dateadded'] ,
                              'image'=> $users['image'],
                              'blogimage'=> $users['blogimage'],
                              'blogheadline'=> showpost($users['blogheadline']),
                              'howmanyminread'=> $users['howmanyminread'],
                              'blogcontent'=> showpost($users['blogcontent']),
                              'category'=> $users['category'] ,
                              'tags'=> $users['tags'],
                              'created_at'=> $users['created_at'],
                              'updated_at'=> $users['updated_at'] ,
                              )), true));
            }
            $maindata['userdata']= $allResponse[0];
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