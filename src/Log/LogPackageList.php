<?php namespace Aliyun\SLS\Log;

use Aliyun\SLS\Exception;
use Aliyun\SLS\Protobuf;

class LogPackageList
{

    private $_unknown;

    private $packages_ = null;


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
            //var_dump("LogPackageList: Found $field type " . Protobuf::get_wiretype($wire) . " $limit bytes left");
            switch ($field) {
                case 1:
                    assert('$wire == 2');
                    $len = Protobuf::read_varint($fp, $limit);
                    if ($len === false) {
                        throw new Exception('Protobuf::read_varint returned false');
                    }
                    $limit -= $len;
                    $this->packages_[] = new LogPackage($fp, $len);
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
        if ( ! is_null($this->packages_)) {
            foreach ($this->packages_ as $v) {
                fwrite($fp, "\x0a");
                Protobuf::write_varint($fp, $v->size()); // message
                $v->write($fp);
            }
        }
    }


    public function size()
    {
        $size = 0;
        if ( ! is_null($this->packages_)) {
            foreach ($this->packages_ as $v) {
                $l = $v->size();
                $size += 1 + Protobuf::size_varint($l) + $l;
            }
        }

        return $size;
    }


    // repeated .LogPackage packages = 1;

    public function __toString()
    {
        return '' . Protobuf::toString('unknown', $this->_unknown) . Protobuf::toString('packages_', $this->packages_);
    }


    public function clearPackages()
    {
        $this->packages_ = null;
    }


    public function getPackagesCount()
    {
        if ($this->packages_ === null) {
            return 0;
        } else {
            return count($this->packages_);
        }
    }


    public function getPackages($index)
    {
        return $this->packages_[$index];
    }


    public function setPackages($index, $value)
    {
        $this->packages_[$index] = $value;
    }


    public function getPackagesArray()
    {
        if ($this->packages_ === null) {
            return array();
        } else {
            return $this->packages_;
        }
    }


    public function addPackages($value)
    {
        $this->packages_[] = $value;
    }


    public function addAllPackages(array $values)
    {
        foreach ($values as $value) {
            $this->packages_[] = $value;
        }
    }
}