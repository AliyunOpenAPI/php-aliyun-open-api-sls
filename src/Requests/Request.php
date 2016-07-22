<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The base request of all log request.
 * @author log service dev
 */
class Request
{

    /**
     * @var string project name
     */
    protected $project;


    /**
     * Get project name
     *
     * @return string project name
     */
    public function getProject()
    {
        return $this->project;
    }


    /**
     * Set project name
     *
     * @param string $project
     *            project name
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
}
