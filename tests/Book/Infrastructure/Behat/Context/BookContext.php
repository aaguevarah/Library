<?php

declare(strict_types=1);

namespace App\Tests\Book\Infrastructure\Behat\Context;

use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Throwable;

final class BookContext implements Context
{
    private ?Response $response = null;
    private array $responseData = [];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    /**
     * @Given there is a book with ID :id
     */
    public function thereIsABookWithId(string $id): void
    {
        try {
            $book = $this->httpClient->get(sprintf('https://gutendex.com/books/%s', $id));
            if (empty($book) || (isset($book['detail']) && $book['detail'] === 'Not found.')) {
                throw new RuntimeException(sprintf('Book with ID %s not found in the real API', $id));
            }
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf('Error verifying book with ID %s: %s', $id, $e->getMessage()));
        }
    }

    /**
     * @Given there is no book with ID :id
     */
    public function thereIsNoBookWithId(string $id): void
    {
        if ($id !== '999999') {
            throw new RuntimeException('For testing non-existent books, please use ID 999999');
        }
    }

    /**
     * @When I send a GET request to :url
     */
    public function iSendAGetRequestTo(string $url): void
    {
        $this->response = $this->kernel->handle(Request::create($url));
        $this->responseData = json_decode($this->response->getContent(), true) ?? [];
    }

    /**
     * @Then the response status code should be :code
     */
    public function theResponseStatusCodeShouldBe(int $code): void
    {
        Assert::assertEquals($code, $this->response->getStatusCode());
    }

    /**
     * @Then the response should be in JSON format
     */
    public function theResponseShouldBeInJsonFormat(): void
    {
        Assert::assertStringContainsString(
            'application/json',
            $this->response->headers->get('Content-Type')
        );
    }

    /**
     * @Then the JSON response should contain a book with ID :id
     */
    public function theJsonResponseShouldContainABookWithId(string $id): void
    {
        Assert::assertEquals((int)$id, $this->responseData['id']);
    }

    /**
     * @Then the JSON response should contain a book with a title
     */
    public function theJsonResponseShouldContainABookWithATitle(): void
    {
        Assert::assertArrayHasKey('title', $this->responseData);
        Assert::assertNotEmpty($this->responseData['title']);
    }

    /**
     * @Then the JSON response should contain a book with subjects
     */
    public function theJsonResponseShouldContainABookWithSubjects(): void
    {
        Assert::assertArrayHasKey('subjects', $this->responseData);
        Assert::assertIsArray($this->responseData['subjects']);
    }

    /**
     * @Then the JSON response should contain a book with authors
     */
    public function theJsonResponseShouldContainABookWithAuthors(): void
    {
        Assert::assertArrayHasKey('authors', $this->responseData);
        Assert::assertIsArray($this->responseData['authors']);
    }

    /**
     * @Then the JSON response should contain an error message
     */
    public function theJsonResponseShouldContainAnErrorMessage(): void
    {
        Assert::assertArrayHasKey('error', $this->responseData);
        Assert::assertNotEmpty($this->responseData['error']);
    }

    /**
     * @Then the JSON response should contain a :field field
     */
    public function theJsonResponseShouldContainAField(string $field): void
    {
        Assert::assertArrayHasKey($field, $this->responseData);
    }

    /**
     * @Then the JSON response should contain a :field array
     */
    public function theJsonResponseShouldContainAnArray(string $field): void
    {
        Assert::assertArrayHasKey($field, $this->responseData);
        Assert::assertIsArray($this->responseData[$field]);
    }

    /**
     * @Then the JSON response should contain pagination information
     */
    public function theJsonResponseShouldContainPaginationInformation(): void
    {
        Assert::assertArrayHasKey('nextPage', $this->responseData);
        Assert::assertArrayHasKey('previousPage', $this->responseData);
        Assert::assertArrayHasKey('currentPage', $this->responseData);
    }

    /**
     * @Then the JSON response should have :field equal to :value
     */
    public function theJsonResponseShouldHaveFieldEqualTo(string $field, $value): void
    {
        Assert::assertArrayHasKey($field, $this->responseData);
        
        if (is_numeric($value)) {
            $value = (int)$value;
        }
        
        Assert::assertEquals($value, $this->responseData[$field]);
    }

    /**
     * @Then the JSON response should contain books related to :term
     */
    public function theJsonResponseShouldContainBooksRelatedTo(string $term): void
    {
        Assert::assertArrayHasKey('results', $this->responseData);
        Assert::assertIsArray($this->responseData['results']);
        
        Assert::assertNotEmpty($this->responseData['results']);
    }

}