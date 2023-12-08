<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Monstre;
use App\Entity\Monstredex;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
    /**
     * Faker
     *
     * @var Generator
     */
    private Generator $faker;

    /**
     * User Password Hasher
     *
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * Constructeur des Fixtures
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->faker = Factory::create("fr_FR");
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * Loading fixtures script
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void 
    {
        $users = [];

        $publicUser = new User();
        $publicUser->setUsername("public@public");
        $publicUser->setRoles(["ROLE_PUBLIC"]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $manager->persist($publicUser);
        // $users[] = $publicUser;

        $adminUser = new User();
        $adminUser->setUsername("admin");
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
        $manager->persist($adminUser);
        $users[] = $adminUser;


        for ($i=0; $i < 5; $i++) { 
            $userUser = new User();
            $password = $this->faker->password(5,10);
            $username = $this->faker->userName();
            $userUser->setUsername($username . "@" . $password);
            $userUser->setRoles(["ROLE_USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $manager->persist($userUser);
            $users[] = $userUser;  
        }


        $monstredexEntries = []; 
        for ($i=0; $i < 15; $i++) { 
            $monsterdexEntry = new Monstredex();

            $monsterdexEntry->setName( $this->faker->realText($maxNbChars = 12,$indexSize= 2 ) . "mon");
            $monsterdexEntry->setPvMin(10);
            $monsterdexEntry->setPvMax(25);
            $monsterdexEntry->setStatus("on");
            $monsterdexEntry->setCreatedBy($adminUser);
            $monsterdexEntry->setUpdatedBy($adminUser);
            $monsterdexEntry->setCreatedAt(new DateTimeImmutable());
            $monsterdexEntry->setUpdatedAt(new DateTimeImmutable());
            $monstredexEntries[] = $monsterdexEntry;
            $manager->persist($monsterdexEntry);

            
        }
        foreach ($monstredexEntries as $key => $monstredexEntryVal) {
            // $evolution = $monstredexEntryVal;
            $evolution = $monstredexEntries[array_rand($monstredexEntries, 1)];
            $monstredexEntryVal->addEvolution($evolution);
            $manager->persist($monstredexEntryVal);
        }

        // for ($i=0; $i < 10; $i++) { 
        foreach ($users as $key => $user) {

            for ($i=0; $i < random_int(0, 6); $i++) { 
                # code...
                $monsterEntry = new Monstre();
                $monstredexRef = $monstredexEntries[array_rand($monstredexEntries,1)];
                $monsterEntry->setName( $monstredexRef->getName());
                $pvMax = rand($monstredexRef->getPvMin(),$monstredexRef->getPvMax());
                $monsterEntry->setPv($pvMax);
                $monsterEntry->setPvMax($pvMax);
                $monsterEntry->setStatus("on");
                $monsterEntry->setCreatedAt(new DateTimeImmutable());
                $monsterEntry->setUpdatedAt(new DateTimeImmutable());
                $monsterEntry->setCreatedBy($user);
                $monsterEntry->setUpdatedBy($user);
                $monsterEntry->setMonstreDex($monstredexRef);
                $manager->persist($monsterEntry);
            }
        }

        $manager->flush();
    }
}
