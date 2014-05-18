<?php

include 'Functions.php';
include 'Feed.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Zen
 */
class User {

    public $username;
    public $password;
    public $feedList = array();
    private $dbconnection;

    public function isValid() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        $stmt = $this->dbconnection->prepare("select username from users where username=? and password=?");
        $stmt->bind_param('ss', $this->username, md5($this->password));
        $stmt->execute();
        if ($stmt->fetch()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function retrieveFeedList() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        if ($this->isValid()) {
            $stmt = $this->dbconnection->prepare("select url,feedname from user_feed where username=?");

            $stmt->bind_param('s', $this->username);
            $stmt->execute();
            $stmt->bind_result($url, $feedname);
            while ($stmt->fetch()) {
                $feed = new Feed($url, $feedname);
                $this->feedList[] = $feed;
            }
        }

        $stmt->close();
        return $this->feedList;
    }

    public function deleteFeed($feedname, $url) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()){
            $stmt = $this->dbconnection->prepare("delete from user_feed where username=? and feedname=? and url=?");
            $stmt->bind_param('sss', $this->username, $feedname, $url);
            $stmt->execute();
            if (!$stmt->error) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }
        return false;
    }

    public function updateFeed($oldfeedname, $oldurl, $newfeedname, $newfeedurl) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()) {
            $stmt = $this->dbconnection->prepare("update user_feed set feedname=?,url=? where username=? and feedname=? and url=?");
            $stmt->bind_param('sssss', $newfeedname, $newfeedurl, $this->username, $oldfeedname, $oldurl);
            $stmt->execute();
            
            if (!$stmt->error) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        } 
    }

    public function register() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()) {
            return 0;
        } else {
            $stmt = $this->dbconnection->prepare("insert into users values(?,?)");

            $stmt->bind_param('ss', $this->username, md5($this->password));
            $stmt->execute();

            if (!$stmt->error) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        }
    }

    public function addFeeds($feed) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        if ($this->isValid()) {
            $stmt = $this->dbconnection->prepare("insert into user_feed values(?,?,?)");
            if (is_array($feed)) {
                foreach ($feed as $oneofFeed) {
                    $stmt->bind_param('sss', $this->username, $oneofFeed->url, $oneofFeed->feedname);
                    $stmt->execute();
                }
            } else {
                $stmt->bind_param('sss', $this->username, $feed->url, $feed->feedname);
                $stmt->execute();
            }
        }
        if (!$stmt->error) {
                $stmt->close();
                return true;
            } else {
                $stmt->close();
                return false;
            }
        
        
    }

    public function dbConnected() {
        if ($this->dbconnection != null) {
            return true;
        } else {
            return false;
        }
    }

    //put your code here
}
