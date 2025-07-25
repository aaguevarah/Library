<?php

declare(strict_types=1);

namespace App\Controller\Book;

use App\src\Book\Book\Application\BookFinder;
use App\src\Book\Book\Domain\Exception\BookNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class BookFinderController extends AbstractController
{
    #[Route('/books/{id}', name: 'book_find', methods: ['GET'])]
    #[OA\Tag(name: 'Books')]
    #[OA\Parameter(
        name: 'id',
        description: 'The book unique identifier',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns the book details',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 2701),
                new OA\Property(property: 'title', type: 'string', example: 'Moby Dick; Or, The Whale'),
                new OA\Property(
                    property: 'subjects',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: [
                        'Adventure stories',
                        'Ahab, Captain (Fictitious character) -- Fiction',
                        'Sea stories',
                        'Whaling ships -- Fiction'
                    ]
                ),
                new OA\Property(
                    property: 'authors',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'name', type: 'string', example: 'Melville, Herman'),
                            new OA\Property(property: 'birth_year', type: 'integer', example: 1819),
                            new OA\Property(property: 'death_year', type: 'integer', example: 1891)
                        ],
                        type: 'object'
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Book not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Book with id 123 not found')
            ]
        )
    )]
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