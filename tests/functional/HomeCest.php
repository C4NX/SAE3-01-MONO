<?php

namespace App\Tests\Functional;

use App\Tests\FunctionalTester;

class HomeCest
{
    public function index(FunctionalTester $tester): void
    {
        $tester->amOnPage('/');
        $tester->seeResponseCodeIsSuccessful();
        $tester->seeElement('header');
        $tester->seeElement('footer');
        $tester->seeElement('.main-content');
    }
}
