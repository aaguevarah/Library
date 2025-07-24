<?php

declare(strict_types=1);

namespace App\Controller\Book;

use App\src\Book\Book\Application\BookSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class BookSearcherController extends AbstractController
{
	#[Route('/books', name: 'book_search', methods: ['GET'])]
	public function search(BookSearcher $bookSearcher): JsonResponse
	{
		return new JsonResponse(
			$bookSearcher->search()
		);
	}
}