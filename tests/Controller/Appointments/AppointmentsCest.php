<?php


namespace App\Tests\Controller\Appointments;

use App\Factory\ClientFactory;
use App\Tests\ControllerTester;

class AppointmentsCest
{
    public function seeAppointments(ControllerTester $I)
    {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);

        $I->amOnPage('/appointments');
        $I->see('Mes rendez-vous');
        $I->see('Prendre un rendez-vous');
    }
}
