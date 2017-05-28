<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide;

use Folour\Oxide\Exceptions\Exception;
use Folour\Oxide\Exceptions\UnsupportedRequestException;

class Client extends Configurator
{
    /**
     * cURL default options
     *
     * @var array
     */
    private const DEFAULT_OPTIONS = [
        CURLOPT_SSL_VERIFYHOST  => false,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_CONNECTTIMEOUT  => 2,
        CURLOPT_TIMEOUT         => 10
    ];

    /**
     * Send GET request
     *
     * @param string    $url
     * @param array     $request_data
     * @return null|string
     */
    public function get(string $url, array $request_data = null): ?string
    {
        if($request_data) {
            $request_data = http_build_query($request_data, '', '&');
        }

        $this->setOptions([
            CURLOPT_URL => implode('/?', [$url, $request_data])
        ]);

        return $this->execute();
    }

    /**
     * Fetch URL and return content
     *
     * @param string $url
     * @param array $post
     * @return mixed
     */
    public function fetch(string $url, array $post = null)
    {
        $this->setOptions([
            CURLOPT_POSTFIELDS  => http_build_query($post, '', '&'),
            CURLOPT_POST        => is_array($post),
            CURLOPT_URL         => $url
        ]);

        return $this->execute();
    }

    /**
     * Download file via cURL
     *
     * @param string $url
     * @param string $path
     * @return string|null
     * @throws \RuntimeException
     */
    public function download(string $url, string $path): ?string
    {
        if(!($file = fopen($path, 'a+b'))) {
            throw new \RuntimeException('Unable to create file resource');
        }

        $this->setOptions([
            CURLOPT_RETURNTRANSFER  => false,
            CURLOPT_FILE            => $file,
            CURLOPT_URL             => $url
        ]);

        $result = $this->execute();
        fclose($file);

        return $result;
    }

    /**
     * @param string    $method Request method
     * @param string    $url    Requested URL
     * @param array     $data   Request data
     * @return null|string
     */
    private function sendRequest(string $method, string $url, array $data = null): ?string
    {
        $data = $data ? http_build_query($data, '', '&') : null;
        $request_options = [];

        switch($method) {
            case 'PUT':
            case 'POST':
            case 'DELETE':
                $request_options[CURLOPT_POSTFIELDS] = $data;
                break;
            case 'GET':
                $url = implode('/?', [$url, $data]);
                $data = null;
                break;
        }
        if($method === 'POST') {
            $request_options[CURLOPT_POST] = true;
        }
        $request_options[CURLOPT_URL] = $url;

        return $this->execute($request_options);
    }

    /**
     * Execute request
     *
     * @param array $request_options
     * @return string|null
     * @throws Exception
     */
    private function execute(array $request_options): ?string
    {
        $handle     = curl_init();
        $options    = array_replace(self::DEFAULT_OPTIONS, $this->getOptions(), $request_options);
        curl_setopt_array($handle, $options);

        $result = curl_exec($handle);
        $error = curl_error($handle);
        curl_close($handle);

        if($error != '') {
            throw new Exception('cURL execution failed with error: "'.$error.'"');
        }

        return is_bool($result) ? null : $result;
    }
}