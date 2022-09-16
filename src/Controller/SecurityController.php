<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //traduction du message dans le cas d'une connexion simple
        if($error && !in_array($error->getCode(), [AppAuthenticator::APP_2FA_ERROR, AppAuthenticator::APP_CONFIRMED_ERROR]))
        {
            $error = new AuthenticationException('Identifiants invalides');
        }

        $form = $this->createForm(LoginType::class, null, [
            'view2FA' => $error && $error->getCode() === AppAuthenticator::APP_2FA_ERROR,
            'lastUsername' => $request->getSession()->get(AppAuthenticator::LAST_USERNAME),
            'lastPassword' => $request->getSession()->get(AppAuthenticator::LAST_PASSWORD),
            'lastCode2FA' => $request->getSession()->get(AppAuthenticator::LAST_CODE_2FA),
            'lastRememberMe' => $request->getSession()->get(AppAuthenticator::LAST_REMEMBER_ME)
        ]);

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/confirmation-de-l-email', name: 'security_emailConfirmation')]
    public function emailConfirmation(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($request->get('id'));
        if($user === null || $user->getToken() !== $request->get('token'))
        {
            throw new NotFoundHttpException('Le lien utilisé n\'est pas valide');
        }
        $user->setConfirmed(true)
                ->setToken(null);
        $em->flush();
        $this->addFlash('success', 'Votre adresse email a été confirmée !');
        return $this->redirectToRoute('security_login');
    }
}
