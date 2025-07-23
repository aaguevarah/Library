<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Healthcheck extends AbstractController
{
	#[Route('/healthcheck', name: 'home')]
	public function index(): Response
	{
		return new Response('Welcome to Book Microservice!');
	}
}