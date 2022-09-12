<?php
namespace App\Controller;

use App\Form\Register;
use App\Form\RegisterType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register_index')]
    public function index(Request $request): Response
    {
        $register = new Register;
        $form = $this->createForm(RegisterType::class, $register);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) 
        { 
            //TODO enregistrer un user et un pro
            dd($register);
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}