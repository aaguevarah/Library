<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

use App\src\Book\Book\Domain\BookSerializer;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class BookSearcher
{
	private const GUTENDEX_API_URL = 'https://gutendex.com/books';

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface     $logger
	)
	{
	}

	public function search(): array
	{
		try {
			$response = $this->httpClient->get(self::GUTENDEX_API_URL);
			return [
				'count'    => $response['count'] ?? 0,
				'next'     => $response['next'] ?? null,
				'previous' => $response['previous'] ?? null,
				'results'  => array_map(
					static function (array $book): array {
						return BookSerializer::serialize($book);
					},
					$response['results'] ?? []
				),
			];

		} catch (Throwable $e) {
			$this->logger->error($e->getMessage());
			return ['count' => 0, 'results' => []];
		}
	}
}