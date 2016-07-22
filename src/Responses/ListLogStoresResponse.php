<?php namespace Aliyun\SLS\Responses;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * The response of the ListLogStores API from log service.
 * @author log service dev
 */
class ListLogStoresResponse extends Response
{

    /**
     * @var integer the number of total logStores from the response
     */
    private $count;

    /**
     * @var array all logStore
     */
    private $logStores;


    /**
     * ListLogStoresResponse constructor
     *
     * @param array $resp   ListLogStores HTTP response body
     * @param array $header ListLogStores HTTP response header
     */
    public function __construct($resp, $header)
    {
        parent::__construct($header);
        $this->count     = $resp ['total'];
        $this->logStores = $resp ['logStores'];
    }


    /**
     * Get total count of logStores from the response
     *
     * @return integer the number of total logStores from the response
     */
    public function getCount()
    {
        return $this->count;
    }


    /**
     * Get all the logStores from the response
     *
     * @return array all logStore
     */
    public function getLogStores()
    {
        return $this->logStores;
    }
}
