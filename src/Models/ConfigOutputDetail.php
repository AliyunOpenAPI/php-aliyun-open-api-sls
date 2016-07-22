<?php namespace Aliyun\SLS\Models;

class ConfigOutputDetail
{

    public $projectName;

    public $logStoreName;


    public function __construct($projectName = '', $logStoreName = '')
    {
        $this->projectName  = $projectName;
        $this->logStoreName = $logStoreName;
    }


    public function toArray()
    {
        $resArray = array();
        if ($this->projectName !== null) {
            $resArray['projectName'] = $this->projectName;
        }
        if ($this->logStoreName !== null) {
            $resArray['logStoreName'] = $this->logStoreName;
        }

        return $resArray;
    }
}