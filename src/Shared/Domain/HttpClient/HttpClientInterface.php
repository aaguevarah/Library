<?php

declare(strict_types=1);

namespace App\src\Shared\Domain\HttpClient;

interface HttpClientInterface
{
    public function request(
        string $url,
        string $method,
        array $headers = [],
        mixed $body = null,
        array $options = []
    ): array;

    public function get(string $url, array $headers = [], array $options = []): array;

    public function post(string $url, mixed $body, array $headers = [], array $options = []): array;

    public function put(string $url, mixed $body, array $headers = [], array $options = []): array;

    public function delete(string $url, array $headers = [], array $options = []): array;
}