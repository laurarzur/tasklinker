<?php

namespace App\DataFixtures;

use App\Factory\EmployeFactory;
use App\Factory\ProjetFactory;
use App\Factory\StatutFactory;
use App\Factory\TacheFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EmployeFactory::createMany(8);
        ProjetFactory::createMany(12);
        StatutFactory::createStatuts();
        TacheFactory::createMany(34);
    }
}
