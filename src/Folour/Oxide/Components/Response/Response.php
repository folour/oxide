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
     * @inheritdoc
     */
    public function __construct(string $response, array $info)
    {
        $this->headers  = $this->parseHeaders($response, $info);
        $this->body     = $this->parseBody($response, $info);
        $this->code     = (int)$info['http_code'];
    }

    /**
     * @inheritdoc
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function code(): int
    {
        return $this->code;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->body();
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
        $raw_headers = trim(substr($response, 0, $info['header_size']));

        //If follow locations enabled, headers of all redirects is returned.
        //But we need headers of last request only.
        if(strstr($raw_headers, "\r\n\r\n")) {
            $ex = explode("\r\n\r\n", $raw_headers);
            $raw_headers = end($ex);
        }

        $ex_headers = explode("\r\n", trim($raw_headers));
        $headers = [];

        foreach ($ex_headers as $header_line) {
            if(strstr($header_line, ': ')) {
                [$header_key, $header_value] = explode(': ', $header_line);

                $headers[$header_key] = $header_value;
            } else {
                $headers[] = $header_line;
            }
        }

        return $headers;
    }
}