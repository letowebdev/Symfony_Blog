<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $this->loadMainCategories($manager);
       $this->loadSubCategories($manager, 'PHP', 1);
    }

    private function loadSubCategories($manager,  $category, $parent_id)
    {
        $parent = $manager->getRepository(Category::class)->find($parent_id);
        $methodName = "get{$category}Data";

        foreach ($this->getPHPData() as [$name]) {
            $category = new Category();
            $category->setName($name);
            $category->setParent($parent);
            $manager->persist($category);
        }
        

        $manager->flush();
    }

    private function loadMainCategories($manager)
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
            ['GO', 3],
            ['Java', 4]
        ];
    }

    private function getPHPData()
    {
        return [
            ['Symfony', 5],
            ['Laravel', 6],
            ['PHP API', 7],
            ['PHP UnitTests', 8]
        ];
    }









}
