<?php

namespace HalExplorer\Hypermedia;

use QL\UriTemplate\Expander;

/**
 * Handle the formatting of link templates into valid URI's
 * According to the
 * {@link https://tools.ietf.org/html/draft-kelly-json-hal-07#section-5.1 draft}
 *
 * @author Jared Meyering
 *
 */
class UriTemplate
{
    /**
     * Sets the template engine on the class.
     *
     */
    public function __construct()
    {
        $this->engine = new Expander();
    }
    /**
     * Templates a uri according to {@link https://tools.ietf.org/html/rfc6570 RFC6570}
     * as required by
     * {@link https://tools.ietf.org/html/draft-kelly-json-hal-07#section-5.1 the hal draft}
     *
     * @param string $template The uri template to be parsed.
     * @param array  $values   The values that will be interpolated into the
     *                         template.
     *
     * @return string
     */
    public function template($template, array $values)
    {
        return $this->engine->__invoke($template, $values);
    }
}
