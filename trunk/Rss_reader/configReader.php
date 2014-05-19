<?php

/**
 * Description: 
 * <br/>a config reader class which reads a plain text file with lines containing "key:value" pairs 
 * @author Kai Jiang 
 * @version 1.0 
 */
class configReader {

    /**
     *text file path
     */
    private $configFileName;

    /**
     *object containing "key:value" pairs
     */
    public $config;

    /**
     * class constructor parse content of structed plain text file into a object
     * <br />using json_decode
     * @param  configFileName  plain text file path
     */
    public function __construct($configFileName) {
        $this->configFileName = $configFileName;
        $rawConfig = file_get_contents($this->configFileName);
        //use json convert config stream into object
        $rawConfig = trim($rawConfig);
        $rawConfig = str_replace("\r\n", "\n", $rawConfig);
        $rawConfigArray = explode("\n", $rawConfig);
        //start to construct json string
        $rawConfigJson = "{";
        
        //extract information from the the file
        foreach ($rawConfigArray as $configItem) {
            $ConfigItemUnits = explode(":", $configItem);
            if (count($ConfigItemUnits) == 2) {
                $rawConfigJson.="\"" . trim($ConfigItemUnits[0]) . "\":" . "\"" . trim($ConfigItemUnits[1]) . "\",";
            }
        }
        $rawConfigJson = substr($rawConfigJson, 0, strlen($rawConfigJson) - 1);
        $rawConfigJson.="}";
        //construct a object containing "key:value" pairs from the json string
        $this->config = json_decode($rawConfigJson);
    }

}
