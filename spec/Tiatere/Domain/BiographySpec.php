<?php

namespace spec\Tiatere\Domain;

use Tiatere\Domain\Biography;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BiographySpec extends ObjectBehavior
{
    function it_get_personal_information_about_me()
    {
        $this->name()->shouldReturn('Fernando Ripoll Lafuente');
    }
}
