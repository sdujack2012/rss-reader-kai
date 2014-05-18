<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RssReader
 *
 * @author Zen
 */
class RssReader {

    private $url;
    private $RssArray;
    private $cacheDir = "./cache/";//set up cache directory
    private $maxCacheTime=600;//set up cache expiry time
    public function __construct($url) {
        $this->url = trim($url);
        if(!($this->RssArray=$this->getCache())){//get cache
            if($this->RssArray=$this->parseRss()){
                $encryptedUrl = md5($this->url);
                $cachefile=$this->cacheDir.$encryptedUrl;
                file_put_contents($cachefile, serialize($this->RssArray));
            }
        }
        
        
    }

    private function parseRss() {
        //get raw rss data
        $rawdata="";
        $rawdata = file_get_contents($this->url);
        if(strlen($rawdata)==0){
            return false;
        }
       
        //create a xml parser
        $parser = xml_parser_create();
        //set parsing options
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        
        //put the result of parsing into an array
        $rawRssArray=array();
        xml_parse_into_struct($parser, $rawdata, $rawRssArray);
        //free xml parser    
        xml_parser_free($parser);
        $is_item = 0;
        foreach ($rawRssArray as $rawRssItem) {
            $tag = $rawRssItem["tag"];
            $type = $rawRssItem["type"];
            $rawRssItemue = isset($rawRssItem["value"])?$rawRssItem["value"]:"" ;
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
    
    private function getCache() {
        //get raw rss data
        $encryptedUrl = md5($this->url);
        $cachefile=$this->cacheDir.$encryptedUrl;
        if(file_exists($cachefile) && time()-filemtime($cachefile) < $this->maxCacheTime){
            return unserialize(file_get_contents($cachefile));  
        }  
        else{
            return false;
        }
    }
    
    public function getRssContent() {
        return $this->RssArray;
    }

}
