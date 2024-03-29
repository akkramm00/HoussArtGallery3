<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsTest extends WebTestCase
{
    public function testIfCreateProductsIsSuccessfull(): void
    {
        $client = static::createClient();

        // Recup urlgenerator
        $urlGenerator = $client->getContainer()->get('router');

        // Recup entity manager
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        // Se rendre sur la page de création d'un Produit
        $crawler = $client->request(Request::METHOD_GET, $urlGenerator->generate("products.new"));

        // Gérer le formulaire
        $form = $crawler->filter("form[name=products]")->form([
            "products[name]" => "Un produit",
            "products[price]" => floatval(33)
        ]);

        $client->submit($form);

        // Gérer la redirection
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        // Gérer l'alert box et la route
        $this->assertSelectorTextContains('div.alert-success', 'Votre produit a été créé avec succès !');

        $this->assertRouteSame('products');
    }

    public function testIfListProductsIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->find(User::class, 1);

        $client->loginUser($user);

        $client->request(Request::METHOD_GET, $urlGenerator->generate("products"));

        $this->assertResponseIsSuccessful();

        $this->assertRouteSame("products");
    }

    public function testifUpdateProductsIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $entityManager->find(User::class, 1);

        $products = $entityManager->getRepository(Products::class)->findOneBy([
            "user" => $user
        ]);
        $client->loginUser($user);

        $crawler = $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("products.edit", ["id" => $products->getId()]),
        );

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter("form[name=products]")->form([
            "products[name]" => "Un produit 2",
            "products[price]" => floatval(34)
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "Votre produit a été modifié avec succès !");

        $this->assertRouteSame("products");
    }

    public function testIfDeleteProductsIsSuccessfull(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get("router");

        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $user = $entityManager->find(User::class, 1);
        $products = $entityManager->getRepository(Products::class)->findOneBy([
            "user" => $user
        ]);

        $client->loginUser($user);

        $client->request(
            Request::METHOD_GET,
            $urlGenerator->generate("products.delete", ["id" => $products->getId()]),
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains("div.alert-success", "Votre produit a été supprimé avec succès !");

        $this->assertRouteSame("products");
    }
}
