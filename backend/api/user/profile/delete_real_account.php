<?php
    
    include "../../../config/utilities.php";

$method = getenv('REQUEST_METHOD');
$endpoint="../../api/user/profile/".basename($_SERVER['PHP_SELF']);
if ($method == 'GET') {
   $username =$_GET['username'];
   $email =$_GET['email'];
   
    // $updatePassQuery = "DELETE FROM users  WHERE username = ? AND email=?";
    // $updateStmt = $connect->prepare($updatePassQuery);
    // $updateStmt->bind_param('ss',$username,$email);
    // $updateStmt->execute();
    
    // if ( $updateStmt->affected_rows > 0 ){
    // echo "done";
    // }
}
?>