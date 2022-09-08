<?php

namespace App\Tests;

use App\Entity\Category;
use App\Repository\CategoryRepository;
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
        $this->crawler = $this->client->request('GET', $this->client->getContainer()->get(UrlGeneratorInterface::class)->generate('home'));
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

    public function testSearch():void 
    {
        $categories = self::getContainer()->get(CategoryRepository::class)->findAll();
        /** @var Category */
        $category = $categories[random_int(0, count($categories) - 1)];
        $form = $this->crawler->selectButton('Rechercher')->form([
            'city' => 'Paris',
            'category' => $category->getId()
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects($this->client->getContainer()->get(UrlGeneratorInterface::class)->generate('pro_index', [
            'city' => 'paris',
            'category' => $category->getSlug()
        ]));
    }
}
