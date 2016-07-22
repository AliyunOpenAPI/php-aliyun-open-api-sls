<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class GetMachineGroupRequest extends Request
{

    private $groupName;


    /**
     * GetMachineGroupRequest Constructor
     *
     */
    public function __construct($groupName = null)
    {
        $this->groupName = $groupName;
    }


    public function getGroupName()
    {
        return $this->groupName;
    }


    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

}
