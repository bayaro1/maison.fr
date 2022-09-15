<?php
namespace App\Controller;

use App\Entity\Pro;
use ErrorException;
use App\Entity\City;
use App\Entity\Category;
use App\Form\DataModel\Register;
use App\DataFixtures\ProFixtures;
use App\Repository\ProRepository;
use App\DataFixtures\CityFixtures;
use App\Repository\CityRepository;
use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\TestUserFixtures;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Tests\Controller\AuthenticationTrait;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\DataFixtures\TestFixtures\UserTestFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class ProControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    protected KernelBrowser $client;
    protected UrlGeneratorInterface $urlGenerator;
    protected City $city;
    protected Category $category;
    protected User $user;
    protected EntityManagerInterface $em;

    public function setUp():void 
    {
        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->urlGenerator = $container->get(UrlGeneratorInterface::class);
        /** @var AbstractDatabaseTool */
        $dbtool = $container->get(DatabaseToolCollection::class)->get();
        $dbtool->loadFixtures([
            CityFixtures::class,
            CategoryFixtures::class,
            ProFixtures::class, 
            TestUserFixtures::class
        ]);
        $this->city = $container->get(CityRepository::class)->findOneBy([]);
        $this->category = $container->get(CategoryRepository::class)->findOneBy([]);
        $this->user = $container->get(UserRepository::class)->findOneBy([]);
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

    public function testRemoveDoRemovePro()
    {
        $test_email = $this->getUniqueEmail();
        $this->em->persist($this->createPro()->setEmail($test_email));
        $this->em->flush();

        $pro = $this->getContainer()->get(ProRepository::class)->findOneBy(['email' => $test_email]);

        $this->assertNotNull($pro, 'le pro n\'est pas persisté');

        $this->client->request('GET', $this->urlGenerator->generate('pro_remove', ['id' => $pro->getId()]));

        $pro = $this->getContainer()->get(ProRepository::class)->findOneBy(['email' => $test_email]);
        $this->assertNull($pro, 'le pro n\'est pas correctement supprimé');
    }

    private function getUniqueEmail():string 
    {
        return 'test'. random_int(0, 100000) .'@mail.com';
    }

    private function createPro():Pro
    {
        return (new Pro)
                ->setUser($this->user)
                ->setEmail($this->user->getEmail())
                ->setBusinessName('Entreprise du batiment')
                ->setContactName('Jean Contact')
                ->setCategories(new ArrayCollection([$this->category]))
                ->setCity($this->city)
                ->setPhone('0612131415')
                ->setDepartments('31, 64, 40')
                ;

    }


}