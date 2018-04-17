<?php

namespace SlavaVishnyakov\MapReduce;

class MapReduceProcess
{
    public $key = null;
    public $buffer = [];

    public function __construct()
    {
        $d = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];

        $pipes = [];

        $this->process = proc_open('/usr/bin/sort', $d, $pipes);
        $this->pipes = $pipes;
        $this->done = false;

        if (!is_resource($this->process)) {
            throw new \RuntimeException("Cannot start sort");
        }
        $this->wroteEof = false;
    }

    public function send($key, $value)
    {
        if($this->wroteEof) {
            throw new \RuntimeException("Cannot call send() after next()");
        }
        $line = json_encode([$key, serialize($value)], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        fwrite($this->pipes[0], $line . "\n");
    }

    public function next()
    {
        if($this->done) {
            return null;
        }

        if (!$this->wroteEof) {
            fclose($this->pipes[0]);
            $this->wroteEof = true;
        }

        do {
            $line = trim(fgets($this->pipes[1]));
            if($line) {
                [$key, $value] = json_decode($line);
                $value = unserialize($value);

                if ($key == $this->key) {
                    $this->buffer [] = $value;
                } else { // key changed -> return
                    $ret = [$this->key, $this->buffer];

                    $this->key = $key;
                    $this->buffer = [$value];

                    if ($ret[0] !== null) {
                        return $ret;
                    }
                }
            }
        } while(!feof($this->pipes[1]));

        // feof, so return last value
        $this->done = true;
        return [$this->key, $this->buffer];

    }
}