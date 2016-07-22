<?php namespace Aliyun\SLS\Requests;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The request used to get cursor by fromTime or begin/end mode
 * @author log service dev
 */
class GetCursorRequest extends Request
{

    /**
     * @var string logStore name
     */
    private $logStore;

    /**
     * @var string shard id
     */
    private $shardId;

    //mode and fromTime: choose one and another remains null
    /**
     * @var string value should be 'begin' or 'end'
     *         begin:return cursor point to first loggroup
     *         end:return cursor point to position after last loggroup
     *         if $mode is set to not null,$fromTime must be set null
     */
    private $mode;

    /**
     * @var integer unix_timestamp
     *         return cursor point to first logGroup whose time after $fromTime
     */
    private $fromTime;


    /**
     * GetCursorRequest Constructor
     *
     * @param string $project  project name
     * @param string $logStore logStore name
     * @param string $shardId  shard id
     * @param string $mode     query mode,value must be 'begin' or 'end'
     * @param string $fromTime query by from time,unix_timestamp
     */
    public function __construct($project, $logStore, $shardId, $mode = null, $fromTime = -1)
    {
        $this->project  = $project;
        $this->logStore = $logStore;
        $this->shardId  = $shardId;
        $this->mode     = $mode;
        $this->fromTime = $fromTime;
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
     * Get shard id
     *
     * @return string shard id
     */
    public function getShardId()
    {
        return $this->shardId;
    }


    /**
     * Set shard id
     *
     * @param string $shardId
     *            shard id
     */
    public function setShardId($shardId)
    {
        $this->shardId = $shardId;
    }


    /**
     * Get mode
     *
     * @return string mode
     */
    public function getMode()
    {
        return $this->mode;
    }


    /**
     * Set mode
     *
     * @param string $mode
     *            value must be 'begin' or 'end'
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }


    /**
     * Get from time
     *
     * @return integer(unix_timestamp) from time
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }


    /**
     * Set from time
     *
     * @param integer $fromTime
     *            from time (unix_timestamp)
     */
    public function setFromTime($fromTime)
    {
        $this->fromTime = $fromTime;
    }

}
