<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Communautés', null, $manager);
        
        $this->createCategory('Discution Générale', $parent, $manager);
        $this->createCategory('Partage', $parent, $manager);
        $this->createCategory('Vision de l\avenir du jeu-vidéo', $parent, $manager);

        $parent = $this->createCategory('Esport', null, $manager);

        $this->createCategory('Compétition', $parent, $manager);
        $this->createCategory('Discussion Générale', $parent, $manager);
        $this->createCategory('Le ranked', $parent, $manager);

        $parent = $this->createCategory('Jeux ESL', null, $manager);

        $this->createCategory('Valorant', $parent, $manager);
        $this->createCategory('OW2', $parent, $manager);
        $this->createCategory('WOW', $parent, $manager);
        $this->createCategory('CS2', $parent, $manager);
        $this->createCategory('Diablo 4', $parent, $manager);
                
                
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
    // public function getDependencies(): array
    // {
    //     return [
    //         UsersFixtures::class
    //     ];  
    // }
}