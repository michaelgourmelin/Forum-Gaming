<?php

namespace App\tests\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ThemeControllerTest extends WebTestCase
{
    /**
     * Add theme test en Anonyme
     */
    public function testThemeAnonymous(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ajout');

        $this->assertResponseRedirects();
    }


    /**
     * ROLE_USER Form soumis vide => 422
     * ce test permet de s'assurer que les contraintes de validation sont en place
     */
    public function testThemePostFail(): void
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
      

        // on doit accède à la page de theme
        $crawler = $client->request('GET', '/ajout');
        // qui doît être accessible
        $this->assertResponseIsSuccessful();

        //todo
        // gestion du form
        // on cible le bouton du form
        // $buttonCrawlerNode = $crawler->selectButton('Valider');
        // on récupère le form associé
        // $form = $buttonCrawlerNode->form();
        // on soumet le form
        // $client->submit($form);
     
        // on attend une 422
        // $this->assertResponseIsUnprocessable();
    }

  
}
