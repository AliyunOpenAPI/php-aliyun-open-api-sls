<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to list logStore from log service.
 * @author log service dev
 */
class ListLogStoresRequest extends Request
{

    /**
     * ListLogStoresRequest constructor
     *
     * @param string $project project name
     */
    public function __construct($project = null)
    {
        $this->project = $project;
    }
}
