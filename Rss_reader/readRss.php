<?php
include 'RssReader.php';
session_start();
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedurl'])){
    $rss = new RssReader($_POST['feedurl']);
    $rsscontent = $rss->getRssContent(); 
    $rssjson = json_encode($rsscontent);
    echo $rssjson;
}
?>
