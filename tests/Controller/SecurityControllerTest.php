<?php
namespace App\Tests\Controller;

use App\DataFixtures\TestUserFixtures;
use App\DataFixtures\TestUsersFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\DomCrawler\Form;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class SecurityControllerTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testLoginWithGoodCredentialsButUnconfirmedUser()
    {
        $client = self::createClient();
        
        $this->loadTestUsersFixtures($client->getContainer());

        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $form = $crawler->selectButton('Connexion')->form([
            'email' => TestUsersFixtures::USER_EMAIL,
            'password' => TestUsersFixtures::PASSWORD
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginWithConfirmedUserButBadCredentials()
    {
        $client = self::createClient();
        $this->loadTestUsersFixtures($client->getContainer());

        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $form = $crawler->selectButton('Connexion')->form([
            'email' => TestUsersFixtures::USER_CONFIRMED_EMAIL,
            'password' => TestUsersFixtures::PASSWORD
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginWithGoodCredentialsAndConfirmedUser()
    {
        $client = self::createClient();
        
        $this->loadTestUsersFixtures($client->getContainer());

        $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
        $form = $crawler->selectButton('Connexion')->form([
            'email' => TestUsersFixtures::USER_CONFIRMED_EMAIL,
            'password' => TestUsersFixtures::PASSWORD
        ]);
        $client->submit($form);
        $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('home'));
    }

    // public function testLoginWithGoodCredentialsAndConfirmedUserButNeeds2FA()
    // {
    //     $client = self::createClient();
        
    //     $this->loadTestUsersFixtures($client->getContainer());

    //     $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
    //     $form = $crawler->selectButton('Connexion')->form([
    //         'email' => TestUsersFixtures::USER_CONFIRMED_WITH_2FA_EMAIL,
    //         'password' => TestUsersFixtures::PASSWORD
    //     ]);
    //     $client->submit($form);
    //     $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
    //     $client->followRedirect();
    //     $this->assertSelectorExists('.alert.alert-danger');
    // }

    // public function testLoginWith2FASubmited()
    // {
    //     $client = self::createClient();
        
    //     $this->loadTestUsersFixtures($client->getContainer());

    //     $crawler = $client->request('GET', $client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
    //     $form = $crawler->selectButton('Connexion')->form([
    //         'email' => TestUsersFixtures::USER_CONFIRMED_WITH_2FA_EMAIL,
    //         'password' => TestUsersFixtures::PASSWORD
    //     ]);
    //     $client->submit($form);
    //     $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('security_login'));
    //     $client->followRedirect();

    //     /** @var User */
    //     $user = self::getContainer()->get(UserRepository::class)->findOneBy(['email' => TestUsersFixtures::USER_CONFIRMED_WITH_2FA_EMAIL]);

    //     $form = $crawler->selectButton('Connexion')->form([
    //         'email' => TestUsersFixtures::USER_CONFIRMED_WITH_2FA_EMAIL,
    //         'password' => TestUsersFixtures::PASSWORD,
    //         '2FA' => $user->getCode2FA()
    //     ]);
    //     $client->submit($form);
    //     $this->assertResponseRedirects($client->getContainer()->get(UrlGeneratorInterface::class)->generate('home'));
    // }
}