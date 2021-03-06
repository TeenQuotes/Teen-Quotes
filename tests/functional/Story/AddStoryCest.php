<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class AddStoryCest
{
    /**
     * The logged in user.
     *
     * @var \TeenQuotes\Users\Models\User
     */
    private $user;

    public function _before(FunctionalTester $I)
    {
        // Create some published quotes and some stories
        $I->createSomePublishedQuotes();
        $I->createSomeStories();

        // Create a new user
        $this->user = $I->logANewUser();
        $I->navigateToTheStoryPage();
    }

    public function addStorySuccess(FunctionalTester $I)
    {
        $I->am('a member of Teen Quotes');
        $I->wantTo('add successfully my story');

        // Post a story
        list($represent, $frequence) = $this->postStory($I, 200, 200);

        // Assert story has been posted
        $I->seeSuccessFlashMessage('Your story has been added '.$this->user->login);
        $I->see($represent, '.story');
        $I->see($frequence, '.story');
    }

    public function tooSmallRepresentAndFrequence(FunctionalTester $I)
    {
        $I->am('a member of Teen Quotes');
        $I->wantTo('add a story with a too small content');

        $this->postStory($I, 99, 99);

        $I->seeFormError('The represent txt must be at least 100 characters.');
        $I->seeFormError('The frequence txt must be at least 100 characters.');
    }

    public function tooLargeRepresentAndFrequence(FunctionalTester $I)
    {
        $I->am('a member of Teen Quotes');
        $I->wantTo('add a story with a too large content');

        $this->postStory($I, 1001, 1001);

        $I->seeFormError('The represent txt may not be greater than 1000 characters.');
        $I->seeFormError('The frequence txt may not be greater than 1000 characters.');
    }

    public function noContentAdded(FunctionalTester $I)
    {
        $I->am('a member of Teen Quotes');
        $I->wantTo('add a story with no content');

        $this->postStory($I, 0, 0);

        $I->seeFormError('The represent txt field is required.');
        $I->seeFormError('The frequence txt field is required.');
    }

    public function seeWarningWhenLoggedOut(FunctionalTester $I)
    {
        $I->am('a visitor of Teen Quotes');
        $I->wantTo('add a story');

        $I->logout();

        $I->navigateToTheStoryPage();
        $I->see('You must be logged in if you want to add your story');
    }

    private function postStory(FunctionalTester $I, $representLength, $frequenceLength)
    {
        $represent = '';
        $frequence = '';

        if ($representLength > 0) {
            $represent = Str::random($representLength);
        }
        if ($frequenceLength > 0) {
            $frequence = Str::random($frequenceLength);
        }

        $I->fillStoryForm($represent, $frequence);

        return [$represent, $frequence];
    }
}
