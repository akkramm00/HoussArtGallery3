<?php

namespace App\Tests\Unit;

use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReviewTest extends KernelTestCase
{
    public function getEntity(): Review
    {
        return (new Review())
            ->setFullName('FullName #1')
            ->setMessage('Message #1')
            ->setIsPublic(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $review = $this->getEntity();


        $errors = $container->get('validator')->validate($review);

        $this->assertCount(0, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testIsValidFullName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $review = $this->getEntity();
        $review->setFullName('');

        $errors = $container->get('validator')->validate($review);
        $this->assertCount(2, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testIsValidMesage()
    {
        self::bootKernel();
        $container = static::getContainer();

        $review = $this->getEntity();
        $review->setMessage('');

        $errors = $container->get('validator')->validate($review);
        $this->assertCount(2, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }
}
