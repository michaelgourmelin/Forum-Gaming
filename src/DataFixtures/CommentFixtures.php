<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class CommentFixtures extends Fixture 
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
    //     $faker = Faker\Factory::create('fr_FR');

    //     for($thm = 1; $thm <= 40; $thm++){
    //         $comment= new Comment();
          
    //         $comment->setCommentaire($faker->text());
    
    //         $comment= $this->getReference('cat-'.rand(1, 11));
    //         $category->setTheme($category);

    //         $this->setReference('prod-' . $thm, $category);
    //         $manager->persist($comment);
    //     }

    //     $manager->flush();
    // }

    // public function getDependencies(): array
    // {
    //     return [
    //         CategoriesFixtures::class
    //     ];  
       }
}
