<?php
include 'User.php';
session_start();
$response = array();
if(!isset($_SESSION['username'])&&$_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['action'])){
    $user = new User();
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    //handle login
    if($_POST['action']=='login'){
        if($user->isValid()){
            $_SESSION['username']=$user->username;
            $_SESSION['password']=$user->password;
        }
        else{
            $_SESSION['error']='Wrong Username or Password';
        }
    }
    //handle registeration
    else if($_POST['action']=='register'){
        //using regular express to valid user name and password
        if (ereg("^([a-zA-Z0-9_-]){8,15}$",$user->username)&&ereg("^([a-zA-Z0-9_-]){8,15}$",$user->password)){ 
            if($user->register()){
                $_SESSION['username']=$user->username;
                $_SESSION['password']=$user->password;
            } else{
                $_SESSION['error']="Username:".$user->username." already exists";
            }
        }
        else{
            $_SESSION['error']='Username and Password must contain 8 to 15 characters';
        }
    }
}

if(isset($_SESSION['username'])){
    $user = new User();
    $user->username=$_SESSION['username'];
    $user->password=$_SESSION['password'];
   
    //retrieve feeds owned by the user
    $feedlist = $user->retrieveFeedList();
    

    //constructing response
    $response['status'] = "good";
    $info = array();
    $info['username']=$user->username;
    $info['feedlist']=$feedlist;
    $response['info']= $info;
    //convert the response into a json string and return it to user
    
}
else{
    //deal with error
    $response['status'] = "error";
    $response['info']="Session expired, Please log in again";   
}

echo json_encode($response);

?>


