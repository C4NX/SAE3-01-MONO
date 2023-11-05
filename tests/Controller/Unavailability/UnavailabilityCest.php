<?php


namespace App\Tests\Controller\Unavailability;

use App\Entity\Veto;
use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\ControllerTester;

class UnavailabilityCest
{
    public function testDeleteUnavailabilityForClient(ControllerTester $I): void
    {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/unavailability/1/delete');
        $I->seeResponseCodeIs(403);
        $I->amOnPage('/unavailability/1/');
        $I->seeResponseCodeIs(404);
    }

}
