<?php

namespace App\Tests\Unit;

use App\Entity\Colletion;
use App\Entity\User;
use App\Entity\Mark;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ColletionTest extends KernelTestCase
{
    public function getEntity(): Colletion
    {
        return (new Colletion())
            ->setName('Colletion #1')
            ->setCategory('Category #1')
            ->setDescription('Description #1')
            ->setPrice('Price #1')
            ->setIsFavorite(true)
            ->setIsPublic(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }

    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $colletion = $this->getEntity();


        $errors = $container->get('validator')->validate($colletion);

        $this->assertCount(0, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testInvalideName()
    {
        self::bootKernel();
        $container = static::getContainer();

        $colletion = $this->getEntity();
        $colletion->setName('');

        $errors = $container->get('validator')->validate($colletion);
        $this->assertCount(2, $errors,  'Le nombre d\'erreurs de validation est incorrect.');
    }

    public function testGetAverage()
    {
        $colletion = $this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 1);

        for ($i = 0; $i < 5; $i++) {
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setColletion($colletion);

            $colletion->addMark($mark);
        }

        $this->assertTrue(2.0 === $colletion->getAverage());
    }
}
