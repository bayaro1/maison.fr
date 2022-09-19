<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\Pro;
use App\Form\DataModel\Search;
use App\Form\SearchType;
use App\Repository\CityRepository;
use App\Repository\ProRepository;
use App\Security\Voter\ProVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProController extends AbstractController
{
    protected ProRepository $proRepository;
    protected EntityManagerInterface $em;

    public function __construct(ProRepository $proRepository, EntityManagerInterface $em)
    {
        $this->proRepository = $proRepository;
        $this->em = $em;
    }


    #[Route('{city_slug}/{category_slug}/pros', name: 'pro_index', requirements: ['category_slug' => '[a-z\-]+', 'city_slug' => '[a-z\-]+'])]
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

    #[Route('/pro/{id}', name: 'pro_show', requirements: ['id' => '\d+'])]
    public function show(Pro $pro): Response 
    {
        return $this->render('pro/show.html.twig', [
            'pro' => $pro
        ]);
    }

    #[Route('/pro/edit/{id}', name: 'pro_edit', requirements: ['id' => '\d+'])]
    public function edit(Pro $pro): Response 
    {
        $this->denyAccessUnlessGranted('CAN_EDIT', $pro, 'Vous ne pouvez pas éditer ce pro car vous nen etes pas le propriétaire');
        return $this->render('pro/edit.html.twig', [
            'pro' => $pro
        ]);
    }

    #[Route('pro-remove/{id}', name: 'pro_remove')]
    public function remove(Pro $pro, Request $request): Response
    {
        $businessName = $pro->getBusinessName();
        $this->em->remove($pro);
        $this->em->flush();
        $this->addFlash('success', 'le pro "'.$businessName.'" a bien été supprimé !');
        return $this->redirect($request->get('target', 'home'));
        
    }
}
