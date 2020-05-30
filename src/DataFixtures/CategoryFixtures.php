<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $this->loadMainCategoriesData($manager);
    }

    private function loadMainCategoriesData($manager)
    {
        foreach ($this->getMainCategoriesData() as [$name]) {
            
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }
        

        $manager->flush();
    }

    private function getMainCategoriesData()
    {
        return [
            ['PHP', 1],
            ['JavaScript', 2],
            ['TypeScript', 3],
            ['GO', 4],
            ['Java', 5],
            ['Python',  6]
        ];
    }








}
