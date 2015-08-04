<?php

namespace HalExplorer;

use HalExplorer\Hypermedia\Parser as HypermediaParser;
use HalExplorer\ClientAdapters\ClientAdapterInterface;
use HalExplorer\Exceptions\LinkNotFoundException;
use Psr\Http\Message\ResponseInterface;

/**
 * Explorer handles all requests to the HAL Api. It follows valid HATEOAS
 * HAL links very well, and it is flexible to allow a user to craft requests
 * when a response body isn't exactly up to HAL spec.
 *
 * The explorer works exclusively with PSR7 Response/Request messages.
 * All requests return a PSR7 ResponseInterface. This should help with
 * portability and managing future changes.
 *
 * To get the parsed object (For single resources) or array (for a collection
 * of resources) from a result you can use the `getParsedBody` method.
 *
 * ```php
 * $resourceResponse = $explorer->makeRequest("get", "/resources"); // returns ResponseInterface
 * $resource = $explorer->getParsedBody($resourceResponse); // returns stdClass|array
 * ```
 *
 *
 * @author Jared Meyering
 *
 */
class Explorer
{

    /**
     * The base api url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * The adapter to be used to make all requests to the HAL api
     *
     * @var ClientAdapterInterface
     */
    protected $adapter;

    /**
     * How to add authorization to the request options
     *
     * @var Closure
     */
    protected $auth;


    /**
     * Make a request to the api. Automatically adds default options to the
     * request.
     *
     * @todo Really dry this up. This is a CRAPpy method.
     *
     * @param string $method  The method used to make the request
     * @param string $uri     The uri to hit
     * @param array  $options An array of options. We use
     *     {@link http://guzzle.readthedocs.org/en/latest/request-options.html guzzle formatted}
     *     options and require adapters to morph this data to match their
     *     specific http client implementataions
     *
     * @return ResponseInterface
     */
    public function makeRequest($method, $uri, $options = [])
    {
        //Trim leading slash if it exists
        $uri = ltrim($uri, "/");

        $parsed = parse_url($uri);

        $path = $parsed["path"];

        // If the link href has query parameters we want to split those out and
        // send them appropriately. Maybe dry this up a bit later when
        // appropriate.
        if (isset($parsed["query"])) {
            $query = [];
            parse_str($parsed["query"], $query);

            $options["query"] = isset($options["query"]) ? $options["query"] + $query : $query;
        }

        //Passed in options take presedence over default options.
        $options = array_merge_recursive($this->getDefaults(), $options);

        $response = $this->getAdapter()->$method($this->getBaseUrl() . "/" . $path, $options);

        return $response;
    }

    /**
     * Return a parsed representation of the json body of a response
     *
     * @param ResponseInterface $response
     *
     * @return array|stdClass
     */
    public function getParsedBody(ResponseInterface $response)
    {
        $parser = new HypermediaParser();

        return $parser->parseJsonBody($response);
    }

    /**
     * Follow a link using the "GET" method.
     *
     * @see followLink
     *
     * @param ResponseInterface $response The response object containing the links
     * @param string            $id       The identifier of the link to follow
     * @param array             $options  Any options to be passed to the adapter
     *
     * @return ResponseInterface
     */
    public function getRelation(ResponseInterface $response, $id, array $options = [])
    {
        return $this->followLink("get", $response, $id, $options);
    }

    /**
     * Follow a link using the "POST" method.
     *
     * @see followLink
     *
     * @param ResponseInterface $response The response object containing the links
     * @param string            $id       The identifier of the link to follow
     * @param array             $options  Any options to be passed to the adapter
     *
     * @return ResponseInterface
     */
    public function createRelation(ResponseInterface $response, $id, array $options = [])
    {
        return $this->followLink("post", $response, $id, $options);
    }

    /**
     * Follow a link using the "PUT" method.
     *
     * @see followLink
     *
     * @param ResponseInterface $response The response object containing the links
     * @param string            $id       The identifier of the link to follow
     * @param array             $options  Any options to be passed to the adapter
     *
     * @return ResponseInterface
     */
    public function updateRelation(ResponseInterface $response, $id, array $options = [])
    {
        return $this->followLink("put", $response, $id, $options);
    }

    /**
     * Follow a link using the "DELETE" method.
     *
     * @see followLink
     *
     * @param ResponseInterface $response The response object containing the links
     * @param string            $id       The identifier of the link to follow
     * @param array             $options  Any options to be passed to the adapter
     *
     * @return ResponseInterface
     */
    public function deleteRelation(ResponseInterface $response, $id, array $options = [])
    {
        return $this->followLink("delete", $response, $id, $options);
    }

    /**
     * Follow a link that lives on a Response.
     *
     * @see makeRequest
     *
     * @param string            $method   The http method to use when following
     *                                    the link.
     * @param ResponseInterface $response The response containing the links
     * @param string            $id       The identifier of the link
     * @param array             $options  Array of options that will be passed
     *                                    to the adapter
     * @throws LinkNotFoundException
     *
     * @return ResponseInterface
     */
    protected function followLink($method, ResponseInterface $response, $id, array $options = [])
    {
        $hypermediaParser = new HypermediaParser();
        $link = $hypermediaParser->getLink($response, $id);

        if ($link === null) {
            throw new LinkNotFoundException("Link \"{$id}\" not found in response");
        }

        return $this->makeRequest($method, $link->href, $options);
    }

    /**
     * Get adapter
     *
     * @return ClientAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set adapter.
     *
     * @param $adapter ClientAdapterInterface
     *
     * @return self
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Set auth
     *
     * @param Closure the closure should accept a single array paramater which
     * is the default options array for the http request.
     *
     * @return self
     */
    public function setAuth(\Closure $auth)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * Get baseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set baseUrl.
     *
     * @param string $baseUrl
     *
     * @return self
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Return the default http options.
     *
     * @return array
     */
    protected function getDefaults()
    {
        $defaults = [
            "query" => [],
        ];

        if (!empty($this->auth)) {
            $defaults = call_user_func($this->auth, $defaults);
        }

        return $defaults;
    }
}
