<?php

namespace spec\Tiatere\Domain;

use Tiatere\Domain\BlogPost;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlogPostSpec extends ObjectBehavior
{
    private $time;

    function let()
    {
        $this->time = new \DateTime();
        $this->beConstructedWith('title', 'content', $this->time);
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
        $this->createdAt()->shouldBeEqualTo($this->time);
    }
}
