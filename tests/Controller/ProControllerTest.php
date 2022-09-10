<?php
namespace App\Controller;

use App\DataFixtures\ProFixtures;
use App\DataFixtures\CityFixtures;
use App\Repository\CityRepository;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Category;
use App\Entity\City;
use App\Repository\CategoryRepository;
use App\Repository\ProRepository;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProControllerTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected UrlGeneratorInterface $urlGenerator;
    protected City $city;
    protected Category $category;

    public function setUp():void 
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->urlGenerator = $container->get(UrlGeneratorInterface::class);
        /** @var AbstractDatabaseTool */
        $dbtool = $container->get(DatabaseToolCollection::class)->get();
        $dbtool->loadFixtures([
            CityFixtures::class,
            CategoryFixtures::class,
            ProFixtures::class
        ]);
        $this->city = $container->get(CityRepository::class)->findOneBy([]);
        $this->category = $container->get(CategoryRepository::class)->findOneBy([]);
    }
    public function testResponseSuccessfull()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('pro_index', [
            'city_slug' => $this->city->getSlug(),
            'category_slug' => $this->category->getSlug()
        ]));
        $this->assertResponseIsSuccessful();
    }
    public function testTitle()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('pro_index', [
            'city_slug' => $this->city->getSlug(),
            'category_slug' => $this->category->getSlug()
        ]));
        $this->assertSelectorTextContains('title', $this->category->getName().' à '.$this->city->getFullName().' avec maison.fr');
    }
    public function testWrongParametersInUrl()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('pro_index', [
            'city_slug' => 'nimportequoi',
            'category_slug' => 'encorenimportequoi'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

}