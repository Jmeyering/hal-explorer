<?php

namespace HalExplorer\Hypermedia;

use Psr\Http\Message\ResponseInterface;

/**
 * The Parser reads links, embedded resources, and curies from a
 * {@link http://www.php-fig.org/psr/psr-7/ PSR7 ResponseInterface}
 * This class assumes a response actually implements the
 * {@link https://tools.ietf.org/html/draft-kelly-json-hal-07 HAL Spec}
 * correctly.  Malformed/Incorrectly imeplemented responses be warned.
 *
 * @author Jared Meyering
 *
 */
class Parser
{

    /**
     * Parses a json message body and returns the result
     *
     * @param ResponseInterface $response
     *
     * @return stdClass|array stdClass if a single response, array of stdClass
     * for a collection
     */
    public function parseJsonBody(ResponseInterface $response)
    {
        return json_decode($response->getBody());
    }

    /**
     * Does the ResponseInterface have any links?
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasLinks(ResponseInterface $response)
    {
        return $this->responseHasProperty($response, "_links");
    }

    /**
     * Return the collection of links on the response.
     *
     * @param ResponseInterface $response
     *
     * @return stdClass|null
     */
    public function getLinks(ResponseInterface $response)
    {
        return $this->getResponseProperty($response, "_links");
    }

    /**
     * Informs the user if a particular link exists on a response
     *
     * @param ResponseInterface $response The response containing the links.
     * @param string            $id       The identifier of the link to find
     *
     * @return boolean
     */
    public function hasLink(ResponseInterface $response, $id)
    {
        if (!$this->hasLinks($response)) {
            return false;
        }

        return property_exists($this->getLinks($response), $id) ? true : false;
    }

    /**
     * Return a single link that is set on the response null if not present.
     * handles curie annotated link names as well as ordinary link names.
     *
     * @param ResponseInterface $response The response containing the links.
     * @param string            $id       The identifier of the link to fetch
     *
     * @return stdClass|null
     */
    public function getLink(ResponseInterface $response, $id)
    {
        if ($this->hasLink($response, $id)) {
            return $this->getLinks($response)->$id;
        }

        if (!$this->hasCuries($response)) {
            return null;
        }

        $curies = $this->getCuries($response);

        foreach ($curies as $curie) {
            if ($this->hasLink($response, $curie->name . ":" . $id)) {
                return $this->getLink($response, $curie->name . ":" . $id);
            }
        }
    }

    /**
     * Informs the user if the response has embedded resources
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasEmbeds(ResponseInterface $response)
    {
        return $this->responseHasProperty($response, "_embedded");
    }

    /**
     * Return all the embeds if they exist
     *
     * @param ResponseInterface $response The response containing the Embeds.
     *
     * @return stdClass|null
     */
    public function getEmbeds(ResponseInterface $response)
    {
        return $this->getResponseProperty($response, "_embedded");
    }

    /**
     * Informs the user if a particular embed exists on a response
     *
     * @param ResponseInterface $response The response containing the embeds.
     * @param string            $id       The identifier of the embed to find
     *
     * @return boolean
     */
    public function hasEmbed(ResponseInterface $response, $id)
    {
        if (!$this->hasEmbeds($response)) {
            return false;
        }

        return property_exists($this->getEmbeds($response), $id) ? true : false;
    }

    /**
     * Return a single embed that is set on the response null if not present.
     *
     * @param ResponseInterface $response The response containing the embeds.
     * @param string            $id       The identifier of the embed to fetch
     *
     * @return stdClass|null
     */
    public function getEmbed(ResponseInterface $response, $id)
    {
        return $this->hasEmbed($response, $id) ?
            $this->getEmbeds($response)->$id :
            null;
    }


    /**
     * Does the ResponseInterface have any curies?
     *
     * @param ResponseInterface $response
     *
     * @return boolean
     */
    public function hasCuries(ResponseInterface $response)
    {
        return ($this->hasLinks($response) && $this->hasLink($response, "curies"));
    }

    /**
     * Retreive the curies for this request
     *
     * @param ResponseInterface $response
     *
     * @return array Because curies are represented as an array of objects.
     */
    public function getCuries(ResponseInterface $response)
    {
        return $this->hasCuries($response) ?
            $this->getLink($response, "curies") :
            null;
    }

    /**
     * Informs the user if a particular curie exists on a response
     *
     * @param ResponseInterface $response The response containing the curies.
     * @param string            $name     The name of the curie to find
     *
     * @return boolean
     */
    public function hasCurie(ResponseInterface $response, $name)
    {
        return $this->getCurie($response, $name) !== null;
    }

    /**
     * Retreive a single curie by name
     *
     * @param ResponseInterface $response The response with the curies
     * @param string            $name     The name value of the curie to fetch
     *
     * @return stdClass|null
     */
    public function getCurie(ResponseInterface $response, $name)
    {
        if ($this->hasCuries($response)) {
            foreach ($this->getCuries($response) as $curie) {
                if ($curie->name === $name) {
                    return $curie;
                }
            }
        }
        return null;
    }

    /**
     * Return true if the message body has a matching json property
     *
     * @param ResponseInterface $response The response to check for the property
     * @param string            $id       The id to look for.
     *
     * @return boolean
     */
    protected function responseHasProperty(ResponseInterface $response, $id)
    {
        return property_exists($this->parseJsonBody($response), $id);
    }

    /**
     * Return a property that is set on the message body
     *
     * @param ResponseInterface $response The response to check for the property
     * @param string            $id       The id to return.
     *
     * @return stdClass|array|null
     */
    protected function getResponseProperty(ResponseInterface $response, $id)
    {
        return $this->responseHasProperty($response, $id) ?
            $this->parseJsonBody($response)->$id :
            null;
    }

}
