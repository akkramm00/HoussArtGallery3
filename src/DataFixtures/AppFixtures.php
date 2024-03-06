<?php

namespace App\DataFixtures;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Products;
use App\Entity\Colletion;
use App\Entity\Contact;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var generator
     */
    private Generator $faker;


    public function __construct()

    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Users
        $users = [];

        $admin = new User();
        $admin->setFullName('Administrateur de HoussArtGallery')
            ->setPseudo('null')
            ->setEmail('admin@houssartgallery.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->SetPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }


        // Products
        $product = [];
        $nameOptions = ['Univer', 'Calligrahpy', 'Nature', 'Espoir', 'amor', 'Liberty'];
        $sizeOptions = ['120 / 80', '100 / 60', '200 / 150', '250 / 200'];
        $categoryOptions = ['Abstrait', 'Figuratif', 'Calligraphy', 'Contemporain'];

        for ($p = 1; $p <= 50; $p++) {
            $products = new Products();
            $products->setName($nameOptions[array_rand($nameOptions)])
                ->setPrice(mt_rand('1000', '50000'))
                ->setSize($sizeOptions[array_rand($sizeOptions)])
                ->setProperty($this->faker->text(2000))
                ->setArtist($this->faker->words(2, true))
                ->setCategory($categoryOptions[array_rand($categoryOptions)])
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $product[] = $products;
            $manager->persist($products);
        }

        // Collection
        $colletions = [];
        for ($c = 0; $c < 30; $c++) {
            $colletion = new Colletion();
            $colletion->setName($this->faker->words(2, true))
                ->setArtist($this->faker->words(2, true))
                ->setCategory($categoryOptions[array_rand($categoryOptions)])
                ->setDescription($this->faker->text(2000))
                ->setPrice(mt_rand('20000', '100000'))
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            for ($k = 0; $k < mt_rand(5, 15); $k++) {

                $colletion->addProduct($product[mt_rand(0, count($product) - 1)]);
            }

            $colletions[] = $colletion;
            $manager->persist($colletion);
        }

        // Marks
        foreach ($colletions as $colletion) {
            for ($x = 0; $x < mt_rand(0, 4); $x++) {
                $mark = new Mark();
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users) - 1)])
                    ->setColletion($colletion);

                $manager->persist($mark);
            }
        }

        // Contact
        for ($b = 0; $b < 5; $b++) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n° . ($c + 1')
                ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        // Review
        for ($r = 0; $r < 10; $r++) {
            $review = new Review();
            $review->setNom($this->faker->name())
                ->setPrenom($this->faker->firstName())
                ->setMessage($this->faker->text(255))
                ->setRoles(['ROLE_USER'])
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false);

            $manager->persist($review);
        }





        $manager->flush();
    }
}
