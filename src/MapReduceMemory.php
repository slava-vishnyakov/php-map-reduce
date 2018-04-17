<?php

namespace SlavaVishnyakov\MapReduce;

class MapReduceMemory extends Base
{
    private $values = [];

    public function __construct()
    {
        $this->values = [];
    }

    public function send($key, $value)
    {
        $key = json_encode($key);

        if(!array_key_exists($key, $this->values)) {
            $this->values[$key] = [];
        }

        $this->values[$key] []= $value;
    }

    public function next()
    {
        if(empty($this->values)) {
            return null;
        }

        $key = array_keys($this->values)[0];

        $ret = [json_decode($key), $this->values[$key]];
        unset($this->values[$key]);
        return $ret;
    }
}