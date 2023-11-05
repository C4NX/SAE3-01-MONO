<?php

namespace App\Tests\Controller\Home;

use App\Factory\ClientFactory;
use App\Tests\ControllerTester;

class HomeCest
{

    public function indexHome(ControllerTester $I)
    {
        $I->amOnPage('/');
        $I->seeResponseCodeIs(200);
        $I->see("Bienvenue sur Take'A'Vet");
        $I->seeElement('header');
        $I->seeElement('footer');
        $I->seeElement('.main-content');
    }

    public function contactHome(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/contact');
        $I->see('Contacter le service');
    }

}
