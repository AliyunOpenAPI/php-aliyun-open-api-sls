<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to create logStore from log service.
 * @author log service dev
 */
class CreateLogStoreRequest extends Request
{

    private $logStore;

    private $ttl;

    private $shardCount;


    /**
     * CreateLogStoreRequest constructor
     *
     * @param string $project project name
     */
    public function __construct($project = null, $logStore = null, $ttl = null, $shardCount = null)
    {
        $this->project    = $project;
        $this->logStore   = $logStore;
        $this->ttl        = $ttl;
        $this->shardCount = $shardCount;
    }


    public function getLogStore()
    {
        return $this->logStore;
    }


    public function getTtl()
    {
        return $this->ttl;
    }


    public function getShardCount()
    {
        return $this->shardCount;
    }
}
