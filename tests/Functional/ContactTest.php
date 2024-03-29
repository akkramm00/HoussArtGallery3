<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'FORMULAIRE DE CONTACT');

        // Récupérer le formulaire 

        $submitButton = $crawler->selectButton('Valider');
        $form = $submitButton->form();


        $form["contact[fullName]"] = "Dominique Delannoy";
        $form["contact[email]"] = "margaux.cousin@noos.fr";
        $form["contact[subject]"] = "Test";
        $form["contact[message]"] = "Test";

        // Soumettre le formulaire
        $client->submit($form);

        // Vérifier le statut HTTP
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        // Vérifier l'envoie du mail
        $this->assertEmailCount(1);

        $client->followRedirect();

        // Vérifier la présence du message de succès
        $this->assertSelectorTextContains(
            "div.alert.alert-succes.mt-4",
            "Votre message a bien été enregistré avec succès"
        );
    }
}
