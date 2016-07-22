<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class ListConfigsResponse extends Response
{

    private $total;

    private $configs;


    /**
     * ListConfigsResponse constructor
     *
     * @param array $resp   GetLogs HTTP response body
     * @param array $header GetLogs HTTP response header
     */
    public function __construct($resp, $header)
    {
        parent::__construct($header);
        $this->size    = $resp['total'];
        $this->configs = $resp['configs'];
    }


    public function getSize()
    {
        return count($this->configs);
    }


    public function getTotal()
    {
        return $this->total;
    }


    public function getConfigs()
    {
        return $this->configs;
    }

}
