<?php
namespace App\Controller;

use App\Entity\Pro;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\DataModel\Register;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'register_index')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        $register = new Register;
        $form = $this->createForm(RegisterType::class, $register);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) 
        { 
            $user = new User;
            $user
                ->setEmail($register->getEmail())
                ->setPassword($hasher->hashPassword($user, $register->getPassword()))
                ;
            $em->persist($user);

            $pro = (new Pro)
                    ->setBusinessName($register->getBusinessName())
                    ->setContactName($register->getContactName())
                    ->setPhone($register->getPhone())
                    ->setCity($register->getCity())
                    ->setCategories($register->getCategories())
                    ->setPictures(new ArrayCollection(($register->getPictures())))
                    ->setDepartments(implode(', ', $register->getDepartments()))
                    ->setUser($user)
                    ;
            $em->persist($pro);

            $em->flush();
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}