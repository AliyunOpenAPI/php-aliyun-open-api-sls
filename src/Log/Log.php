<?php namespace Aliyun\SLS\Log;

use Aliyun\SLS\Exception;
use Aliyun\SLS\Protobuf;

class Log
{

    private $unknown;

    private $time = null;

    private $contents = null;


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
            //var_dump("Log: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
            switch ($field) {
                case 1:
                    assert('$wire == 0');
                    $tmp = Protobuf::read_varint($fp, $limit);
                    if ($tmp === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    $this->time = $tmp;

                    break;
                case 2:
                    assert('$wire == 2');
                    $len = Protobuf::read_varint($fp, $limit);
                    if ($len === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    $limit -= $len;
                    $this->contents[] = new LogContent($fp, $len);
                    assert('$len == 0');
                    break;
                default:
                    $this->unknown[$field . '-' . Protobuf::get_wiretype($wire)][] = Protobuf::read_field($fp, $wire,
                        $limit);
            }
        }
        if ( ! $this->validateRequired()) {
            throw new Exception('Required fields are missing');
        }
    }


    public function validateRequired()
    {
        if ($this->time === null) {
            return false;
        }

        return true;
    }


    function write($fp)
    {
        if ( ! $this->validateRequired()) {
            throw new Exception('Required fields are missing');
        }
        if ( ! is_null($this->time)) {
            fwrite($fp, "\x08");
            Protobuf::write_varint($fp, $this->time);
        }
        if ( ! is_null($this->contents)) {
            foreach ($this->contents as $v) {
                fwrite($fp, "\x12");
                Protobuf::write_varint($fp, $v->size()); // message
                $v->write($fp);
            }
        }
    }


    // required uint32 time = 1;

    public function size()
    {
        $size = 0;
        if ( ! is_null($this->time)) {
            $size += 1 + Protobuf::size_varint($this->time);
        }
        if ( ! is_null($this->contents)) {
            foreach ($this->contents as $v) {
                $l = $v->size();
                $size += 1 + Protobuf::size_varint($l) + $l;
            }
        }

        return $size;
    }


    public function __toString()
    {
        return '' . Protobuf::toString('unknown', $this->unknown) . Protobuf::toString('time_',
            $this->time) . Protobuf::toString('contents_', $this->contents);
    }


    public function clearTime()
    {
        $this->time = null;
    }


    public function hasTime()
    {
        return $this->time !== null;
    }


    public function getTime()
    {
        if ($this->time === null) {
            return 0;
        } else {
            return $this->time;
        }
    }


    // repeated .Log.Content contents = 2;

    public function setTime($value)
    {
        $this->time = $value;
    }


    public function clearContents()
    {
        $this->contents = null;
    }


    public function getContentsCount()
    {
        if ($this->contents === null) {
            return 0;
        } else {
            return count($this->contents);
        }
    }


    public function getContents($index)
    {
        return $this->contents[$index];
    }


    public function setContents($index, $value)
    {
        $this->contents[$index] = $value;
    }


    public function getContentsArray()
    {
        if ($this->contents === null) {
            return array();
        } else {
            return $this->contents;
        }
    }


    public function addContent($value)
    {
        $this->contents[] = $value;
    }


    public function addAllContents(array $values)
    {
        foreach ($values as $value) {
            $this->contents[] = $value;
        }
    }
}