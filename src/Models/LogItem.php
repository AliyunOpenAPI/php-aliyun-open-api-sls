<?php namespace Aliyun\SLS\Models;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * LogItem used to present a log, it contains log time and multiple
 * key/value pairs to present the log contents.
 * @author log service dev
 */
class LogItem
{

    /**
     * @var integer time of the log item, the default time if the now time.
     */
    private $time;

    /**
     * @var array the data of the log item, including many key/value pairs.
     */
    private $data;


    /**
     * LogItem constructor
     *
     * @param array   $data the data of the log item, including many key/value pairs.
     * @param integer $time time of the log item, the default time if the now time.
     */
    public function __construct($data = null, $time = null)
    {
        if ( ! $time) {
            $time = time();
        }

        $this->time = $time;

        if ($data) {
            $this->data = $data;
        } else {
            $this->data = array();
        }
    }


    /**
     * Get log time
     *
     * @return integer log time
     */
    public function getTime()
    {
        return $this->time;
    }


    /**
     * Set log time
     *
     * @param integer $time log time
     *
     * @return $this
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }


    /**
     * Get log contents
     *
     * @return array log contents
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Set log contents
     *
     * @param array $data log contents
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }


    /**
     * Add a key/value pair as log content to the log
     *
     * @param string $key
     *            log content key
     * @param string $value
     *            log content value
     */
    public function pushBack($key, $value)
    {
        $this->data[$key] = $value;
    }
}
