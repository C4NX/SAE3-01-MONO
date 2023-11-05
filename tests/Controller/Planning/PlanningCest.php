<?php


namespace App\Tests\Controller\Planning;

use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\ControllerTester;

class PlanningCest
{
    public function planningIndex(ControllerTester $I) {
        $I->amOnPage('/planning');
        $I->seeResponseCodeIs(200);
        $I->see('Liste des Plannings');
        $I->seeElement('header');
        $I->seeElement('footer');
        $I->seeElement('.main-content');
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();
        $I->amLoggedInAs($veto);
        $I->amOnPage('/planning/' . $vetoProxy->getId());
    }

    public function planningAdded(ControllerTester $I) {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();
        $I->amLoggedInAs($veto);
        $I->amOnPage('/');
        $I->click('Mon Dashboard');
        $I->see('Mon Dashboard');
        $I->see('Créer mon planning');
        $I->click('Créer mon planning');
        $I->see('Créer votre planning');
        $I->seeCurrentUrlEquals('/planning/create');
        $I->selectOption('agenda_form[timeStart][hour]', '08');
        $I->selectOption('agenda_form[timeStart][minute]', '30');
        $I->selectOption('agenda_form[timeEnd][hour]', '17');
        $I->selectOption('agenda_form[timeEnd][minute]', '30');
        $I->click("C'est bon !");
        $I->amOnPage('planning/');
        $I->see($veto->getEmail(), 'tr');
    }

    public function deletePlanningForClientWhoIsntVeto(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();
        $I->amLoggedInAs($client);
        $I->amOnPage('/planning/8/delete');
        $I->seeResponseCodeIs(403);
    }

    public function deletePlanningFromVeto(ControllerTester $I) {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();
        $I->amLoggedInAs($veto);
        $I->amOnPage('dashboard');
        $I->click('Créer mon planning');
        $I->click("C'est bon !");
        $vetores = $vetoProxy->object();
        $I->amOnPage('/planning/' . $vetores->getAgenda()->getId() . '/delete');
        $I->amOnPage('/dashboard');
        $I->dontSee("Éditer mon planning");
        $I->see("Vous n'avez pas de planning, commencez par en créer un !");
    }

}