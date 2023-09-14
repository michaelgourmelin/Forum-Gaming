<?php

namespace App\tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnonymousAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour Anonymous
     * 
     * @dataProvider getUrls
     */
    public function testPagePostIsForbidden($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseRedirects();
    }

    public function getUrls()
    {
        yield ['/admin'];
     
    }


}