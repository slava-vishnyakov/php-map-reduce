<?php

namespace SlavaVishnyakov\MapReduce;

class Base
{
    public function iter()
    {
        while (($pair = $this->next()) !== null) {
            yield $pair[0] => $pair[1];
        }
    }
}