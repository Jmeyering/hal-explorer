<?php

namespace HalExplorer\ClientAdapters;

/**
 * Provide generic methods to all the Adapters
 *
 * @abstract
 *
 * @author Jared Meyering
 */
abstract class AbstractAdapter
{

    /**
     * The HTTP Client responsible for making all of the requests.
     * The client must return PSR7 Messages to work with the system.
     * {@link http://www.php-fig.org/psr/psr-7}.
     *
     * @var mixed
     */
    protected $client;

    /**
     * Sets the concrete client onto the adapter
     *
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Returns the set client
     *
     * @param mixed
     */
    public function getClient()
    {
        return $this->client;
    }
}
