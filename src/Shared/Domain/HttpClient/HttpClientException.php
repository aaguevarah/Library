<?php

declare(strict_types=1);

namespace App\src\Shared\Domain\HttpClient;

use Exception;
use Throwable;

class HttpClientException extends Exception
{
	private ?int   $statusCode;
	private ?array $responseData;

	public function __construct(string     $message,
								?int       $statusCode = null,
								?array     $responseData = null,
								int        $code = 0,
								?Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->statusCode   = $statusCode;
		$this->responseData = $responseData;
	}

	public function getStatusCode(): ?int
	{
		return $this->statusCode;
	}

	public function getResponseData(): ?array
	{
		return $this->responseData;
	}

	public static function connectionError(string $message, ?Throwable $previous = null): self
	{
		return new self("Connection error: $message",
						null,
						null,
						0,
						$previous);
	}

	public static function httpError(int $statusCode, ?array $responseData = null, ?Throwable $previous = null): self
	{
		return new self("HTTP error: Status code $statusCode",
						$statusCode,
						$responseData,
						0,
						$previous);
	}

	public static function timeoutError(?Throwable $previous = null): self
	{
		return new self("Request timed out",
						null,
						null,
						0,
						$previous);
	}

	public static function invalidResponse(?array $responseData = null, ?Throwable $previous = null): self
	{
		return new self("Invalid response received",
						null,
						$responseData,
						0,
						$previous);
	}
}