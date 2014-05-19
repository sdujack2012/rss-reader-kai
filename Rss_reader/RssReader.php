<?php
/**
 * Description: 
 * <br />a class which parses rss feed as return an array of parsed feed result.
 * <br />Also, using cache file to speed up the process
 * @author Kai Jiang 
 * @version 1.0 
 */
class RssReader {

    /**
     * feed url
     */
    private $url;

    /**
     * an array containing parsed rss xml items
     */
    private $RssArray;

    /**
     * cache path in which cached parsed rss data will be hold 
     */
    private $cacheDir = "./cache/";

    /**
     * the maximun time for a cached file to use
     */
    private $maxCacheTime = 600;

    /**
     * take a rss url then either get data from previous cached data if a valid cached file exist
     * <br />or get xml form the url and parsed it and cached the result
     */
    public function __construct($url) {
        $this->url = trim($url);
        if (!($this->RssArray = $this->getCache())) {//get cache
            if ($this->RssArray = $this->parseRss()) {
                $encryptedUrl = md5($this->url);
                $cachefile = $this->cacheDir . $encryptedUrl;
                file_put_contents($cachefile, serialize($this->RssArray));
            }
        }
    }
    /**
     * core part of this class. used for parsing rss xml
     */
    private function parseRss() {
        //get raw rss data
        $rawdata = "";
        $rawdata = file_get_contents($this->url);
        if (strlen($rawdata) == 0) {
            return false;
        }

        //create a xml parser
        $parser = xml_parser_create();
        //set parsing options
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

        //put the result of parsing into an array
        $rawRssArray = array();
        xml_parse_into_struct($parser, $rawdata, $rawRssArray);
        //check error
        if (xml_get_error_code($parser)) {
            return false;
        }

        //free xml parser    
        xml_parser_free($parser);
        $is_item = 0;
        foreach ($rawRssArray as $rawRssItem) {
            $tag = $rawRssItem["tag"];
            $type = $rawRssItem["type"];
            $rawRssItemue = isset($rawRssItem["value"]) ? $rawRssItem["value"] : "";
            //for extracting item, convert all letters in tag into lower case         
            $tag = strtolower($tag);
            if ($tag == "item" && $type == "open") {
                $is_item = 1;
            } else if ($tag == "item" && $type == "close") {
                $is_item = 0;
                $arrdata[] = $arrdatason;
            } else if ($is_item && $type == "complete") {
                $arrdatason[$tag] = $rawRssItemue;
            }
        }
        return $arrdata;
    }
    /**
     * check and get catched file within a specified period of time otherwise get xml from
     * <br /> given url and parse it
     */
    private function getCache() {
        //get raw rss data
        $encryptedUrl = md5($this->url); //md5 the url as a cache name
        $cachefile = $this->cacheDir . $encryptedUrl;
        if (file_exists($cachefile) && time() - filemtime($cachefile) < $this->maxCacheTime) {
            return unserialize(file_get_contents($cachefile));
        } else {
            return false;
        }
    }
    /**
     * return parsed rss items
     */
    public function getRssContent() {
        return $this->RssArray;
    }

}
