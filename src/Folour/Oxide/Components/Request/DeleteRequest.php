<?php declare(strict_types=1);

/**
 * @author  Vadim Bova <folour@gmail.com>
 * @link    https://bova.io
 */

namespace Folour\Oxide\Components\Request;

use Folour\Oxide\Interfaces\RequestInterface;

/**
 * DELETE request
 *
 * @package Folour\Oxide
 */
class DeleteRequest extends PostRequest implements RequestInterface
{
    /**
     * @inheritdoc
     */
    protected function prepare(string $url, string $raw_data = null): array
    {
        return array_replace(parent::prepare($url, $raw_data), [
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        ]);
    }
}