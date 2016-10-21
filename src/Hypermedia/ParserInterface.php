<?php

namespace HalExplorer\Hypermedia;

use Psr\Http\Message\ResponseInterface;

/**
 * The ParserInterface provides the necessary methods for fetching linked,
 * embedded, or curie information from a {@see ResponseInterface} object.
 *
 * @author Jared Meyering
 *
 */
interface ParserInterface
{
    /**
     * Does the ResponseInterface have any links?
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasLinks(ResponseInterface $response);

    /**
     * Return the collection of links on the response.
     *
     * @param ResponseInterface $response
     *
     * @return \stdClass|null
     */
    public function getLinks(ResponseInterface $response);

    /**
     * Informs the user if a particular link exists on a response
     *
     * @param ResponseInterface $response The response containing the links.
     * @param string            $id       The identifier of the link to find
     *
     * @return boolean
     */
    public function hasLink(ResponseInterface $response, $id);

    /**
     * Return a single link that is set on the response null if not present.
     * handles curie annotated link names as well as ordinary link names.
     *
     * @param ResponseInterface $response The response containing the links.
     * @param string            $id       The identifier of the link to fetch
     *
     * @return \stdClass|null
     */
    public function getLink(ResponseInterface $response, $id);

    /**
     * Informs the user if the response has embedded resources
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasEmbeds(ResponseInterface $response);

    /**
     * Return all the embeds if they exist
     *
     * @param ResponseInterface $response The response containing the Embeds.
     *
     * @return \stdClass|null
     */
    public function getEmbeds(ResponseInterface $response);

    /**
     * Informs the user if a particular embed exists on a response
     *
     * @param ResponseInterface $response The response containing the embeds.
     * @param string            $id       The identifier of the embed to find
     *
     * @return boolean
     */
    public function hasEmbed(ResponseInterface $response, $id);

    /**
     * Return a single embed that is set on the response null if not present.
     *
     * @param ResponseInterface $response The response containing the embeds.
     * @param string            $id       The identifier of the embed to fetch
     *
     * @return \stdClass|null
     */
    public function getEmbed(ResponseInterface $response, $id);


    /**
     * Does the ResponseInterface have any curies?
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasCuries(ResponseInterface $response);

    /**
     * Retreive the curies for this request
     *
     * @param ResponseInterface $response
     *
     * @return array Because curies are represented as an array of objects.
     */
    public function getCuries(ResponseInterface $response);

    /**
     * Informs the user if a particular curie exists on a response
     *
     * @param ResponseInterface $response The response containing the curies.
     * @param string            $name     The name of the curie to find
     *
     * @return boolean
     */
    public function hasCurie(ResponseInterface $response, $name);

    /**
     * Retreive a single curie by name
     *
     * @param ResponseInterface $response The response with the curies
     * @param string            $name     The name value of the curie to fetch
     *
     * @return \stdClass|null
     */
    public function getCurie(ResponseInterface $response, $name);
}
