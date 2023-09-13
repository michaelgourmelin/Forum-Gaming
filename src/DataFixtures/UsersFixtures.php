<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstname('admin');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        $managerAdmin = new Users();
        $managerAdmin->setEmail('manager@manager.com'); 
        $managerAdmin->setFirstname('manager');
        $managerAdmin->setPassword(
            $this->passwordEncoder->hashPassword($managerAdmin, 'manager')
        );
        $managerAdmin->setRoles(['ROLE_MANAGER_ADMIN']);

        $manager->persist($managerAdmin);



        $faker = Faker\Factory::create('fr_FR');

        for ($usr = 1; $usr <= 60; $usr++) {
            $user = new Users();
            $user->setEmail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'secret')
            );
            $this->setReference('user-' . $usr, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
