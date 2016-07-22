<?php namespace Aliyun\SLS\Log;

use Aliyun\SLS\Exception;
use Aliyun\SLS\Protobuf;

class LogGroupList
{

    private $_unknown;

    private $logGroupList_ = null;


    function __construct($in = null, &$limit = PHP_INT_MAX)
    {
        if ($in !== null) {
            if (is_string($in)) {
                $fp = fopen('php://memory', 'r+b');
                fwrite($fp, $in);
                rewind($fp);
            } else {
                if (is_resource($in)) {
                    $fp = $in;
                } else {
                    throw new Exception('Invalid in parameter');
                }
            }
            $this->read($fp, $limit);
        }
    }


    function read($fp, &$limit = PHP_INT_MAX)
    {
        while ( ! feof($fp) && $limit > 0) {
            $tag = Protobuf::read_varint($fp, $limit);
            if ($tag === false) {
                break;
            }
            $wire  = $tag & 0x07;
            $field = $tag >> 3;
            //var_dump("LogGroupList: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
            switch ($field) {
                case 1:
                    assert('$wire == 2');
                    $len = Protobuf::read_varint($fp, $limit);
                    if ($len === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    $limit -= $len;
                    $this->logGroupList_[] = new LogGroup($fp, $len);
                    assert('$len == 0');
                    break;
                default:
                    $this->_unknown[$field . '-' . Protobuf::get_wiretype($wire)][] = Protobuf::read_field($fp, $wire,
                        $limit);
            }
        }
        if ( ! $this->validateRequired()) {
            throw new Exception('Required fields are missing');
        }
    }


    public function validateRequired()
    {
        return true;
    }


    function write($fp)
    {
        if ( ! $this->validateRequired()) {
            throw new Exception('Required fields are missing');
        }
        if ( ! is_null($this->logGroupList_)) {
            foreach ($this->logGroupList_ as $v) {
                fwrite($fp, "\x0a");
                Protobuf::write_varint($fp, $v->size()); // message
                $v->write($fp);
            }
        }
    }


    public function size()
    {
        $size = 0;
        if ( ! is_null($this->logGroupList_)) {
            foreach ($this->logGroupList_ as $v) {
                $l = $v->size();
                $size += 1 + Protobuf::size_varint($l) + $l;
            }
        }

        return $size;
    }


    // repeated .LogGroup logGroupList = 1;


    public function __toString()
    {
        return '' . Protobuf::toString('unknown', $this->_unknown) . Protobuf::toString('logGroupList_',
            $this->logGroupList_);
    }


    public function clearLogGroupList()
    {
        $this->logGroupList_ = null;
    }


    public function getLogGroupListCount()
    {
        if ($this->logGroupList_ === null) {
            return 0;
        } else {
            return count($this->logGroupList_);
        }
    }


    public function getLogGroupList($index)
    {
        return $this->logGroupList_[$index];
    }


    public function setLogGroupList($index, $value)
    {
        $this->logGroupList_[$index] = $value;
    }


    public function getLogGroupListArray()
    {
        if ($this->logGroupList_ === null) {
            return array();
        } else {
            return $this->logGroupList_;
        }
    }


    public function addLogGroupList($value)
    {
        $this->logGroupList_[] = $value;
    }


    public function addAllLogGroupList(array $values)
    {
        foreach ($values as $value) {
            $this->logGroupList_[] = $value;
        }
    }
}