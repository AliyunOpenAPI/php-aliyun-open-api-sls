<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to send data to log server.
 * @author log service dev
 */
class PutLogsRequest extends Request
{

    /**
     * @var string logStore name
     */
    private $logStore;

    /**
     * @var string topic name
     */
    private $topic;

    /**
     * @var string source of the logs
     */
    private $source;

    /**
     * @var array LogItem array, log data
     */
    private $logItems;


    /**
     * PutLogsRequest cnstructor
     *
     * @param string $project  project name
     * @param string $logStore logStore name
     * @param string $topic    topic name
     * @param string $source   source of the log
     * @param array  $logItems LogItem array,log data
     */
    public function __construct($project = null, $logStore = null, $topic = null, $source = null, $logItems = null)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
        $this->topic    = $topic;
        $this->source   = $source;
        $this->logItems = $logItems;
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
     * Get topic name
     *
     * @return string topic name
     */
    public function getTopic()
    {
        return $this->topic;
    }


    /**
     * Set topic name
     *
     * @param string $topic
     *            topic name
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }


    /**
     * Get all the log data
     *
     * @return array LogItem array, log data
     */
    public function getLogItems()
    {
        return $this->logItems;
    }


    /**
     * Set the log data
     *
     * @param array $logItems LogItem array, log data
     */
    public function setLogItems($logItems)
    {
        $this->logItems = $logItems;
    }


    /**
     * Get log source
     *
     * @return string log source
     */
    public function getSource()
    {
        return $this->source;
    }


    /**
     * set log source
     *
     * @param string $source
     *            log source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }
}