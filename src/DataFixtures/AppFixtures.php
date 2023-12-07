<?php

namespace App\DataFixtures;

use App\Entity\Monstredex;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create("fr_FR");
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        for ($i=0; $i < 152; $i++) { 
            $monsterdexEntry = new Monstredex();

            $monsterdexEntry->setName( $this->faker->realText($maxNbChars = 12,$indexSize= 2 ) . "mon");
            $monsterdexEntry->setPvMin(10);
            $monsterdexEntry->setPvMax(25);
            $monsterdexEntry->setStatus("on");
            $monsterdexEntry->setCreatedAt(new DateTimeImmutable());
            $monsterdexEntry->setUpdatedAt(new DateTimeImmutable());
            $manager->persist($monsterdexEntry);
        }
     
        $manager->flush();
    }
}
