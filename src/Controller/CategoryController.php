<?php 
namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {

    }


    #[Route('/category-search/{q}', name: 'category_search')]
    function search(string $q): Response
    {
        $results = $this->categoryRepository->findByQ($q);
        return new Response(json_encode($results));
    }
}