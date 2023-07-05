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
function return_blog($category_id){
    global $connect;
    //To Fecth All Blogs
    $newArray = [];
    $stmt1= $connect->prepare("SELECT * FROM `blog` LEFT JOIN authors ON blog.authorid = authors.id WHERE authors.type = 1 AND blog.category = '$category_id' AND blog.draft = 1 ORDER BY blog.id DESC LIMIT 5");
    $stmt1->execute();
    $result= $stmt1->get_result();
    $numRow = $result->num_rows;
    if($numRow > 0){
        while($blog = $result->fetch_assoc()){
            $blog['blogheadline']=showpost( $blog['blogheadline']);
            $blog['blogcontent']=showpost($blog['blogcontent']);
            array_push($newArray,json_decode(json_encode($blog), true));
        }
    }
    $stmt1->close();
    return $newArray;
}

if ($method == 'GET') {
        $sqlQuery = "SELECT * FROM `blog_category` LIMIT 3";
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
                              'blog'=> return_blog($users['id']) 
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