<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class ListMachineGroupsRequest extends Request
{

    private $groupName;

    private $offset;

    private $size;


    /**
     * ListMachineGroupsRequest Constructor
     *
     */
    public function __construct($groupName = null, $offset = null, $size = null)
    {
        $this->groupName = $groupName;
        $this->offset    = $offset;
        $this->size      = $size;
    }


    public function getGroupName()
    {
        return $this->groupName;
    }


    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }


    public function getOffset()
    {
        return $this->offset;
    }


    public function setOffset($offset)
    {
        $this->offset = $offset;
    }


    public function getSize()
    {
        return $this->size;
    }


    public function setSize($size)
    {
        $this->size = $size;
    }
}
