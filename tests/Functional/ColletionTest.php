<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Colletion;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ColletionTest extends WebTestCase
{
    public function testIfCreateColletionIsSuccessfull(): void
    {
        $client = static::createClient();

        // Recup Urlgenerator
        $urlGenerator = $client->getContainer()->get("router");

        // Recup entity Manager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        // Se rendre sur la page de création d'une collection
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate("collection.new"));

        // Gérer le formulaire
        $form = $crawler->filter('form[name=colletion]')->form([
            "colletion[name]" => "une colletion"
        ]);

        $client->submit($form);

        // Gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        // Gérer l'alert box et la route
        $this->assertSelectorTextContains("div.alert-success", "Votre collection a été créé avec succès !");

        $this->assertRouteSame("colletion.index");
    }

    public function testIfListColletionIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate("colletion"));

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame("colletion.index");
    }

    public function testIfUpdateColletionIsSuccefull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->find(User::class, 1);
        $colletion = $entityManager->getRepository(Colletion::class)->findOneBy([
            "user" => $user
        ]);

        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("colletion.edit", ["id" => $colletion->getId()]),
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter("form[name=colletion]")->form([
            "colletion[name]" => "Une collection 2"
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "Votre collection a été modifiée avec succès !");

        $this->assertRouteSame("colletion.index");
    }

    public function testIfDeleteColletionIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->find(User::class, 1);
        $colletion = $entityManager->getRepository(Colletion::class)->findOneBy([
            "user" => $user
        ]);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("colletion.delete", ["id" => $colletion->getId()]),
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "La collection a été supprimée avec succès !");

        $this->assertRouteSame("colletion.index");
    }
}
