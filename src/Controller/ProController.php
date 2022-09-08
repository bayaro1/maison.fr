<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProController extends AbstractController
{
    #[Route('{city}/{category}/pros', name: 'pro_index')]
    public function index($city, $category): Response
    {
        return $this->render('pro/index.html.twig', [
            'city' => $city,
            'category' => $category
        ]);
    }
}
