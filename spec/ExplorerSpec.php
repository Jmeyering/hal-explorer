<?php

namespace spec\HalExplorer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use HalExplorer\ClientAdapters\AdapterInterface;
use HalExplorer\Exceptions\LinkNotFoundException;

class ExplorerSpec extends ObjectBehavior
{
    public $baseUrl = "http://www.baseurl.com";

    public $methods = [
        "get",
        "put",
        "post",
        "delete",
    ];

    function it_should_set_and_retreive_a_client_adapter(AdapterInterface $clientAdapter)
    {
        $this->setAdapter($clientAdapter);
        $this->getAdapter()->shouldBeEqualTo($clientAdapter);
    }

    function it_should_set_and_retreive_a_base_url()
    {
        $this->setBaseUrl($this->baseUrl);
        $this->getBaseUrl()->shouldBeEqualTo($this->baseUrl);
    }

    function it_should_be_able_to_enter_the_entrypoint_of_the_api(AdapterInterface $adapter)
    {

        $this->setBaseUrl($this->baseUrl)->setAdapter($adapter);
        $adapter->get($this->baseUrl . "/", Argument::type("array"))->shouldBeCalled();
        $this->enter();
    }

    function it_should_be_able_to_make_all_requests_to_an_api(AdapterInterface $adapter)
    {

        $endpoint = "endpoint";
        $this->setAdapter($adapter);
        foreach ($this->methods as $method) {
            $adapter->$method(Argument::type("string"), Argument::type("array"))->shouldBeCalled();
            $this->makeRequest($method, $endpoint);
        }
    }

    function it_should_be_able_to_set_defaults_via_the_auth_closure(AdapterInterface $adapter)
    {

        $expected = [
            "query" => [
                "apiley" => 1234,
            ],
        ];

        $this->setAdapter($adapter)->setDefaults(function($options) use ($expected) {
            return $expected;
        });

        $adapter->get(Argument::type("string"), $expected)->shouldBeCalled();

        $this->makeRequest("get", "endpoint");

        //Setting an authorization header
        $expected = [
            "header" => [
                "Authorization" => "token MylongUniQueToken",
            ],
        ];

        $this->setDefaults(function($options) use ($expected) {
            return $expected;
        });

        $adapter->get(Argument::type("string"), $expected)->shouldBeCalled();

        $this->makeRequest("get", "endpoint");
    }

    function it_should_return_a_response_after_a_request(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setAdapter($adapter);
        foreach ($this->methods as $method) {
            $adapter->$method(Argument::type("string"), Argument::type("array"))
                ->willReturn($response);

            $this->makeRequest($method, "sessions")->shouldBeEqualTo($response);
        }
    }

    function it_should_be_able_to_retreive_a_relation_identified_by_a_link(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setBaseUrl($this->baseUrl);;

        $this->setAdapter($adapter);
        $adapter->get($this->baseUrl . "/relationship/456", Argument::type("array"))
            ->shouldBeCalled();

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/fixtures/halResponse.json"));

        $this->getRelation($response, "relation");
    }

    function it_should_be_able_to_create_a_relation_identified_by_a_link(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setBaseUrl($this->baseUrl);;

        $this->setAdapter($adapter);
        $adapter->post($this->baseUrl . "/relationship/456", Argument::type("array"))
            ->shouldBeCalled();

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/fixtures/halResponse.json"));

        $this->createRelation($response, "relation");
    }

    function it_should_be_able_to_update_a_relation_identified_by_a_link(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setBaseUrl($this->baseUrl);;

        $this->setAdapter($adapter);
        $adapter->put($this->baseUrl . "/relationship/456", Argument::type("array"))
            ->shouldBeCalled();

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/fixtures/halResponse.json"));

        $this->updateRelation($response, "relation");
    }

    function it_should_be_able_to_delete_a_relation_identified_by_a_link(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setBaseUrl($this->baseUrl);;

        $this->setAdapter($adapter);
        $adapter->delete($this->baseUrl . "/relationship/456", Argument::type("array"))
            ->shouldBeCalled();

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/fixtures/halResponse.json"));

        $this->deleteRelation($response, "relation");
    }

    function it_should_throw_an_exception_when_no_link_exists_and_we_ask_it_to_follow_it(
        AdapterInterface $adapter,
        ResponseInterface $response
    )
    {
        $this->setBaseUrl($this->baseUrl);;

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/fixtures/halResponse.json"));
        $this->setAdapter($adapter);

        $this->shouldThrow("HalExplorer\Exceptions\LinkNotFoundException")
            ->during("getRelation", [$response, "notalink"]);
    }

}
