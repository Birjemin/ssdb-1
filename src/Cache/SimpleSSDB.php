<?php

namespace Haolyy\Ssdb\Cache;

class SimpleSSDB extends SSDB
{
    function __construct($host, $port, $timeoutMs=2000){
        parent::__construct($host, $port, $timeoutMs);
        $this->easy();
    }
}