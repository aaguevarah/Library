<?php

namespace App\Tests\Shared\Infrastructure\HttpClient;

use App\Tests\Book\Domain\BookMother;

class ResponseMother
{
	public static function single(): array
	{
		return BookMother::create();
	}

	public static function list(): array
	{
		$list = BookMother::list();

		return [
			'count'    => count($list),
			'next'     => null,
			'previous' => null,
			'results'  => $list,
		];
	}
}