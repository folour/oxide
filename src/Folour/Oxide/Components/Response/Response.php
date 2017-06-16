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
        $ex_headers = explode("\r\n", $this->getLastHeaders($response, $info));
        $headers    = [];

        foreach (array_slice($ex_headers, 1) as $k => $line) {
            [$headers[0][$k], $headers[1][$k]] = explode(': ', $line);
        }

        return array_combine($headers[0], $headers[1]);
    }


    /**
     * @param string $response
     * @param array $info
     * @return string
     */
    protected function getLastHeaders(string $response, array $info): string
    {
        $raw_headers    = trim(substr($response, 0, $info['header_size']));
        $parts          = explode("\r\n\r\n", $raw_headers);

        return trim(end($parts));
    }
}