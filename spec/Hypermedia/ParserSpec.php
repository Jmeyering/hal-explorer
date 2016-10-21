<?php

namespace spec\HalExplorer\Hypermedia;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use HalExplorer\Hypermedia\Parser;

class ParserSpec extends ObjectBehavior
{
    function it_should_implement_the_abstract_parser(Parser $parser)
    {
        $this->shouldImplement("\HalExplorer\Hypermedia\ParserInterface");
    }

    function it_should_know_if_a_response_has_links_or_not(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->hasLinks($response)->shouldBe(true);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));

        $this->hasLinks($response)->shouldBe(false);
    }

    function it_should_be_able_to_retreive_links_when_they_exist(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->getLinks($response)->shouldHaveType("stdClass");
        $this->getLinks($response)->shouldBeLike($responseBody->_links);
    }

    function it_should_send_null_when_no_links_exist_and_are_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getLinks($response)->shouldEqual(null);
    }

    function it_should_be_able_determine_if_a_response_has_a_particular_link(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $this->hasLink($response, "self")->shouldBe(true);
        $this->hasLink($response, "bad")->shouldBe(false);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->hasLink($response, "self")->shouldBe(false);
    }

    function it_should_be_able_to_retreive_a_single_link_when_it_exists(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->getLink($response, "self")->shouldHaveType("stdClass");
        $this->getLink($response, "self")->shouldBeLike($responseBody->_links->self);

    }

    function it_should_be_able_to_retreive_a_curie_annotated_link(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $this->getLink($response, "association")->shouldHaveType("stdClass");
        $this->getLink($response, "association")->shouldBeLike($responseBody->_links->{"doc:association"});
    }


    function it_should_send_null_when_no_link_exists_and_is_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getLink($response, "self")->shouldEqual(null);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $this->getLink($response, "non")->shouldEqual(null);
    }

    function it_should_know_if_a_response_has_embeds_or_not(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->hasEmbeds($response)->shouldBe(true);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));

        $this->hasEmbeds($response)->shouldBe(false);
    }

    function it_should_be_able_to_retreive_embeds_when_they_exist(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->getEmbeds($response)->shouldHaveType("stdClass");
        $this->getEmbeds($response)->shouldBeLike($responseBody->_embedded);
    }

    function it_should_send_null_when_no_embeds_exist_and_are_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getEmbeds($response)->shouldEqual(null);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponseNoEmbeds.json"));
        $this->getEmbeds($response)->shouldEqual(null);
    }

    function it_should_be_able_determine_if_a_response_has_a_particular_embed(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->hasEmbed($response, "relation")->shouldBe(true);
        $this->hasEmbed($response, "bad")->shouldBe(false);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->hasEmbed($response, "self")->shouldBe(false);
    }

    function it_should_be_able_to_retreive_a_single_embed_when_it_exists(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->getEmbed($response, "relation")->shouldHaveType("stdClass");
        $this->getEmbed($response, "relation")->shouldBeLike($responseBody->_embedded->relation);

    }

    function it_should_send_null_when_no_embed_exists_and_is_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getEmbed($response, "self")->shouldEqual(null);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $this->getEmbed($response, "non")->shouldEqual(null);
    }

    function it_should_know_if_a_response_has_curies_or_not(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->hasCuries($response)->shouldBe(true);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));

        $this->hasCuries($response)->shouldBe(false);
    }

    function it_should_be_able_to_retreive_curies_when_they_exist(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $curies = $responseBody->_links->curies;

        $this->getCuries($response)->shouldBeArray();
        $this->getCuries($response)->shouldBeLike($curies);
    }

    function it_should_send_null_when_no_curies_exist_and_are_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getCuries($response)->shouldEqual(null);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponseNoCuries.json"));
        $this->getCuries($response)->shouldEqual(null);
    }

    function it_should_be_able_determine_if_a_response_has_a_particular_curie(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));

        $this->hasCurie($response, "doc")->shouldBe(true);
        $this->hasCurie($response, "bad")->shouldBe(false);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));

        $this->hasCurie($response, "doc")->shouldBe(false);
    }

    function it_should_be_able_to_retreive_a_single_curie_when_it_exists(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $responseBody = json_decode(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $curies = $responseBody->_links->curies;
        $name = $curies[0]->name;

        $this->getCurie($response, $name)->shouldHaveType("stdClass");
        $this->getCurie($response, $name)->shouldBeLike($responseBody->_links->curies[0]);

    }

    function it_should_send_null_when_no_curie_exists_and_is_requested(ResponseInterface $response)
    {
        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/notHalResponse.json"));
        $this->getCurie($response, "doc")->shouldEqual(null);

        $response->getBody()->willReturn(file_get_contents(__DIR__ . "/../fixtures/halResponse.json"));
        $this->getCurie($response, "non")->shouldEqual(null);
    }
}
