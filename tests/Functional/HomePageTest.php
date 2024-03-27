<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $icons = $crawler->filter('.icons');
        $this->assertEquals(4, count($icons));

        $products = $crawler->filter('.products .card');
        $this->assertEquals(4, count($products));

        $colletion = $crawler->filter('.colletion .card');
        $this->assertEquals(4, count($colletion));

        $review = $crawler->filter('.review .card');
        $this->assertEquals(1, count($review));

        $this->assertSelectorTextContains('h1', 'Houss.Art.Gallery');
    }
}
