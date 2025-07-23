<?php

declare(strict_types=1);

namespace App\src\Book\Book\Application;

final class BookSearcher
{
	public function search(): array
	{
		return ['total' => 0, 'books' => []];
	}
}