<?php

namespace App\Tests\Unit;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductsTest extends KernelTestCase
{
    public function getEntity(): Products
    {
        return (new Products())
            ->setName('Products #1')
            ->setPrice(19.99)
            ->setSize('Size #1')
            ->setProperty('Property #1')
            ->setArtist('Artist #1')
            ->setAgeArtist(45)
            ->setOrigin('Origin #1')
            ->setAutobiography('Autobiography #1')
            ->setCategory('Category #1')
            ->setIsPublic(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $products = $this->getEntity();


        $errors = $container->get('validator')->validate($products);

        $this->assertCount(0, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testIsValidName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $products = $this->getEntity();
        $products->setName('');

        $errors = $container->get('validator')->validate($products);
        $this->assertCount(2, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testIsValidatePrice()
    {
        self::bootKernel();
        $container = static::getContainer();

        $products = $this->getEntity();
        $products->setPrice(19.99);

        $errors = $container->get('validator')->validate($products);
        $this->assertCount(0, $errors,  'Le nombre d\'erreurs de validation est incorrect.');

        $this->assertSame('Le prix doit être un nombre.', $errors[0]->getMessage());
    }
}
