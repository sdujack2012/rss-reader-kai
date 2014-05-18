<?php
include 'User.php';
session_start();

if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedname']) && isset($_POST['feedurl']) && isset($_POST['newfeedname']) && isset($_POST['newfeedurl'])) {
    $user = new User();
    $user->username = $_SESSION['username'];
    $user->password = $_SESSION['password'];
    $feedname = $_POST['feedname'];
    $feedurl = $_POST['feedurl'];
    $newfeedname = trim($_POST['newfeedname']);
    $newfeedurl = trim($_POST['newfeedurl']);
    if ($user->updateFeed($feedname, $feedurl, $newfeedname, $newfeedurl)) {
        echo "Feed updated successfully";
    } else {
        echo "Can't update the feed, please try later";
    }
    
}