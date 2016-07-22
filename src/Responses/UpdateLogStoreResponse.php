<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The response of the UpdateLogStore API from log service.
 * @author log service dev
 */
class UpdateLogStoreResponse extends Response
{

    /**
     * UpdateLogStoreResponse constructor
     *
     * @param array $resp   UpdateLogStore HTTP response body
     * @param array $header UpdateLogStore HTTP response header
     */
    public function __construct($resp, $header)
    {
        parent::__construct($header);
    }

}
