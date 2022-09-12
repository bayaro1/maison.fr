<?php
namespace App\Tests\Controller;

use App\DataFixtures\TestUserFixtures;
use Symfony\Component\DomCrawler\Form;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class SecurityControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testLoginWithGoodCredentials()
    {
        $client = self::createClient();
        
        $this->loadTestUserFixtures($client->getContainer());

        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('home'));
    }
    public function testLoginWithBadCredentials()
    {
        $client = self::createClient();
        $this->loadTestUserFixtures($client->getContainer());

        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $form = $crawler->selectButton('Sign in')->form([
            'email' => 'user@gmail.com',
            'password' => 'badpassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }
}