<?php
/**
 * Error Handling Trait for OneSignal API
 * 
 * @author    DropFan <DropFan@Gmail.com>
 * @copyright 2016 DropFan.
 * @license   MIT License
 */

declare(strict_types=1);

namespace OneSignalApi\Traits;

/**
 * Trait for handling errors in OneSignal API operations
 */
trait ErrorHandlingTrait
{
    /**
     * Handle API errors
     * 
     * @param array|null $response API response
     * @return bool True if error exists
     */
    protected function hasError(?array $response): bool
    {
        return $response === null || isset($response['errors']);
    }

    /**
     * Get error message from response
     * 
     * @param array|null $response API response
     * @return string Error message
     */
    protected function getErrorMessage(?array $response): string
    {
        if ($response === null) {
            return 'No response received';
        }

        if (isset($response['errors'])) {
            if (is_array($response['errors'])) {
                return implode(', ', $response['errors']);
            }
            return (string) $response['errors'];
        }

        return '';
    }

    /**
     * Validate HTTP response code
     * 
     * @param int $httpCode HTTP response code
     * @return bool True if successful
     */
    protected function isHttpSuccess(int $httpCode): bool
    {
        return $httpCode >= 200 && $httpCode < 300;
    }

    /**
     * Get HTTP error message based on status code
     * 
     * @param int $httpCode HTTP status code
     * @return string Error message
     */
    protected function getHttpErrorMessage(int $httpCode): string
    {
        $messages = [
            400 => 'Bad Request - The request was invalid',
            401 => 'Unauthorized - API key is invalid',
            403 => 'Forbidden - The request is not allowed',
            404 => 'Not Found - The resource was not found',
            429 => 'Too Many Requests - Rate limit exceeded',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
        ];

        return $messages[$httpCode] ?? "HTTP Error {$httpCode}";
    }

    /**
     * Determine if error is retryable
     * 
     * @param int $httpCode HTTP status code
     * @return bool True if retryable
     */
    protected function isRetryableError(int $httpCode): bool
    {
        // 5xx errors and rate limiting are typically retryable
        return ($httpCode >= 500 && $httpCode < 600) || $httpCode === 429;
    }

    /**
     * Log error for debugging
     * 
     * @param string $method API method name
     * @param array|null $response API response
     * @param int $httpCode HTTP status code
     * @param string $error Error message
     * @return void
     */
    protected function logError(string $method, ?array $response, int $httpCode, string $error): void
    {
        // This can be extended to use any logging library
        error_log(sprintf(
            '[OneSignal] Error in %s: HTTP %d - %s. Response: %s',
            $method,
            $httpCode,
            $error,
            json_encode($response)
        ));
    }
}