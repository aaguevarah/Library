<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

use App\src\Book\Book\Domain\BookSerializer;
use App\src\Book\Book\Domain\Exception\BookNotFoundException;
use App\src\Shared\Domain\Cache\CacheInterface;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class BookFinder
{
	private const string GUTENDEX_API_URL = 'https://gutendex.com/books';
	private const int    CACHE_TTL        = 3600;
	private const string CACHE_KEY_PREFIX = 'book_finder_';

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface $logger,
		private readonly CacheInterface $cache
	)
	{
	}

	public function find(string $id): array
	{
		$cacheKey = self::CACHE_KEY_PREFIX . $id;
		
		$cachedData = $this->cache->get($cacheKey);
		if ($cachedData !== null) {
			$this->logger->info(sprintf('Book with ID %s retrieved from cache', $id));
			return $cachedData;
		}
		
		try {
			$response = $this->httpClient->get(sprintf('%s/%s', self::GUTENDEX_API_URL, $id));

			if (empty($response) || (isset($response['detail']) && $response['detail'] === 'Not found.')) {
				throw BookNotFoundException::withId($id);
			}

			$serializedData = BookSerializer::serialize($response);
			
			$this->cache->set($cacheKey, $serializedData, self::CACHE_TTL);
			
			return $serializedData;
		} catch (Throwable $e) {
			$this->logger->critical('Error finding book by ID: ' . $e->getMessage());

			throw BookNotFoundException::withId($id);
		}
	}
}