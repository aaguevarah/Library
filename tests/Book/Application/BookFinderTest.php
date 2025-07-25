<?php

declare(strict_types=1);

namespace App\Tests\Book\Application;

use App\src\Book\Book\Application\BookFinder;
use App\src\Book\Book\Domain\Exception\BookNotFoundException;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use App\Tests\Shared\Infrastructure\HttpClient\ResponseMother;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class BookFinderTest extends KernelTestCase
{

	/**
	 * @throws Exception
	 * @throws BookNotFoundException
	 */
	public function testBookFinder(): void
	{
		self::bootKernel();

		$container = self::getContainer();

		$httpClientInterface = $this->createMock(HttpClientInterface::class);

		$response = ResponseMother::single();
		$id       = $response['id'];

		$httpClientInterface->expects($this->once())
			->method('get')
			->withAnyParameters()
			->willReturn(
				value: $response
			);

		$container->set(HttpClientInterface::class, $httpClientInterface);

		/** @var BookFinder $bookFinder */
		$bookFinder = $container->get(BookFinder::class);
		$book       = $bookFinder->find((string)$id);

		self::assertIsArray(actual: $book);
		self::assertEquals(expected: $id, actual: $book['id']);
		self::assertCount(expectedCount: count(value: $response['subjects']), haystack: $book['subjects']);
		self::assertCount(expectedCount: count(value: $response['authors']), haystack: $book['authors']);
	}
}