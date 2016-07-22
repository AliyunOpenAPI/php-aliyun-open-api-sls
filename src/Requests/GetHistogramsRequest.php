<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to get histograms of a query from log service.
 * @author log service dev
 */
class GetHistogramsRequest extends Request
{

    /**
     * @var string logStore name
     */
    private $logStore;

    /**
     * @var string topic name of logs
     */
    private $topic;

    /**
     * @var integer the begin time
     */
    private $from;

    /**
     * @var integer the end time
     */
    private $to;

    /**
     * @var string user defined query
     */
    private $query;


    /**
     * GetHistogramsRequest constructor
     *
     * @param string  $project  project name
     * @param string  $logStore logStore name
     * @param integer $from     the begin time
     * @param integer $to       the end time
     * @param string  $topic    topic name of logs
     * @param string  $query    user defined query
     */
    public function __construct(
        $project = null,
        $logStore = null,
        $from = null,
        $to = null,
        $topic = null,
        $query = null
    ) {
        $this->project  = $project;
        $this->logStore = $logStore;
        $this->from     = $from;
        $this->to       = $to;
        $this->topic    = $topic;
        $this->query    = $query;
    }


    /**
     * Get logStore name
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
     * Get begin time
     *
     * @return integer begin time
     */
    public function getFrom()
    {
        return $this->from;
    }


    /**
     * Set begin time
     *
     * @param integer $from
     *            begin time
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }


    /**
     * Get end time
     *
     * @return integer end time
     */
    public function getTo()
    {
        return $this->to;
    }


    /**
     * Set end time
     *
     * @param integer $to
     *            end time
     */
    public function setTo($to)
    {
        $this->to = $to;
    }


    /**
     * Get user defined query
     *
     * @return string user defined query
     */
    public function getQuery()
    {
        return $this->query;
    }


    /**
     * Set user defined query
     *
     * @param string $query
     *            user defined query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }
}
