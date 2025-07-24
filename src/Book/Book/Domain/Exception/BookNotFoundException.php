<?php

declare(strict_types=1);

namespace App\src\Book\Book\Domain\Exception;

use Exception;

final class BookNotFoundException extends Exception
{
	public function __construct(string $bookId, string $message = "", int $code = 0, ?Exception $previous = null)
	{
		$message = $message ?: sprintf('Book with ID %s not found', $bookId);
		parent::__construct($message, $code, $previous);
	}

	public static function withId(string $bookId): self
	{
		return new self($bookId);
	}
}