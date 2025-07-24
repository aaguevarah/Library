<?php

declare(strict_types=1);

namespace App\src\Shared\Infrastructure\HttpClient;

use App\src\Shared\Domain\HttpClient\HttpClientException;
use App\src\Shared\Domain\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

final class SymfonyHttpClient implements HttpClientInterface
{
	public function __construct(
		private readonly SymfonyHttpClientInterface $httpClient
	)
	{
	}

	public function request(
		string $url,
		string $method,
		array  $headers = [],
		mixed  $body = null,
		array  $options = []
	): array
	{
		try {
			$requestOptions = $this->prepareRequestOptions($headers, $body, $options);
			$response       = $this->httpClient->request($method, $url, $requestOptions);

			return $this->processResponse($response);
		} catch (TransportException $e) {
			throw HttpClientException::connectionError($e->getMessage(), $e);
		} catch (ClientException|RedirectionException|ServerException $e) {
			$statusCode   = $e->getResponse()->getStatusCode();
			$responseData = $this->safelyDecodeResponse($e->getResponse());

			throw HttpClientException::httpError($statusCode, $responseData, $e);
		} catch (Throwable $e) {
			throw new HttpClientException($e->getMessage(), null, null, 0, $e);
		}
	}

	public function get(string $url, array $headers = [], array $options = []): array
	{
		return $this->request($url, 'GET', $headers, null, $options);
	}

	public function post(string $url, mixed $body, array $headers = [], array $options = []): array
	{
		return $this->request($url, 'POST', $headers, $body, $options);
	}

	public function put(string $url, mixed $body, array $headers = [], array $options = []): array
	{
		return $this->request($url, 'PUT', $headers, $body, $options);
	}

	public function delete(string $url, array $headers = [], array $options = []): array
	{
		return $this->request($url, 'DELETE', $headers, null, $options);
	}

	private function prepareRequestOptions(array $headers, mixed $body, array $options): array
	{
		$requestOptions = $options;

		if (!empty($headers)) {
			$requestOptions['headers'] = $headers;
		}

		if ($body !== null) {
			if (is_array($body)) {
				if (
					!isset($headers['Content-Type']) ||
					str_contains($headers['Content-Type'] ?? '', 'application/json')
				) {
					$requestOptions['json'] = $body;
				} else {
					$requestOptions['body'] = $body;
				}
			} else {
				$requestOptions['body'] = $body;
			}
		}

		return $requestOptions;
	}

	private function processResponse(ResponseInterface $response): array
	{
		$statusCode = $response->getStatusCode();

		if ($statusCode >= 400) {
			$responseData = $this->safelyDecodeResponse($response);
			throw HttpClientException::httpError($statusCode, $responseData);
		}

		return $this->safelyDecodeResponse($response);
	}

	private function safelyDecodeResponse(ResponseInterface $response): array
	{
		try {
			$content = $response->getContent(false);

			if (empty($content)) {
				return [];
			}

			$contentType = $response->getHeaders()['content-type'][0] ?? '';

			if (str_contains($contentType, 'application/json')) {
				$decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

				if (!is_array($decoded)) {
					return ['data' => $decoded];
				}

				return $decoded;
			}

			return ['data' => $content];
		} catch (\JsonException $e) {
			return ['data' => $response->getContent(false)];
		} catch (Throwable $e) {
			throw HttpClientException::invalidResponse(null, $e);
		}
	}
}