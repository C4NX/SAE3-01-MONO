<?php

namespace App\Tests\Controller\Registration;

use App\Tests\ControllerTester;

class RegisterCest
{
    public function testRegisterForVeto(ControllerTester $I)
    {
        $I->amOnPage('/register');
        $I->see("Vous êtes vétérinaire, c'est ici !");
        $I->click("Vous êtes vétérinaire, c'est ici !");
        $I->seeCurrentUrlEquals('/register/vet');
        $I->see('Créer un compte pro');
        $I->fillField('registration_form_veto[email]', 'veto-871@veto.net');
        $I->fillField('registration_form_veto[lastName]', 'Veto');
        $I->fillField('registration_form_veto[firstName]', 'Veto');
        $I->fillField('registration_form_veto[plainPassword]', 'vetotest');
        $I->click('Créer le compte');
        $I->click('Se connecter');
        $I->see('Veuillez vous connecter');
        $I->fillField('email', 'veto-871@veto.net');
        $I->fillField('password', 'vetotest');
        $I->click('Sign in');
        $I->seeResponseCodeIs(200);
    }
}
