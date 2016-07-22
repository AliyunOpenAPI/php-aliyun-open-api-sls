<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The Request used to list topics from log service.
 *
 * @author log service dev
 */
class ListTopicsRequest extends Request
{

    /**
     * @var string $logStore logStore name
     */
    private $logStore;

    /**
     * @var string $token the start token to list topics
     */
    private $token;

    /**
     * @var integer $line max topic counts to return
     */
    private $line;


    /**
     * ListTopicsRequest constructor
     *
     * @param string  $project  project name
     * @param string  $logStore logStore name
     * @param string  $token    the start token to list topics
     * @param integer $line     max topic counts to return
     */
    public function __construct($project = null, $logStore = null, $token = null, $line = null)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
        $this->token    = $token;
        $this->line     = $line;
    }


    /**
     * Get logStore  name
     *
     * @return string logStore name
     */
    public function getLogStore()
    {
        return $this->logStore;
    }


    /**
     * Set logStore name
     *
     * @param string $logStore
     *            logStore name
     */
    public function setLogStore($logStore)
    {
        $this->logStore = $logStore;
    }


    /**
     * Get start token to list topics
     *
     * @return string start token to list topics
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * Set start token to list topics
     *
     * @param string $token start token to list topics
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


    /**
     * Get max topic counts to return
     *
     * @return integer max topic counts to return
     */
    public function getLine()
    {
        return $this->line;
    }


    /**
     * Set max topic counts to return
     *
     * @param integer $line max topic counts to return
     */
    public function setLine($line)
    {
        $this->line = $line;
    }
}
