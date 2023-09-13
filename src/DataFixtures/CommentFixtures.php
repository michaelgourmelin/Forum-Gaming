<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($thm = 1; $thm <= 500; $thm++) {
            $comment = new Comment();

            $comment->setCommentaire($faker->text());

            $user = $this->getReference('user-' . rand(1, 60));
            $comment->setUsers($user);


            $theme = $this->getReference('theme-' . rand(1, 150));
            $comment->setTheme($theme);

          
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ThemeFixtures::class
        ];
    }
}
