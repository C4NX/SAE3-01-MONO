<?php


namespace App\Tests\Controller\RecordAnimal;

use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\ControllerTester;

class RecordAnimalCest
{

    public function recordCreateDenied(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/record/create');
        $I->seeResponseCodeIs(403);
    }

    public function recordIdDenied(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/record/5');
        $I->seeResponseCodeIs(403);
    }

    public function recordUpdateDenied(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/record/update/5');
        $I->seeResponseCodeIs(403);
    }

    public function recordAuthorized(ControllerTester $I) {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();

        $I->amLoggedInAs($veto);
        $I->amOnPage('/record/5');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/record/update/5');
        $I->seeResponseCodeIs(200);
        $I->amOnPage('/record/create');
        $I->seeResponseCodeIs(200);
    }

}
