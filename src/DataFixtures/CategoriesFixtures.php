<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\DBAL\Connection;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    private $connection;

    public function __construct(private SluggerInterface $slugger, Connection $connection)

    {
        $this->connection = $connection;
    }

    /**
     * Permet de TRUNCATE les tables et de remettre les AI à 1
     */
    private function truncate()
    {
        // On passe en mode SQL ! On cause avec MySQL
        // Désactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // On tronque
        $this->connection->executeQuery('TRUNCATE TABLE categories');
        $this->connection->executeQuery('TRUNCATE TABLE comment');
        $this->connection->executeQuery('TRUNCATE TABLE theme');
        $this->connection->executeQuery('TRUNCATE TABLE users');
        $this->connection->executeQuery('TRUNCATE TABLE visits');
    
        // etc.
    }


    public function load(ObjectManager $manager): void
    {
        $this->truncate();

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

        $this->addReference('cat-' . $this->counter, $category);
        $this->counter++;

        return $category;
    }
}
