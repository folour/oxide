<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide;

use Folour\Oxide\Exceptions\Exception;
use Folour\Oxide\Components\Configurator;
use Folour\Oxide\Interfaces\ResponseInterface;
use Folour\Oxide\Components\Request\GetRequest;
use Folour\Oxide\Components\Request\PutRequest;
use Folour\Oxide\Components\Request\HeadRequest;
use Folour\Oxide\Components\Request\PostRequest;
use Folour\Oxide\Components\Request\DeleteRequest;

/**
 * Simple and lightweight HTTP client for PHP 7.1
 *
 * @package Folour\Oxide
 */
class Oxide extends Configurator
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
        CURLOPT_TIMEOUT         => 10,
        CURLOPT_HEADER          => true
    ];

    /**
     * Send GET request
     *
     * @param string    $url
     * @param array     $request_data
     * @return ResponseInterface
     */
    public function get(string $url, array $request_data = null): ResponseInterface
    {
        $request = new GetRequest($this->getOptions());

        return $request->send($url, $request_data);
    }

    /**
     * Send HEAD request
     *
     * @param string    $url
     * @param array     $request_data
     * @return ResponseInterface
     */
    public function head(string $url, array $request_data): ResponseInterface
    {
        $request = new HeadRequest($this->getOptions());

        return $request->send($url, $request_data);
    }

    /**
     * Send POST request
     *
     * @param string    $url
     * @param array     $request_data
     * @return ResponseInterface
     */
    public function post(string $url, array $request_data): ResponseInterface
    {
        $request = new PostRequest($this->getOptions());

        return $request->send($url, $request_data);
    }

    /**
     * Send PUT request
     *
     * @param string    $url
     * @param array     $request_data
     * @return ResponseInterface
     */
    public function put(string $url, array $request_data): ResponseInterface
    {
        $request = new PutRequest($this->getOptions());

        return $request->send($url, $request_data);
    }

    /**
     * Send DELETE request
     *
     * @param string    $url
     * @param array     $request_data
     * @return ResponseInterface
     */
    public function delete(string $url, array $request_data): ResponseInterface
    {
        $request = new DeleteRequest($this->getOptions());

        return $request->send($url, $request_data);
    }

    /**
     * Download file via cURL
     *
     * @param string $url
     * @param string $path
     * @return string|null
     * @throws \RuntimeException
     */
//    public function download(string $url, string $path): ?string
//    {
//        if(!($file = fopen($path, 'a+b'))) {
//            throw new \RuntimeException('Unable to create file resource');
//        }
//
//        $this->setOptions([
//            CURLOPT_RETURNTRANSFER  => false,
//            CURLOPT_FILE            => $file,
//            CURLOPT_URL             => $url
//        ]);
//
//        $result = $this->execute();
//        fclose($file);
//
//        return $result;
//    }

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

    /**
     * @inheritdoc
     */
    protected function getOptions(): array
    {
        return array_replace(self::DEFAULT_OPTIONS, parent::getOptions());
    }
}