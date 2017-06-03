<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Interfaces;

/**
 * Internal Request
 * 
 * @package Folour\Oxide
 */
interface RequestInterface
{
    /**
     * Constructor for Request
     * 
     * @param array $global_options Array with global cURL options
     */
    public function __construct(array $global_options);

    /**
     * Send prepared request
     * 
     * @param string $url
     * @param array $data
     * @return ResponseInterface
     */
    public function send(string $url, array $data): ResponseInterface;
}