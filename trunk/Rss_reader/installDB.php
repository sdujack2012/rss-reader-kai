<?php
//this is where to start
include 'Functions.php';

$db = getDatabaseConnection();

$stmt = $db->prepare("DROP TABLE IF EXISTS user_feed;");
$stmt->execute();
$stmt->close();

$stmt = $db->prepare("DROP TABLE IF EXISTS rss_user;");
$stmt->execute();
$stmt->close();



$stmt = $db->prepare("create table rss_user(
    username varchar (50) primary key,
    password varchar(50)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;");
$stmt->execute();
if($stmt->error)
	die("Can't install table because ".$stmt->error);
$stmt->close();
$stmt = $db->prepare("create table user_feed(
    username varchar (50),
    url varchar (300),
    feedname varchar (300),
    PRIMARY KEY(username,url),
    CONSTRAINT uf_username FOREIGN KEY (username) REFERENCES rss_user (username)  
)ENGINE=InnoDB DEFAULT CHARSET=latin1;");
$stmt->execute();
if($stmt->error)
	die("Can't install table because ".$stmt->error);
echo "Great, Rss reader installed!";	
echo "<head><meta http-equiv='refresh' content='2;url=./index.html'> </head>";


