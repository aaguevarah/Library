<?php

declare(strict_types=1);

namespace App\Tests\Book\Domain;

use App\Tests\Shared\Domain\MotherCreator;

class BookMother
{

	public static function create(): array
	{
		$faker = MotherCreator::random();

		$authorsCount = $faker->numberBetween(1, 3);

		$authors = [];
		for ($i = 0; $i < $authorsCount; $i++) {
			$birthYear = $faker->numberBetween(1800, 1950);
			$deathYear = $faker->boolean(70) ? $faker->numberBetween($birthYear + 20, 2020) : null;

			$authors[] = [
				'name'       => $faker->name(),
				'birth_year' => $birthYear,
				'death_year' => $deathYear,
			];
		}

		$subjectsCount = $faker->numberBetween(3, 10);
		$subjects      = [];
		for ($i = 0; $i < $subjectsCount; $i++) {
			$subjects[] = $faker->sentence(20);
		}

		return [
			'id'       => $faker->unique()->numberBetween(1, 9999),
			'title'    => $faker->sentence(20),
			'subjects' => $subjects,
			'authors'  => $authors,
		];
	}

	public static function list(): array
	{
		$faker = MotherCreator::random();

		$authorsCount = $faker->numberBetween(1, 10);

		$list = [];
		for ($i = 0; $i < $authorsCount; $i++) {
			$list[] = self::create();
		}

		return $list;
	}
}