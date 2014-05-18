<?php
include 'User.php';
session_start();
if(!isset($_SESSION['username'])&&$_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['action'])){
    $user = new User();
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    if($_POST['action']=='login'){
        if($user->isValid()){
            $_SESSION['username']=$user->username;
            $_SESSION['password']=$user->password;
        }
        else{
            $_SESSION['error']='Wrong Username or Password';
        }
    }
    else if($_POST['action']=='register'){
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
   
    
    $feedlist = $user->retrieveFeedList();
    
    $response = array();
    $response['status'] = "good";
    $info = array();
    $info['username']=$user->username;
    $info['feedlist']=$feedlist;
    $response['info']= $info;
    echo json_encode($response);
}
else{
    
    $response['status'] = "error";
    $response['info']=isset ($_SESSION['error'])?$_SESSION['error']:"";
    echo json_encode($response);
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

