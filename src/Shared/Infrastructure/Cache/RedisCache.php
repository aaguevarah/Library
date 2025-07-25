<?php

declare(strict_types=1);

namespace App\src\Shared\Infrastructure\Cache;

use App\src\Shared\Domain\Cache\CacheInterface;
use Redis;
use Throwable;

final class RedisCache implements CacheInterface
{
	private Redis $redis;
	private int   $defaultTtl;

	public function __construct(private readonly string $redisHost,
								private readonly int    $redisPort,
								private readonly int    $defaultTtlSeconds = 3600)
	{
		$this->redis      = new Redis();
		$this->defaultTtl = $defaultTtlSeconds;

		try {
			$this->redis->connect($redisHost, $redisPort);
		} catch (Throwable $e) {
		}
	}

	public function get(string $key): mixed
	{
		try {
			$value = $this->redis->get($key);

			if ($value === false) {
				return null;
			}

			return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
		} catch (Throwable $e) {
			return null;
		}
	}

	public function set(string $key, mixed $value, ?int $ttl = null): bool
	{
		try {
			$ttl             = $ttl ?? $this->defaultTtl;
			$serializedValue = json_encode($value, JSON_THROW_ON_ERROR);

			if ($serializedValue === false) {
				return false;
			}

			return $this->redis->setex($key, $ttl, $serializedValue);
		} catch (Throwable $e) {
			return false;
		}
	}

	public function has(string $key): bool
	{
		try {
			return $this->redis->exists($key) > 0;
		} catch (Throwable $e) {
			return false;
		}
	}

	public function delete(string $key): bool
	{
		try {
			return $this->redis->del($key) > 0;
		} catch (Throwable $e) {
			return false;
		}
	}
}