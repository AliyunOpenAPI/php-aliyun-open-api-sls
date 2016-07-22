<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class GetMachineRequest extends Request
{

    private $uuid;


    /**
     * GetMachineRequest Constructor
     *
     */
    public function __construct($uuid = null)
    {
        $this->uuid = $uuid;
    }


    public function getUuid()
    {
        return $this->uuid;
    }


    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

}
