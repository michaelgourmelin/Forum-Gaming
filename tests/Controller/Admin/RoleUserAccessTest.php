<?php

namespace App\tests\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleUserAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour ROLE_USER
     * 
     * @dataProvider getUrls
     */
    public function testPagePostIsForbidden($url)
    {
        // On crée un client

        $client = static::createClient();

        // on se connecter en tant que ROLE_USER
        /**
         * @var UsersRepository
         */
        $userRepository = static::getContainer()->get(UsersRepository::class);
        $userTest = $userRepository->findOneBy(['email' => 'user@user.com']);
        $client->loginUser($userTest);

        // On exécute la requête APRES être loggué
        $client->request('GET', $url);

        // Le ROLE_USER aura un status code 403
        $this->assertResponseStatusCodeSame(403);
    }

    public function getUrls()
    {
        yield ['/admin'];
    }
}
