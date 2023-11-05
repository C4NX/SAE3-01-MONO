<?php


namespace App\Tests\Controller\User;

use App\Factory\ClientFactory;
use App\Tests\ControllerTester;

class UserCest
{
    public function notUserRedirectionOnLogin(ControllerTester $I) {
        $I->amOnPage('/me');
        $I->seeCurrentUrlEquals('/login');
    }

    public function userCanAccessToMe(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/me');
        $I->seeCurrentUrlEquals('/me');
        $I->seeResponseCodeIs(200);
    }

    public function userCanEditHim(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/me');
        $I->seeCurrentUrlEquals('/me');
        $I->seeResponseCodeIs(200);
        $I->see('Mes Informations');
        $I->fillField('user_info_change_form[lastName]', 'Gis');
        $I->fillField('user_info_change_form[firstName]', 'José');
        $I->fillField('user_info_change_form[tel]', '0606060606');
        $I->click('Mettre à jour les informations');
        $I->seeResponseCodeIs(200);
        $I->seeInField('user_info_change_form[lastName]', 'Gis');
        $I->seeInField('user_info_change_form[firstName]', 'José');
    }

    public function userCanDeleteHim(ControllerTester $I) {
        $client = ClientFactory::createOne([
            'email' => 'testdelete111@live.fr',
            'password' => 'test123'
        ]);
        $I->amOnPage('/login');
        $I->fillField('email', 'testdelete111@live.fr');
        $I->fillField('password', 'test123');
        $I->click('Sign in');
        $I->amOnPage('/me');
        $I->seeCurrentUrlEquals('/me');
        $I->see('Supprimer votre compte');
        $I->click('Supprimer votre compte');
        $I->seeCurrentUrlEquals('/');
        $I->amOnPage('/');
        $I->click('Se connecter');
        $I->fillField('email', 'testdelete111@live.fr');
        $I->fillField('password', 'test123');
        $I->click('Sign in');
        $I->see('Identifiants invalides.');
    }

}
