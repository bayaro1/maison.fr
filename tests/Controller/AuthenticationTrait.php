<?php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DataFixtures\TestUserFixtures;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\HttpFoundation\Session\SessionFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait AuthenticationTrait
{
    public function login(User $user, KernelBrowser $client):void 
    {
        /** @var SessionFactory */
        $sessionFactory = $client->getContainer()->get('session.factory');
        /** @var SessionInterface */
        $session = $sessionFactory->createSession();
        $session->set('_security_main', serialize(new UsernamePasswordToken($user, 'main', $user->getRoles())));
        $session->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
    }

    public function getTestUser(ContainerInterface $container):User
    {
        return $container->get(UserRepository::class)->findAll()[0];
    }

    public function loadTestUserFixtures(ContainerInterface $container):void 
    {
        /** @var AbstractDatabaseTool */
        $dbtool = $container->get(DatabaseToolCollection::class)->get();
        $dbtool->loadFixtures([TestUserFixtures::class]);
    }
}