<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

use App\src\Book\Book\Domain\BookSerializer;
use App\src\Book\Book\Domain\Exception\BookNotFoundException;
use App\src\Shared\Domain\HttpClient\HttpClientException;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class BookFinder
{
	private const GUTENDEX_API_URL = 'https://gutendex.com/books';

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface     $logger
	)
	{
	}

	public function find(string $id): array
	{
		try {
			$response = $this->httpClient->get(sprintf('%s/%s', self::GUTENDEX_API_URL, $id));

			if (empty($response) || (isset($response['detail']) && $response['detail'] === 'Not found.')) {
				throw BookNotFoundException::withId($id);
			}

			return BookSerializer::serialize($response);
		} catch (Throwable $e) {
			$this->logger->critical('Error finding book by ID: ' . $e->getMessage());

			throw BookNotFoundException::withId($id);
		}
	}
}