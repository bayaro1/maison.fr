<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private Crawler $crawler;

    public function setUp():void
    {
        parent::setUp();
        $this->client = static::createClient();
        $crawler = $this->client->request('GET', $this->client->getContainer()->get(UrlGeneratorInterface::class)->generate('home'));
    }
    public function testResponseIsSuccessfull(): void
    {
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseIsSuccessful();
    }

    public function testPageContent():void 
    {
        $this->assertSelectorExists('nav');
        $this->assertSelectorTextContains('h1', 'Prenez RDV avec un pro de l\'Habitat');
    }
}
