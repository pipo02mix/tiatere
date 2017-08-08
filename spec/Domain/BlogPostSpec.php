<?php

namespace spec\Domain;

use Domain\BlogPost;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlogPostSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('title', 'content');
    }

    function it_returns_the_content()
    {
        $this->content()->shouldBeEqualTo('content');
    }

    function it_returns_the_title()
    {
        $this->title()->shouldBeEqualTo('title');
    }

    function it_returns_the_created_date()
    {
        $this->createdAt()->shouldMatch('/\d+-'.date('m').'-\d+/');
    }
}
