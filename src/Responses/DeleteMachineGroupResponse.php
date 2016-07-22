<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The response of the GetLog API from log service.
 * @author log service dev
 */
class DeleteMachineGroupResponse extends Response
{

    /**
     * DeleteMachineGroupResponse constructor
     *
     * @param array $resp   GetLogs HTTP response body
     * @param array $header GetLogs HTTP response header
     */
    public function __construct($header)
    {
        parent::__construct($header);
    }

}
