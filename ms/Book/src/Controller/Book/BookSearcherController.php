<?php

declare(strict_types=1);

namespace App\Controller\Book;

use App\src\Book\Book\Application\BookSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class BookSearcherController extends AbstractController
{
	#[Route('/books', name: 'book_search', methods: ['GET'])]
	#[OA\Tag(name: 'Books')]
	#[OA\Parameter(
		name: 'page',
		description: 'The page number for pagination (defaults to 1)',
		in: 'query',
		required: false,
		schema: new OA\Schema(type: 'integer', default: 1)
	)]
	#[OA\Parameter(
		name: 'search',
		description: 'Search term to filter books by title or author',
		in: 'query',
		required: false,
		schema: new OA\Schema(type: 'string')
	)]
 #[OA\Response(
		response: Response::HTTP_OK,
		description: 'Returns a list of books with pagination',
		content: new OA\JsonContent(
			properties: [
				new OA\Property(property: 'count', type: 'integer', example: 76381),
				new OA\Property(property: 'nextPage', type: 'integer', example: 2),
				new OA\Property(property: 'previousPage', type: 'null', example: null),
				new OA\Property(property: 'currentPage', type: 'integer', example: 1),
				new OA\Property(
					property: 'results',
					type: 'array',
					items: new OA\Items(
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
									'Mentally ill -- Fiction',
									'Psychological fiction',
									'Sea stories',
									'Ship captains -- Fiction',
									'Whales -- Fiction',
									'Whaling -- Fiction',
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
				)
			]
		)
	)]
	public function search(Request $request, BookSearcher $bookSearcher): JsonResponse
	{
		$page = max(1, (int) $request->query->get('page', 1));
		$searchTerm = $request->query->get('search');
		
		return new JsonResponse(
			$bookSearcher->search($page, $searchTerm)
		);
	}
}