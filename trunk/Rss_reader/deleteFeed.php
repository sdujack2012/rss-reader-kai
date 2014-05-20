<?php
include 'User.php';
session_start();
$response = array();
//check is a user has login and all other information needed for delete a feed with post method
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedname']) && isset($_POST['feedurl'])) {
    $user = new User();
    $user->username = $_SESSION['username'];
    $user->password = $_SESSION['password'];
    
    $feedname = $_POST['feedname'];
    $feedurl = $_POST['feedurl'];
    if ($user->deleteFeed($feedname, $feedurl)) {
        $response['status'] = "good";
        $response['info']="Feed deleted successfully";
       
    } else {
        $response['status'] = "error";
        $response['info']="Can't delete the feed, please try later";
       
    }  
}
else{
    $response['status'] = "error";
    $response['info']="Unkown Error"; 
}

echo json_encode($response);