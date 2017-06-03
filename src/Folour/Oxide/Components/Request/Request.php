<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components\Request;

use Folour\Oxide\Exceptions\Exception;
use Folour\Oxide\Interfaces\RequestInterface;
use Folour\Oxide\Interfaces\ResponseInterface;
use Folour\Oxide\Components\Response\Response;

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
     * @throws Exception
     */
    public final function send(string $url, array $data = null): ResponseInterface
    {
        if($data !== null) {
            $data = $data ? http_build_query($data, '', '&') : null;
        }
        $options = $this->prepare($url, $data);
        $handle  = curl_init();

        curl_setopt_array($handle, $options);

        $response   = curl_exec($handle);
        $error      = curl_error($handle);
        $info       = curl_getinfo($handle);

        curl_close($handle);

        if($error !== '') {
            throw new Exception('Request failed with error: "'.$error.'"');
        }

        return new Response($response, $info);
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