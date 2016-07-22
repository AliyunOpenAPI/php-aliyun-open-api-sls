<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The response of the CreateLogStore API from log service.
 * @author log service dev
 */
class CreateLogStoreResponse extends Response
{

    /**
     * CreateLogStoreResponse constructor
     *
     * @param array $resp   CreateLogStore HTTP response body
     * @param array $header CreateLogStore HTTP response header
     */
    public function __construct($resp, $header)
    {
        parent::__construct($header);
    }

}
