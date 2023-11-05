<?php


namespace App\Tests\Controller\Animal;

use App\DataFixtures\AnimalFixtures;
use App\Factory\AnimalFactory;
use App\Factory\ClientFactory;
use App\Tests\ControllerTester;
use DateTimeZone;

class AnimalCest
{

    public function TestAnimalAddedAndDeleted(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);

        $I->amOnPage('/animal');
        $I->click('Ajouter un nouvel animal');
        $I->seeCurrentUrlEquals('/animal/create');
        $I->fillField('Nom', 'Alphonse');
        $I->selectOption('animal[birthday][day]', '12');
        $I->selectOption('animal[birthday][month]', 'août');
        $I->selectOption('animal[birthday][year]', '1989');
        $I->click('créer');
        $I->seeCurrentUrlEquals('/animal');
        $I->click('Supprimer cet animal');
        $I->see('Suppression de Alphonse');
        $I->click('Oui, supprimer cet animal');
    }


    public function TestAnimalEdited(ControllerTester $I) {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);

        $I->amOnPage('/animal');
        $I->click('Ajouter un nouvel animal');
        $I->seeCurrentUrlEquals('/animal/create');
        $I->fillField('Nom', 'Roger');
        $I->selectOption('animal[birthday][day]', '12');
        $I->selectOption('animal[birthday][month]', 'août');
        $I->selectOption('animal[birthday][year]', '1989');
        $I->click('créer');
        $I->seeCurrentUrlEquals('/animal');
        $I->click('Modifier cet animal');
        $I->see('Édition de Roger');
        $I->fillField('Nom', 'Roger le chien');
        $I->click('modifier');
        $I->seeCurrentUrlEquals('/animal');
        $I->see('Roger le chien');
    }

}
