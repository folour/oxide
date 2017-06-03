<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components\Response;

use Folour\Oxide\Interfaces\ResponseInterface;

/**
 * @inheritdoc
 */
class Response implements ResponseInterface
{
    /**
     * @var array
     */
    private $headers;
    /**
     * @var int
     */
    private $code;
    /**
     * @var string
     */
    private $body;

    /**
     * Response constructor
     *
     * @param string $response
     * @param array $info
     */
    public function __construct(string $response, array $info)
    {
        $this->headers  = $this->parseHeaders($response, $info);
        $this->body     = $this->parseBody($response, $info);
        $this->code     = (int)$info['http_code'];
    }

    /**
     * Returns response headers as array
     *
     * @return array
     */
    public function headers(): array
    {
        return$this->headers;
    }

    /**
     * Returns response body
     *
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * Returns HTTP response code
     *
     * @return int
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * Parse body from response
     *
     * @param string $response
     * @param array $info
     * @return string
     */
    protected function parseBody(string $response, array $info): string
    {
        $body = substr($response, $info['header_size']);

        return trim($body);
    }

    /**
     * Parse headers from response
     *
     * @param string $response
     * @param array $info
     * @return array
     */
    protected function parseHeaders(string $response, array $info): array
    {
        $raw_headers = substr($response, 0, $info['header_size']);
        $ex_headers = explode("\r\n", trim($raw_headers));
        $headers = [];

        foreach(array_slice($ex_headers, 1) as $header_line) {
            [$header_key, $header_value] = explode(': ', $header_line);

            $headers[$header_key] = $header_value;
        }

        return $headers;
    }
}