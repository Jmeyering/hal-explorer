<?php

namespace HalExplorer\ClientAdapters;

use Psr\Http\Message\ResponseInterface;

/**
 * Adapter interfaces allow for the use of any particular HTTP Client within the
 * Explorer. The adapter must be able to translate `get`, `post`, `put`, and
 * `delete` requests to their particular client.
 *
 * Implementations of the AdapterInterface must implement all of these methods.
 * When used, the implementation will receive a standardized set of data.
 * The method will receive `(string $uri, array $options)`.
 *
 * The $uri will be a fully qualified uri to the resource.
 *
 * The $options array will contain all additional information needed to
 * process the request. The options array will always come formatted in the
 * style used by {@link http://guzzle.readthedocs.org/en/latest/request-options.html guzzle}
 *
 *
 * @author Jared Meyering
 */
interface AdapterInterface
{
    /**
     * Set the actual HTTP client onto the adapter
     *
     * @param mixed
     *
     * @return void
     */
    public function setClient($client);

    /**
     * Retreive the HTTP Client from the adapter
     *
     * @return mixed
     */
    public function getClient();

    /**
     * Make a get request
     *
     * @param string $uri     The uri that the client will hit
     * @param array  $options All options that should be handled by the client.
     *
     * @return ResponseInterface
     */
    public function get($uri, array $options = []);

    /**
     * Make a post request
     *
     * @param string $uri     The uri that the client will hit
     * @param array  $options All options that should be handled by the client.
     *
     * @return ResponseInterface
     */
    public function post($url, array $options = []);

    /**
     * Make a put request
     *
     * @param string $uri     The uri that the client will hit
     * @param array  $options All options that should be handled by the client.
     *
     * @return ResponseInterface
     */
    public function put($uri, array $options = []);

    /**
     * Make a delete request
     *
     * @param string $uri     The uri that the client will hit
     * @param array  $options All options that should be handled by the client.
     *
     * @return ResponseInterface
     */
    public function delete($uri, array $options = []);
}

