<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'account_index')]
    #[IsGranted('ROLE_USER')]
    public function index():Response
    {
        return $this->render('account/index.html.twig');
    }
}