<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to delete logStore from log service.
 * @author log service dev
 */
class DeleteLogStoreRequest extends Request
{

    private $logStore;


    /**
     * DeleteLogStoreRequest constructor
     *
     * @param string $project project name
     */
    public function __construct($project = null, $logStore = null)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
    }


    public function getLogStore()
    {
        return $this->logStore;
    }
}
