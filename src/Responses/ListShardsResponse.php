<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The response of the GetLog API from log service.
 * @author log service dev
 */
class ListShardsResponse extends Response
{

    private $shardIds;


    /**
     * ListShardsResponse constructor
     *
     * @param array $resp   GetLogs HTTP response body
     * @param array $header GetLogs HTTP response header
     */
    public function __construct($resp, $header)
    {
        parent::__construct($header);
        foreach ($resp as $key => $value) {
            $this->shardIds[] = $value['shardID'];
        }
    }


    public function getShardIds()
    {
        return $this->shardIds;
    }

}
