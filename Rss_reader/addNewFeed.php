<?php
include 'User.php';

session_start();

//check is a user has login and all other information need with post method
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedname']) && isset($_POST['feedurl'])) {
    $user = new User();
    $user->username = $_SESSION['username'];
    $user->password = $_SESSION['password'];
   
    $feedname = trim($_POST['feedname']);
    $feedurl = trim($_POST['feedurl']);
    $feed = new Feed($feedurl,$feedname);
    if ($user->addFeeds($feed)) {
        echo "Feed added";
    } else {
        echo "Can't Add the feed, please try later";
    }
    
}