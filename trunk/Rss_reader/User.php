<?php

include 'Functions.php';
include 'Feed.php';

/**
 * Description: 
 * <br />a class which maps rss_user table in database.
 * @author Kai Jiang 
 * @version 1.0 
 */
class User {

    /**
     * map username filed in rss_user table
     */
    public $username;

    /**
     * map password filed in rss_user table
     */
    public $password;

    /**
     * containing all feed associated with the user
     */
    public $feedList = array();

    /**
     * mysqli object for database access
     */
    private $dbconnection;

    /**
     * check if the user exists with given username and password
     */
    public function isValid() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        //retrieve user infomation from database
        $stmt = $this->dbconnection->prepare("select username from rss_user where username=? and password=?");
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

    /**
     * get all the rss feeds associated with the user
     */
    public function retrieveFeedList() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        if ($this->isValid()) {
            //retrieve feeds from database
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

    /**
     * delete a feed with a given feed name and an feed url
     */
    public function deleteFeed($feedname, $url) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()) {
            //delete feeds from database
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

    /**
     * modify a feed info
     */
    public function updateFeed($oldfeedname, $oldurl, $newfeedname, $newfeedurl) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()) {
            //modify feeds from database
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

    /**
     * register a new user
     */
    public function register() {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }

        if ($this->isValid()) {
            return 0;
        } else {
            //add a new user to the database
            $stmt = $this->dbconnection->prepare("insert into rss_user values(?,?)");

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

    /**
     * add a new feed with a user
     */
    public function addFeeds($feed) {
        if (!$this->dbConnected()) {
            $this->dbconnection = getDatabaseConnection();
        }
        if ($this->isValid()) {
            //add a new feed to the database
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

    /**
     * check if an object has a database connection
     */
    public function dbConnected() {
        if ($this->dbconnection != null) {
            return true;
        } else {
            return false;
        }
    }

}
