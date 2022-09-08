<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('pro_index', [
            'city' => 'paris',
            'category' => 'papier-peint'
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'papier-peint à Paris avec maison.fr');
    }
}