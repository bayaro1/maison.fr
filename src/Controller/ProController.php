<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Form\DataModel\Search;
use App\Form\SearchType;
use App\Repository\CityRepository;
use App\Repository\ProRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProController extends AbstractController
{
    protected ProRepository $proRepository;

    public function __construct(ProRepository $proRepository)
    {
        $this->proRepository = $proRepository;
    }


    #[Route('{city_slug}/{category_slug}/pros', name: 'pro_index')]
    #[ParamConverter('city', options: ['mapping' => ['city_slug' => 'slug']])]
    #[ParamConverter('category', options: ['mapping' => ['category_slug' => 'slug']])]
    public function index(City $city, Category $category, Request $request): Response
    {
        $search = (new Search)
                    ->setCategory($category)
                    ->setCity($city)
                    ;
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        { 
            return $this->redirectToRoute('pro_index', [
                'city_slug' => $search->getCity()->getSlug(), 
                'category_slug' => $search->getCategory()->getSlug()
            ]);
        }

        $pros = $this->proRepository->findByDepartmentAndCategoryHydratedWithFirstPicture($city->getDepartmentCode(), $category);

        return $this->render('pro/index.html.twig', [
            'city' => $city,
            'category' => $category,
            'form' => $form->createView(), 
            'pros' => $pros
        ]);
    }
}
