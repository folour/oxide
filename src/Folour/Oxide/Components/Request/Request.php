<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components\Request;

use Folour\Oxide\Components\Response\Response;
use Folour\Oxide\Interfaces\RequestInterface;
use Folour\Oxide\Interfaces\ResponseInterface;

/**
 * @inheritdoc
 */
class Request implements RequestInterface
{
    /**
     * @var array
     */
    protected $global_options;

    /**
     * Request constructor
     *
     * @param array $global_options cURL options
     */
    public function __construct(array $global_options)
    {
        $this->global_options = $global_options;
    }

    /**
     * Send request
     *
     * @param string $url
     * @param array|null $data
     * @return ResponseInterface
     */
    public final function send(string $url, array $data = null): ResponseInterface
    {
        if($data) {
            $data = $data ? http_build_query($data, '', '&') : null;
        }
        $options = $this->prepare($url, $data);
        var_dump($options);
        //$handle  = curl_init();

        return new Response;
    }

    /**
     * Prepare request
     *
     * @param   string  $url
     * @param   string  $raw_data
     * @return  array
     */
    protected function prepare(string $url, string $raw_data = null): array
    {
        //By Default is GET request without query string

        return array_replace($this->global_options, [
            CURLOPT_URL => $url
        ]);
    }
}