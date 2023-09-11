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
        $parent = $this->createCategory('Diablo 4', null, $manager);
        
        $this->createCategory('Le jcj', $parent, $manager);
        $this->createCategory('Le stuff', $parent, $manager);
        $this->createCategory('Tournoi', $parent, $manager);

        $parent = $this->createCategory('World of Warcraft', null, $manager);

        $this->createCategory('Discussion Générale', $parent, $manager);
        $this->createCategory('Ranked', $parent, $manager);
        $this->createCategory('Tournament', $parent, $manager);

        $parent = $this->createCategory('Counter strike 2', null, $manager);

        $this->createCategory('Streaming', $parent, $manager);
        $this->createCategory('Le skill', $parent, $manager);
        $this->createCategory('Évènement', $parent, $manager);
      
                
                
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setParent($parent);
        $category->setCategoryOrder(rand(1, 11));
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
