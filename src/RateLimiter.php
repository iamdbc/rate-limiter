<?php

namespace RateLimiter;

use RateLimiter\Contracts\Limiter;

class RateLimiter implements Limiter
{
    private $cacheHandler;

    private $setHeader = false;

    public function __construct($config)
    {
        $cacheClass = "RateLimiter\\Cache\\" . $config['cacheType'];
        $this->cacheHandler = new $cacheClass($config);

        if (!empty($config['setHeader'])) {
            $this->setHeader = true;
        }
    }

    public function getRemainingCount($prefix, $timeSlotsMaxLimits = [60 => 100])
    {
        $redisKeys = [];    // 各个统计时间段的redis缓存的key
        $timeSlots = [];    // 间隔数组
        $maxLimits = [];    // 最大限制数组

        $remainingCounts    = [];    // 返回数组
        // 通过$prefix查找是否有已设置的limit缓存
        foreach ($timeSlotsMaxLimits as $timeSlot => $maxLimit) {
            $redisKeys[] = $prefix . ':' . $timeSlot;
            $maxLimits[] = $maxLimit;
            $timeSlots[] = $timeSlot;
        }
        // 获取当前各时段的请求量
        $currentCounts = $this->cacheHandler->mget($redisKeys);
        // 请求量+1，获取各个统计维度的剩余量
        foreach ($currentCounts as $k => $currentCount) {
            $returnCurrentCount = $this->cacheHandler->increment($redisKeys[$k], 1, $timeSlots[$k]);
            $remainingCounts[$k] = $maxLimits[$k] - $returnCurrentCount;
        }

        if ($this->setHeader) {
            $this->setRateLimitHeader($remainingCounts, $maxLimits);
        }

        return $remainingCounts;
    }

    public function setRateLimitHeader($remainingCounts, $maxLimits)
    {
        header('X-RateLimit-Limit: ' . $maxLimits[0]);
        header('X-RateLimit-Remaining: ' . $remainingCounts[0]);
    }
}
