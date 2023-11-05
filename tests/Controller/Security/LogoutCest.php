<?php


namespace App\Tests\Controller\Security;

use App\Tests\ControllerTester;

class LogoutCest
{

        public function logout(ControllerTester $I)
        {
            $I->amOnPage('/');
            $I->click('Se connecter');
            $I->fillField('Email', 'admin@take.vet');
            $I->fillField('Mot de passe', 'admin');
            $I->click('Sign in');
            $I->see('Menu admin');
            $I->see('Déconnexion');
            $I->click('Déconnexion');
            $I->see('Se connecter');
        }
}
