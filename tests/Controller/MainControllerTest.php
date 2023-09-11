<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * Test de la home
     */
    public function testCategories(): void
    {
        // création d'un client HTTP
        $client = static::createClient();
        // on exécute une requête HTTP
        $crawler = $client->request('GET', '/');

        // on teste le résultat/la réponse
        // status HTTP 200
        $this->assertResponseIsSuccessful(); // 200 et +
        // on s'assure que le p du HTML reçu contient la bonne string
        $this->assertSelectorTextContains('p', 'Toutes les catégories :');
    }
}
