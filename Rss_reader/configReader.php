<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of configReader
 *
 * @author Zen
 */
class configReader {
    private $configFileName;
    public $config;
    public function __construct($configFileName) {
        $this->configFileName = $configFileName;
        $rawConfig = file_get_contents($this->configFileName);
        //use json convert config stream into object
        $rawConfig = trim($rawConfig);
        $rawConfig=  str_replace("\r\n", "\n",$rawConfig);
        $rawConfigArray = explode("\n",$rawConfig);
        $rawConfigJson = "{";
        foreach($rawConfigArray as $configItem){
            $ConfigItemUnits = explode(":",$configItem);
            if(count($ConfigItemUnits)==2){
                $rawConfigJson.="\"".trim($ConfigItemUnits[0])."\":"."\"".trim($ConfigItemUnits[1])."\",";
            }
        }
        $rawConfigJson=substr($rawConfigJson,0,  strlen($rawConfigJson)-1);
        $rawConfigJson.="}";
        
        $this->config=json_decode($rawConfigJson);
        
    }
}
