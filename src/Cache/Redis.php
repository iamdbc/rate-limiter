<?php
namespace RateLimiter\Cache;

use RateLimiter\Contracts\Cache;

class Redis implements Cache
{
    private $host = '127.0.0.1';

    private $port = 6379;

    private $password = null;

    private $database = 1;

    private $redis = null;

    private $timeOut = 2;   // 超时时间（秒）

    private $prefix = 'limiter:';

    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            if ($key == 'cacheType') {
                continue;
            }
            $this->$key = $value;
        }
    }

    private function connect()
    {
        if ($this->redis) {
            return $this->redis;
        }

        if (!($redis = new Redis())) {
            $redis->connect($this->host, $this->port, $this->timeOut);
            if ($this->password) {
                $redis->auth($this->password);
            }
            $redis->select($this->database);
            return $this->redis = $redis;
        } else {
            throw new Exception("redis connect failed");
        }
    }

    public function get($key)
    {
        return $this->connect()->get($prefix . $key);
    }

    public function mget(array $keys)
    {
        $prefixKeys = array_map(function ($key) {
            return $this->prefix . $key;
        }, $keys);

        return $this->connect()->mget($prefixKeys);
    }

    public function set($key, $value, $expire = null)
    {
        // setnx?
        return $this->connect()->set($this->prefix . $key, $value, (int)$expire);
    }

    public function increment($key, $value = 1)
    {
        return $this->connect()->incrBy($this->prefix . $key, $value);
    }
}
