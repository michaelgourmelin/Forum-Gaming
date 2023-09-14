<?php

namespace App\tests\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoleAdminAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour ROLE_ADMIN
     * 
     * @dataProvider getUrls
     */
    public function testPageGetIsSuccessful($url)
    {
        // On crée un client

        $client = static::createClient();

        // on se connecter en tant que ROLE_ADMIN
        /**
         * @var UsersRepository
         */
        $userRepository = static::getContainer()->get(UsersRepository::class);
        $userTest = $userRepository->findOneBy(['email' => 'admin@admin.com']);
        $client->loginUser($userTest);

        // On exécute la requête APRES être loggué
        $client->request('GET', $url);

        // Le ROLE_ADMIN aura un status code 302
        $this->assertResponseStatusCodeSame(302);
    }

    public function getUrls()
    {
        yield ['/admin'];
    }
}
