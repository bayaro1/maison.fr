<?php

namespace App\Controller;

use App\Form\DataModel\Search;
use App\Form\SearchType;
use App\Helper\Slugator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $search = new Search;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        { 
            return $this->redirectToRoute('pro_index', [
                'city' => Slugator::slugify($search->getCity()), 
                'category' => $search->getCategory()->getSlug()
            ]);
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
