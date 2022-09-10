<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Helper\Slugator;
use App\Config\CategoryConfig;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $i = 0;
        foreach(CategoryConfig::LIST as $category_name)
        {
            $i++;
            $category = (new Category)
                        ->setName($category_name)
                        ->setSlug(Slugator::slugify($category_name))
                        ;
            $manager->persist($category);
            $this->addReference('category'.$i, $category);
        }


        $manager->flush();
    }
}
