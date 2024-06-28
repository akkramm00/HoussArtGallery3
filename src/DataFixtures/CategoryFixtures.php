<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\PostRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) {
    }

    public function load(ObjectManager $manager): Void
    {
        $faker = Factory::create('fr_FR');


        //category
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->words(4, true) . ' ' . $i)
                ->setSlug($faker->words(4, true) . ' ' . $i)

                ->setDescription(mt_rand(0, 1) === 1 ? $faker->realText(254) : '');


            $manager->persist($category);
            $categories[] = $category;
        }

        $posts = $this->postRepository->findAll();

        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }
}
