<?php
namespace App\Form\DataModel;

use App\Entity\City;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class Search
{
    #[Assert\NotBlank(message: 'Vous devez choisir une catégorie de travaux')]
    protected Category|null $category;

    #[Assert\NotBlank(message: 'Vous devez indiquer une ville')]
    protected City|null $city;

    


    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
}