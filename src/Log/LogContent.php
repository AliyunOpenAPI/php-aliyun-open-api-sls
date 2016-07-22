<?php namespace Aliyun\SLS\Log;

use Aliyun\SLS\Exception;
use Aliyun\SLS\Protobuf;

class LogContent
{

    private $_unknown;

    private $key_ = null;

    private $value_ = null;


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
            //var_dump("LogContent: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
            switch ($field) {
                case 1:
                    assert('$wire == 2');
                    $len = Protobuf::read_varint($fp, $limit);
                    if ($len === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    if ($len > 0) {
                        $tmp = fread($fp, $len);
                    } else {
                        $tmp = '';
                    }
                    if ($tmp === false) {
                        throw new Exception("fread($len) returned false");
                    }
                    $this->key_ = $tmp;
                    $limit -= $len;
                    break;
                case 2:
                    assert('$wire == 2');
                    $len = Protobuf::read_varint($fp, $limit);
                    if ($len === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    if ($len > 0) {
                        $tmp = fread($fp, $len);
                    } else {
                        $tmp = '';
                    }
                    if ($tmp === false) {
                        throw new Exception("fread($len) returned false");
                    }
                    $this->value_ = $tmp;
                    $limit -= $len;
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
        if ($this->key_ === null) {
            return false;
        }
        if ($this->value_ === null) {
            return false;
        }

        return true;
    }


    function write($fp)
    {
        if ( ! $this->validateRequired()) {
            throw new Exception('Required fields are missing');
        }
        if ( ! is_null($this->key_)) {
            fwrite($fp, "\x0a");
            Protobuf::write_varint($fp, strlen($this->key_));
            fwrite($fp, $this->key_);
        }
        if ( ! is_null($this->value_)) {
            fwrite($fp, "\x12");
            Protobuf::write_varint($fp, strlen($this->value_));
            fwrite($fp, $this->value_);
        }
    }


    // required string key = 1;

    public function size()
    {
        $size = 0;
        if ( ! is_null($this->key_)) {
            $l = strlen($this->key_);
            $size += 1 + Protobuf::size_varint($l) + $l;
        }
        if ( ! is_null($this->value_)) {
            $l = strlen($this->value_);
            $size += 1 + Protobuf::size_varint($l) + $l;
        }

        return $size;
    }


    public function __toString()
    {
        return '' . Protobuf::toString('unknown', $this->_unknown) . Protobuf::toString('key_',
            $this->key_) . Protobuf::toString('value_', $this->value_);
    }


    public function clearKey()
    {
        $this->key_ = null;
    }


    public function hasKey()
    {
        return $this->key_ !== null;
    }


    public function getKey()
    {
        if ($this->key_ === null) {
            return "";
        } else {
            return $this->key_;
        }
    }


    // required string value = 2;

    public function setKey($value)
    {
        $this->key_ = $value;
    }


    public function clearValue()
    {
        $this->value_ = null;
    }


    public function hasValue()
    {
        return $this->value_ !== null;
    }


    public function getValue()
    {
        if ($this->value_ === null) {
            return "";
        } else {
            return $this->value_;
        }
    }


    public function setValue($value)
    {
        $this->value_ = $value;
    }
}