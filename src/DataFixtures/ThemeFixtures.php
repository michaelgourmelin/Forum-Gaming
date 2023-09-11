<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ThemeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        //use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 30; $prod++) {
            $theme = new Theme();
            $theme->setName($faker->text(15));
            $theme->setSlug($this->slugger->slug($theme->getName())->lower());
            $user = $this->getReference('user-'. rand(1,7));
            $theme->setUsers($user);
           
           

            //On va chercher une référence de catégorie
            $users = $this->getReference('cat-' . rand(1, 11));
            $theme->setCategories($users);

          

            $this->setReference('prod-' . $prod, $theme);
            $manager->persist($theme);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UsersFixtures::class
        ];
    }
}
