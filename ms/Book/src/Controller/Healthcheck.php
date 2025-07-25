<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

final class Healthcheck extends AbstractController
{
	#[Route('/healthcheck', name: 'home', methods: ['GET'])]
	#[OA\Tag(name: 'System')]
	#[OA\Response(
		response: Response::HTTP_OK,
		description: 'Healthcheck endpoint to verify the microservice is running',
	)]
	#[OA\Get(summary: 'Check if the Book microservice is up and running')]
	public function index(): Response
	{
		return new Response('Welcome to Book Microservice!', Response::HTTP_OK);
	}
}