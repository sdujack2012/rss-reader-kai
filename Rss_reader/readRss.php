<?php
include 'RssReader.php';
session_start();
//check is a user has login and a rss feed url  needed to retrieve rss content
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedurl'])){
    //parse rss feed
    $rss = new RssReader(trim($_POST['feedurl']));
    $rsscontent = $rss->getRssContent(); 
    //return parsed rss feed result in a json string
    $rssjson = json_encode($rsscontent);
    echo $rssjson;
}
?>
