<?php namespace Aliyun\SLS\Models;

/**
 * Copyright (C) Alibaba Cloud Computing
 * All rights reserved
 *
 * CompressedLogGroup is compressed LogGroup,
 * LogGroup infomation please refer to LogGroup
 * @author log service dev
 */
class CompressedLogGroup
{

    /**
     * @var integer uncompressed LogGroup size
     *
     */
    protected $uncompressedSize;

    /**
     * @var integer uncompressed LogGroup size
     *
     */
    protected $compressedData;


    public function __construct($time = null, $contents = null)
    {
        if ( ! $time) {
            $time = time();
        }
        $this->time = $time;
        if ($contents) {
            $this->contents = $contents;
        } else {
            $this->contents = array();
        }
    }

}
