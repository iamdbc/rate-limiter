<?php

namespace RateLimiter;

use Contracts\Limiter;

class RateLimiter implements Limiter
{
    private $prefix;
    private $secondTimestamp;
    private $miniteTimestamp;
    private $hourTimstamp;

    private $cacheHandler;

    public function __construct($config)
    {
        $cacheClass= "RateLimiter\\Cache\\" + $config['type'];
        $this->cacheHandler = new $cacheClass;
    }

    public function getRemainingCount($prefix, $timeSlots = [60], $maxLimit = [0])
    {
        echo 123;
        // 通过$prefix查找是否有已设置的limit缓存

        // 如果有，计数加1

        // 如果没，创建缓存，计数初始为1
    }

    private function getLimitCache($key)
    {

    }

    private function increaseCount($key)
    {

    }
}
