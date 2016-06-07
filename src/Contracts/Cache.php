<?php

namespace RateLimiter\Contracts;

interface Cache
{
	public function get($key);

	public function mget(array $keys);

	public function set($key, $value, $expire = null);

	public function increment($key, $value = 1, $expire = null);
}
