<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to get logs by logStore and shardId from log service.
 * @author log service dev
 */
class BatchGetLogsRequest extends Request
{

    /**
     * @var string logStore name
     */
    private $logStore;

    /**
     * @var string shard ID
     */
    private $shardId;

    /**
     * @var integer max line number of return logs
     */
    private $count;

    /**
     * @var string start cursor
     */
    private $cursor;


    /**
     * BatchGetLogsRequest Constructor
     *
     * @param string  $project  project name
     * @param string  $logStore logStore name
     * @param string  $shardId  shard ID
     * @param integer $count    return max logGroup numbers
     * @param string  $cursor   start cursor
     */
    public function __construct($project = null, $logStore = null, $shardId = null, $count = null, $cursor = null)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
        $this->shardId  = $shardId;
        $this->count    = $count;
        $this->cursor   = $cursor;
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
     * Get shard ID
     *
     * @return string shardId
     */
    public function getShardId()
    {
        return $this->shardId;
    }


    /**
     * Set shard ID
     *
     * @param string $shardId
     *            shard ID
     */
    public function setShardId($shardId)
    {
        $this->shardId = $shardId;
    }


    /**
     * Get max return logGroup number
     *
     * @return integer count
     */
    public function getCount()
    {
        return $this->count;
    }


    /**
     * Set max return logGroup number
     *
     * @param integer $count
     *            max return logGroup number
     */
    public function setCount($count)
    {
        $this->count = $count;
    }


    /**
     * Get start cursor
     *
     * @return string cursor
     */
    public function getCursor()
    {
        return $this->cursor;
    }


    /**
     * Set start cursor
     *
     * @param string $cursor
     *            start cursor
     */
    public function setCursor($cursor)
    {
        $this->cursor = $cursor;
    }

}
