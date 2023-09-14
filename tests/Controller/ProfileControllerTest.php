<?php

namespace App\tests\Controller;


use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    /**
     * L'anonyme est redirigé
     * 
     * @dataProvider urlProvider
     */
    public function testThemeAnonymous($url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseRedirects();
    }

    /**
     * un ROLE_USER a un statut 200 sur la liste
     */
    public function testRoleUserList(): void
    {
        // on crée le cient
        $client = static::createClient();

        // on se connecter en tant que ROLE_USER
        /**
         * @var UsersRepository
         */
        $userRepository = static::getContainer()->get(UsersRepository::class);
        $userTest = $userRepository->findOneBy(['email' => 'user@user.com']);
        $client->loginUser($userTest);


        // on doit accède à la page de profil
        $crawler = $client->request('GET', '/profil');
        // qui doît être accessible
        $this->assertResponseIsSuccessful();
    }


    /**
     * URLs à fournir pour les tests
     */
    public function urlProvider()
    {

        yield ['/profil'];
        yield ['/profil/comment'];
        yield ['/profil/update/1'];
        yield ['/profil/delete/1'];
    }

    /**
     * URLs à fournir pour les tests du ROLE_USER
     */
    public function urlRoleUserProvider()
    {

        yield ['/profil/comment'];
        yield ['/profil/update/1'];
        yield ['/profil/delete/1'];
    }
}
