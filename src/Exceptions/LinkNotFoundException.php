<?php

namespace HalExplorer\Exceptions;

use Exception;

/**
 * Exception thrown when we are trying to perform an action on a link, but
 * that link does not exist.
 *
 * @author Jared Meyering
 */
class LinkNotFoundException extends Exception{}
