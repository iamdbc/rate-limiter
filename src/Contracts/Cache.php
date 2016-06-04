<?php

namespace RateLimiter\Contracts;

interface Cache
{
	public function get($key);

	public function set($key, $value, $expire = null);
}
