<?php
include 'User.php';
session_start();

if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedname']) && isset($_POST['feedurl'])) {
    $user = new User();
    $user->username = $_SESSION['username'];
    $user->password = $_SESSION['password'];
    
    $feedname = $_POST['feedname'];
    $feedurl = $_POST['feedurl'];
    if ($user->deleteFeed($feedname, $feedurl)) {
        echo "Feed deleted successfully";
    } else {
        echo "Can't delete the feed, please try later";
    }
    
}