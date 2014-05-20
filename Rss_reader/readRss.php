<?php
include 'RssReader.php';
session_start();
$response = array();
//check is a user has login and a rss feed url  needed to retrieve rss content
if (isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['feedurl'])){
    //parse rss feed
    $rss = new RssReader(trim($_POST['feedurl']));
    $rsscontent = $rss->getRssContent();
    if($rsscontent){
        //return parsed rss feed result in a json string
        $response['status'] = "good";
        $response['info']=$rsscontent;
        
    }
    else{
        $response['status'] = "error";
        $response['info']="rss";
    }
   
    
}
else{
     $response['status'] = "error";
     $response['info']="unkown";
}
echo json_encode($response);
?>
