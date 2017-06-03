<?php

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components\Response;

use Folour\Oxide\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    private $headers;
    private $error;
    private $code;
    private $body;

    public function __construct()
    {

    }
}