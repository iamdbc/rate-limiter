<?php
namespace RateLimiter\Cache;

use RateLimiter\Contracts\Cache;

class Redis implements Cache
{
    private $host = '127.0.0.1';

    private $port = 6379;

    private $password = null;

    private $database = 1;

    public function __construct($config)
    {
        foreach ($config as $key => $value) {
            if ($key == 'class') {
                continue;
            }
            $this->$key = $value;
        }
    }

    private function connect()
    {

    }
    public function get($key)
    {

    }

    public function set($key, $value, $expire = null)
    {

    }
}
