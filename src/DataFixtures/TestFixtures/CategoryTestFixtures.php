<?php

namespace App\DataFixtures\TestFixtures;

use App\Config\CategoryConfig;
use App\Entity\Category;
use App\Helper\Slugator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach(CategoryConfig::LIST as $category_name)
        {
            $category = (new Category)
                        ->setName($category_name)
                        ->setSlug(Slugator::slugify($category_name))
                        ;
            $manager->persist($category);
        }

        $manager->flush();
    }
}
