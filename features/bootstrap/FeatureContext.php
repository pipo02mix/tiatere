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
        $this->visitPath(preg_replace('/\s+/', '-', $arg1).'.php');
    }

    /**
     * @Then first paragraph should contain :arg1
     */
    public function firstParagraphShouldContain($arg1)
    {
        $text = $this->getSession()->getPage()->find('css', '.container')->getText();

        expect($text)->toBe($arg1);
    }

    /**
     * @Then /^I see the list of the last (\d+) entries$/
     */
    public function iSeeTheListOfTheLastEntries($number)
    {
        $entries = $this->getSession()->getPage()->findAll('css', '.blog-entry');

        expect(count($entries))->toBe((int) $number);
    }
}
