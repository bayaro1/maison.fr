<?php
namespace App\Controller;

use App\Form\RegisterType;
use App\Form\DataModel\Register;
use App\Persister\RegisterPersister;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register_index')]
    public function index(Request $request, RegisterPersister $registerPersister): Response
    {
        $register = new Register;
        $form = $this->createForm(RegisterType::class, $register);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) 
        { 
           $registerPersister->persist($register);
           $this->addFlash('success', 'l\'inscription s\'est bien passée !');
           return $this->redirectToRoute('account_index');
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}