<?php

namespace HalExplorer\ClientAdapters;

use Psr\Http\Message\ResponseInterface;

/**
 * The built in client adapter. This adapter is tested and works with with the
 * Guzzle HTTP client specifically although it may work with others.
 *
 * ```php
 * $client = new \GuzzleHttp\Client();
 * $adapter = new Adapter();
 * $adapter->setClient($client);
 * ```
 *
 * @see AdapterInterface
 * @see AbstractAdapter
 *
 * @author Jared Meyering
 */
class Adapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * Perform a get request
     *
     * @param string $uri
     * @param array  $options All options for the request, will be passed to the
     * client
     *
     * @return ResponseInterface
     */
    public function get($uri, array $options = [])
    {
        return $this->getClient()->get($uri, $options);
    }

    /**
     * Perform a post request
     *
     * @param string $uri
     * @param array  $options All options for the request, will be passed to the
     * client
     *
     * @return ResponseInterface
     */
    public function post($uri, array $options = [])
    {
        return $this->getClient()->post($uri, $options);
    }

    /**
     * Perform a put request
     *
     * @param string $uri
     * @param array  $options All options for the request, will be passed to the
     * client
     *
     * @return ResponseInterface
     */
    public function put($uri = null, array $options = [])
    {
        return $this->getClient()->put($uri, $options);
    }

    /**
     * Perform a delete request
     *
     * @param string $uri
     * @param array  $options All options for the request, will be passed to the
     * client
     *
     * @return ResponseInterface
     */
    public function delete($uri = null, array $options = [])
    {
        return $this->getClient()->delete($uri, $options);
    }
}
