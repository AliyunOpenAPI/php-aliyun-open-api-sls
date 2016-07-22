<?php namespace Aliyun\SLS\Requests;

use Aliyun\SLS\Models\MachineGroup;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class CreateMachineGroupRequest extends Request
{

    /**
     * @var MachineGroup
     */
    private $machineGroup;


    /**
     * CreateMachineGroupRequest Constructor
     *
     */
    public function __construct($machineGroup = null)
    {
        $this->machineGroup = $machineGroup;
    }


    public function getMachineGroup()
    {
        return $this->machineGroup;
    }


    public function setMachineGroup($machineGroup)
    {
        $this->machineGroup = $machineGroup;
    }

}
