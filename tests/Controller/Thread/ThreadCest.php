<?php


namespace App\Tests\Controller\Thread;

use App\Factory\ClientFactory;
use App\Tests\ControllerTester;
use http\Client;

class ThreadCest
{
    public function testIndexForNoUser(ControllerTester $I): void
    {
        $I->amOnPage('/threads');
        $I->seeResponseCodeIs(200);
        $I->click('Ajouter une question ici');
        $I->seeCurrentUrlEquals('/login');
    }

    public function testIndexForUser(ControllerTester $I): void
    {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/threads');
        $I->seeResponseCodeIs(200);
        $I->click('Ajouter une question ici');
        $I->seeCurrentUrlEquals('/thread/create');
        $I->see('Soumettre une question');
        $I->fillField('thread_form[lib]', 'Question pour test');
        $I->click('Soumettre ma question');
        $I->see('Question pour test');
        $I->see('Ma question est résolue ?');
        $I->click('Ma question est résolue ?');
        $I->see('Question résolue');
        $I->see('Je veux réouvrir ma question !');
        $I->click('Je veux réouvrir ma question !');
        $I->dontSee('Question résolue');
        $I->seeResponseCodeIs(200);
    }

    public function testThreadDeleted(ControllerTester $I): void
    {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/threads');
        $I->seeResponseCodeIs(200);
        $I->click('Ajouter une question ici');
        $I->seeCurrentUrlEquals('/thread/create');
        $I->see('Soumettre une question');
        $I->fillField('thread_form[lib]', 'Question pour test');
        $I->click('Soumettre ma question');
        $I->click('Supprimer');
        $I->seeCurrentUrlEquals('/threads');
    }

    public function testThreadAnswer(ControllerTester $I): void
    {
        $clientProxy = ClientFactory::createOne();
        $client = $clientProxy->object();

        $I->amLoggedInAs($client);
        $I->amOnPage('/threads');
        $I->seeResponseCodeIs(200);
        $I->click('Répondre');
        $I->fillField('thread_reply_form[message]', 'Réponse pour test');
        $I->click('Répondre');
        $I->seeElement('p.thread__messages_item_message:contains("Réponse pour test")');
    }



}
