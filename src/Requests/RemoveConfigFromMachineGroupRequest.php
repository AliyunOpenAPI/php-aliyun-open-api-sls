<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class RemoveConfigFromMachineGroupRequest extends Request
{

    private $groupName;

    private $configName;


    /**
     * RemoveConfigFromMachineGroupRequest Constructor
     *
     */
    public function __construct($groupName = null, $configName = null)
    {
        $this->groupName  = $groupName;
        $this->configName = $configName;
    }


    public function getGroupName()
    {
        return $this->groupName;
    }


    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }


    public function getConfigName()
    {
        return $this->configName;
    }


    public function setConfigName($configName)
    {
        $this->configName = $configName;
    }

}
