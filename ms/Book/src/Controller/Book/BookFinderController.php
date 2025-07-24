<?php

declare(strict_types=1);

namespace App\Controller\Book;

use App\src\Book\Book\Application\BookFinder;
use App\src\Book\Book\Domain\Exception\BookNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookFinderController extends AbstractController
{
    #[Route('/books/{id}', name: 'book_find', methods: ['GET'])]
    public function find(string $id, BookFinder $bookFinder): JsonResponse
    {
        try {
            return new JsonResponse(
				$bookFinder->find($id)
			);
        } catch (BookNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}