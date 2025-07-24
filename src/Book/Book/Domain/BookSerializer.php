<?php

declare(strict_types=1);

namespace App\src\Book\Book\Domain;

final class BookSerializer
{
	public static function serialize(array $book): array
	{
		return [
			'id' => $book['id'] ?? null,
			'title' => $book['title'] ?? null,
			'subjects' => $book['subjects'] ?? [],
			'authors' => $book['authors'] ?? [],
		];
	}
}