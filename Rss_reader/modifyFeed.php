<?php
include 'User.php';
session_start();
$response = array();
//check is a user has login and all other information needed for modify a feed with post method
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedname']) && isset($_POST['feedurl']) && isset($_POST['newfeedname']) && isset($_POST['newfeedurl'])) {
    $user = new User();
    $user->username = $_SESSION['username'];
    $user->password = $_SESSION['password'];
    $feedname = $_POST['feedname'];
    $feedurl = $_POST['feedurl'];
    $newfeedname = trim($_POST['newfeedname']);
    $newfeedurl = trim($_POST['newfeedurl']);
    if(empty($newfeedname)||empty($newfeedname)){
        $response['status'] = "error";
        $response['info']="Do input empty name and feed url";
    }
    else if ($user->updateFeed($feedname, $feedurl, $newfeedname, $newfeedurl)) {
        $response['status'] = "good";
        $response['info']="Feed updated successfully";
    } else {
        $response['status'] = "error";
        $response['info']="Can't update the feed, please try later";
    }
    
    
}
else{
    $response['status'] = "error";
    $response['info']="Unknow Error";
}
echo json_encode($response);
?>