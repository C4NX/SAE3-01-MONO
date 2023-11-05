<?php


namespace App\Tests\Controller\Vacations;

use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\ControllerTester;

class VacationsCest
{
    public function normalUserCannotAccessToVacations(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();
        $I->amLoggedInAs($client);
        $I->amOnPage('vacation/2/delete');
        $I->seeResponseCodeIs(403);
    }

    /*
     * test non fonctionnel, à corriger
     *
     * public function vetoCanDeleteVacations(ControllerTester $I) {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();
        $I->amLoggedInAs($veto);
        $I->amOnPage('dashboard');
        $I->click('Créer mon planning');
        $I->click("C'est bon !");
        $I->click("Éditer mon planning");
        $I->fillField('vacation_form[libVacation]', "Vacances d'été");
        $I->click('Ajouter');
        $vetores = $vetoProxy->object();
        $I->amOnPage('vacation/' . $vetores->getAgenda()->getVacations()[0]->getId() . '/delete');
        $vetores = $vetoProxy->object();
        $I->amOnPage('/dashboard');
        $I->click('Éditer mon planning');
        $I->see('Edition de votre planning');
        $I->dontSee('Vacances d\'été');
    } */

}
