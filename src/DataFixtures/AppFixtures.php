<?php

namespace App\DataFixtures;

use App\Config\CategoryConfig;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach(CategoryConfig::LIST as $category_name)
        {
            $category = (new Category)
                        ->setName($category_name)
                        ->setSlug(strtolower(str_replace(' ', '_', $category_name)))
                        ;
            $manager->persist($category);
        }


        $manager->flush();
    }
}
