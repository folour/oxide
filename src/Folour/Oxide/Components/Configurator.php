<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components;

/**
 * Abstract configuration class for Oxide client
 *
 * @package Folour\Oxide
 */
abstract class Configurator
{
    /**
     * @var array Options array buffer
     */
    private $options = [];

    /**
     * Set file for read and write cookies
     *
     * @param string $cookies_file
     * @return self
     */
    public function setCookiesFile(string $cookies_file): self
    {
        return $this->setOptions([
            CURLOPT_COOKIEFILE  => $cookies_file,
            CURLOPT_COOKIEJAR   => $cookies_file
        ]);
    }

    /**
     * Set request cookies
     *
     * @param array $cookies
     * @return self
     */
    public function setCookies(array $cookies): self
    {
        $cookie_string = '';
        array_walk($cookies, function($value, $key) use(&$cookie_string) {
            $cookie_string .= "$key=$value;";
        });

        return $this->setOptions([
            CURLOPT_COOKIE => rtrim($cookie_string, ';')
        ]);
    }

    /**
     * Set request headers
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        return $this->setOptions([
            CURLOPT_HTTPHEADER => $headers
        ]);
    }

    /**
     * Set proxy server
     * If proxy server required authorization - provide proxy in format:
     *     user:password@proxy.addr:port
     *
     * @param string $proxy
     * @return self
     */
    public function setProxy(string $proxy): self
    {
        if(strstr($proxy, '@')) {
            [$auth, $proxy] = explode('@', $proxy);
        }

        return $this->setOptions([
            CURLOPT_PROXYUSERPWD    => $auth ?? null,
            CURLOPT_PROXY           => $proxy
        ]);
    }

    /**
     * Add cURL options
     *
     * @param array $options
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = array_replace($this->options, $options);

        return $this;
    }

    /**
     * Get options array
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return $this->options;
    }
}