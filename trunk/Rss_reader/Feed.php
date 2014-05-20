<?php

/**
 * Description: 
 * <br/>a simple class representing a record of user_feed table in database
 * @author Kai Jiang 
 * @version 1.0 
 */
class Feed {

    /**
     * map url filed in user_feed table
     */
    public $url="";

    /**
     * map feedname in user_feed table
     */
    public $feedname="";

    public function __construct($url, $feedname) {
        $this->url = trim($url);
        $this->feedname = trim($feedname);
    }

}
