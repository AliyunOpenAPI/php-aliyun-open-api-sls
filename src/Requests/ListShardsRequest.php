<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * @author log service dev
 */
class ListShardsRequest extends Request
{

    private $logStore;


    /**
     * ListShardsRequest Constructor
     *
     */
    public function __construct($project, $logStore)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
    }


    public function getLogStore()
    {
        return $this->logStore;
    }


    public function setLogStore($logStore)
    {
        $this->logStore = $logStore;
    }

}
