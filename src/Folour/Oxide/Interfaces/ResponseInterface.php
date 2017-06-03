<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Interfaces;

/**
 * Oxide http client Response interface
 *
 * @package Folour\Oxide
 */
interface ResponseInterface
{
    /**
     * @param string $response
     * @param array $info
     */
    public function __construct(string $response, array $info);

    /**
     * Returns response headers as array
     *
     * @return array
     */
    public function headers(): array;

    /**
     * Returns response body
     *
     * @return string
     */
    public function body(): string;

    /**
     * Returns HTTP response code
     *
     * @return int
     */
    public function code(): int;

    /**
     * Returns response body
     *
     * @return string
     */
    public function __toString(): string;
}