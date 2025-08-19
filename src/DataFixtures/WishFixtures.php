<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $wish = new Wish();

            $wish->setTitle($faker->realText(30))
                ->setDescription($faker->realText (250))
                ->setAuthor($faker->realText(50))
                ->setIsPublished(true)
                ->setDateCreated(new \DateTime())
                ->setDateUpdated(new \DateTime())

            ;


        // $product = new Product();
        $manager->persist($wish);

    }
        $manager->flush();
}
}