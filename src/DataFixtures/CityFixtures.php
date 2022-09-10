<?php

namespace App\DataFixtures;

use App\Helper\Slugator;
use App\Config\CityConfig;
use App\Entity\City;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $i = 0;
        foreach(CityConfig::LIST as $postalCode => $name)
        {
            $i++;
            $city = (new City)
                        ->setName($name)
                        ->setSlug(Slugator::slugify($name))
                        ->setPostalCode($postalCode)
                        ->setDepartmentCode(substr($postalCode, 0, 2))
                        ;
            $manager->persist($city);
            $this->addReference('city'.$i, $city);
        }
        $manager->flush();
    }
}
