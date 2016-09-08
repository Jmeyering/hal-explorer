<?php

namespace spec\HalExplorer\ClientAdapters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use HalExplorer\ClientAdapters\AdapterInterface;
use HalExplorer\ClientAdapters\Adapter;
use HalExplorer\Exceptions\LinkNotFoundException;

class AdapterSpec extends ObjectBehavior
{
    function it_should_implement_the_abstract_adapter(Adapter $adapter)
    {
        $this->shouldImplement("\HalExplorer\ClientAdapters\AdapterInterface");
    }
}
