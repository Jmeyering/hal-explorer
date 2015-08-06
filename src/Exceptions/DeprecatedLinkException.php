<?php

namespace HalExplorer\Exceptions;

use Exception;

/**
 * Exception thrown when we are trying to perform an action on a link, but
 * that link has been deprecated by the api..
 *
 * @author Jared Meyering
 */
class DeprecatedLinkException extends Exception{}
