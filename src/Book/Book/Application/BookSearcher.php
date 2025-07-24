<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class BookSearcher
{
	private const GUTENDEX_API_URL = 'https://gutendex.com/books';

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface $logger
	) {
	}

	public function search(): array
	{
		try {
			return $this->httpClient->get(self::GUTENDEX_API_URL);
		} catch (Throwable $e) {
			$this->logger->error($e->getMessage());
			return ['total' => 0, 'books' => []];
		}
	}
}