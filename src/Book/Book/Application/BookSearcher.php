<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

use App\src\Book\Book\Domain\BookSerializer;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class BookSearcher
{
	private const string GUTENDEX_API_URL = 'https://gutendex.com/books?page=%s';

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly LoggerInterface     $logger
	)
	{
	}

	public function search(int $page = 1, ?string $searchTerm = null): array
	{
		try {
			$url = sprintf(self::GUTENDEX_API_URL, $page);
			
			if ($searchTerm !== null && $searchTerm !== '') {
				$url .= '&search=' . urlencode($searchTerm);
			}

			$response = $this->httpClient->get($url);

			$nextPage     = $this->nextPage($response);
			$previousPage = $this->previousPage($response);

			return [
				'count'        => $response['count'],
				'nextPage'     => $nextPage,
				'previousPage' => $previousPage,
				'currentPage'  => $page,
				'results'      => array_map(
					static function (array $book): array {
						return BookSerializer::serialize($book);
					},
					$response['results'] ?? []
				),
			];
		} catch (Throwable $e) {
			$this->logger->error($e->getMessage());
			return [
				'count'        => 0,
				'nextPage'     => 0,
				'previousPage' => 0,
				'currentPage'  => 0,
				'results'      => []
			];
		}
	}

	private function nextPage(array $response): ?int
	{
		$nextPage = null;

		if (isset($response['next'])) {
			$nextUrl = $response['next'];
			preg_match('/page=(\d+)/', $nextUrl, $matches);
			$nextPage = isset($matches[1]) ? (int)$matches[1] : null;
		}

		return $nextPage;
	}

	private function previousPage(array $response): ?int
	{
		$previousPage = null;

		if (isset($response['previous'])) {
			$previousUrl = $response['previous'];
			preg_match('/page=(\d+)/', $previousUrl, $matches);
			$previousPage = isset($matches[1]) ? (int)$matches[1] : null;
		}

		return $previousPage;
	}
}