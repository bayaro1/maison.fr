<?php
namespace App\Form\DataModel;

use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Category;

class Search
{
    #[Assert\NotBlank(message: 'Vous devez choisir une catégorie de travaux')]
    public Category $category;

    #[Assert\NotBlank(message: 'Vous devez indiquer une ville')]
    public string $city;
}