<?php

declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\src\Book\Book\Application\BookSearcher;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use App\Tests\Shared\Infrastructure\HttpClient\ResponseMother;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BookSearcherTest extends KernelTestCase
{

	/**
	 * @throws Exception
	 */
	public function testBookFinder(): void
	{
		self::bootKernel();

		$container = self::getContainer();

		$httpClientInterface = $this->createMock(HttpClientInterface::class);

		$response = ResponseMother::list();

		$httpClientInterface->expects($this->once())
			->method('get')
			->withAnyParameters()
			->willReturn(
				value: $response
			);

		$container->set(HttpClientInterface::class, $httpClientInterface);

		/** @var BookSearcher $bookSearcher */
		$bookSearcher = $container->get(BookSearcher::class);
		$data         = $bookSearcher->search();

		self::assertIsArray(actual: $data);
		self::assertCount(expectedCount: $data['count'], haystack: $response['results']);
	}
}