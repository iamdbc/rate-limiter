<?php

namespace RateLimiter;

use RateLimiter\Contracts\Limiter;

class RateLimiter implements Limiter
{

    private $cacheHandler;

    public function __construct($config)
    {
        $cacheClass = "RateLimiter\\Cache\\" . $config['cacheType'];
        $this->cacheHandler = new $cacheClass($config);
    }

    public function getRemainingCount($prefix, $timeSlots = [60 => 100])
    {
        $keys = [];
        $return = [];
        // 通过$prefix查找是否有已设置的limit缓存
        foreach ($timeSlots as $timeSlot => $maxLimit) {
            $keys[$timeSlot] = $prefix . $timeSlot;
        }
        $currentCounts = $this->cacheHandler->mget($keys);
        foreach ($currentCounts as $timeSlot => $currentCount) {
            $returnCurrentCount = $this->cacheHandler->increment($keys[$timeSlot]);
            $remainingCount = $timeSlots[$timeSlot] - $returnCurrentCount;
            $return[$timeSlot] = $remainingCount;
        }
        return $return;
    }


    private function increaseCount($key)
    {

    }
}
