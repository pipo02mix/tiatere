<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @Given I am in the :arg1
     */
    public function iAmInThe($arg1)
    {
        $this->visitPath('/');
    }

    /**
     * @When I browse to :arg1 page
     */
    public function iBrowseToPage($arg1)
    {
        $this->visitPath('/es/'.preg_replace('/\s+/', '-', $arg1));
    }

    /**
     * @Then first paragraph should contain :arg1
     */
    public function firstParagraphShouldContain($arg1)
    {
        $text = $this->getSession()->getPage()->find('css', 'p.highlited-paragraph')->getText();

        expect($text)->shouldContain($arg1);
    }

    /**
     * @Then /^I see the list of the last (\d+) entries$/
     */
    public function iSeeTheListOfTheLastEntries($number)
    {
        $entries = $this->getSession()->getPage()->findAll('css', '.blog-entry');

        expect(count($entries))->toBe((int) $number);
    }

    /**
     * @Then the header should contain :arg1
     */
    public function theHeaderShouldContain($arg1)
    {
        $text = $this->getSession()->getPage()->find('css', 'h1')->getText();

        expect($text)->toBe($arg1);
    }

    /**
     * @Then nav should contain :number social links
     */
    public function navShouldContainSocialLinks($number)
    {
        $entries = $this->getSession()->getPage()->findAll('css', '.navbar .social-link');

        expect(count($entries))->toBe((int) $number);
    }
}
