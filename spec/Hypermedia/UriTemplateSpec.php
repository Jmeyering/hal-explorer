<?php

namespace spec\HalExplorer\Hypermedia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UriTemplateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('HalExplorer\Hypermedia\UriTemplate');
    }

    function it_should_template_a_uri()
    {
        $template = "path{/name,resource}{?value,page}";

        $this->template($template, [
            "name" => "myname",
            "resource" => "thing",
            "value" => "one",
            "page" => "two",
        ])->shouldBeEqualTo("path/myname/thing?value=one&page=two");
    }
}
