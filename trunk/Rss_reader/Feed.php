<?php
/**
 * Description of Feed
 *
 * @author Zen
 */
class Feed {
    public $url;
    public $feedname;
    public function __construct($url,$feedname) {
        $this->url = trim($url);
        $this->feedname = trim($feedname);
    }
}
