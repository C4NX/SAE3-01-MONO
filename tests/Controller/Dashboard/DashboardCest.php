<?php


namespace App\Tests\Controller\Dashboard;

use App\Factory\VetoFactory;
use App\Tests\ControllerTester;

class DashboardCest
{
    public function seeDashboard(ControllerTester $I)
    {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();

        $I->amLoggedInAs($veto);
        $I->amOnPage('/dashboard');
        $I->see('Mon Dashboard');
        $I->see('Créer mon planning');
    }

    public function createPlanning(ControllerTester $I) {
        $vetoProxy = VetoFactory::createOne();
        $veto = $vetoProxy->object();

        $I->amLoggedInAs($veto);
        $I->amOnPage('/dashboard');
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

        $I->seeCurrentUrlEquals('/dashboard');

        $I->click('Éditer mon planning');

        $I->fillField('vacation_form[libVacation]', "Vacances d'été");
        $I->selectOption('vacation_form[dateStart][day]', '1');
        $I->selectOption('vacation_form[dateStart][month]', 'août');
        $I->selectOption('vacation_form[dateStart][year]', '2023');

        $I->selectOption('vacation_form[dateEnd][day]', '28');
        $I->selectOption('vacation_form[dateEnd][month]', 'août');
        $I->selectOption('vacation_form[dateEnd][year]', '2023');
        $I->click('Ajouter');
        $I->see("Vacances d'été");
        $I->see("L'opération a été un succès, vous pouvez faire un tour sur le planning.");
    }

}
