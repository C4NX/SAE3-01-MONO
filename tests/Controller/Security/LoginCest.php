<?php


namespace App\Tests\Controller\Security;

use App\Factory\ClientFactory;
use App\Factory\VetoFactory;
use App\Tests\ControllerTester;
use http\Client;

class LoginCest
{

    public function loginForAdmin(ControllerTester $I)
    {
        $I->amOnPage('/');
        $I->click('Se connecter');
        $I->fillField('Email', 'admin@take.vet');
        $I->fillField('Mot de passe', 'admin');
        $I->click('Sign in');
        $I->see('Menu admin');
        $I->see('Déconnexion');
    }

    public function loginForUser(ControllerTester $I) {
        $client = ClientFactory::createOne([
            'email' => 'doetest@take.vet',
            'password' => 'doe',
        ]);
        $I->amOnPage('/');
        $I->click('Se connecter');
        $I->fillField('Email', 'doetest@take.vet');
        $I->fillField('Mot de passe', 'doe');
        $I->click('Sign in');
        $I->see('Déconnexion');
        $I->see('Mes adresses');
    }

    public function loginForVeto(ControllerTester $I) {
        $client = VetoFactory::createOne([
            'email' => 'vetotesting@takevet.net',
            'password' => 'vetoveto',
        ]);
        $I->amOnPage('/');

        $I->click('Se connecter');
        $I->fillField('Email', 'vetotesting@takevet.net');
        $I->fillField('Mot de passe', 'vetoveto');
        $I->click('Sign in');
        $I->see('Mon Dashboard');
        $I->see('Créer un récap');
    }

}