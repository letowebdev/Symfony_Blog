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
       $this->loadPHP($manager);
       $this->loadJavaScript($manager);
       $this->loadGO($manager);
       $this->loadJava($manager);
       $this->loadNodeJS($manager);
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

    private function loadPHP($manager)
    {
        $this->loadSubcategories($manager, 'PHP', 1);
    }

    private function loadJavaScript($manager)
    {
        $this->loadSubcategories($manager, 'JavaScript', 2);
    }

    private function loadGO($manager)
    {
        $this->loadSubcategories($manager, 'GO', 3);
    }

    private function loadJava($manager)
    {
        $this->loadSubcategories($manager, 'Java', 4);
    }

    private function loadNodeJS($manager)
    {
        $this->loadSubcategories($manager, 'NodeJS', 10);
    }





    private function loadSubcategories($manager,  $category, $parent_id)
    {
        $parent = $manager->getRepository(Category::class)->find($parent_id);
        $methodName = "get{$category}Data";

        foreach ($this->$methodName() as [$name]) 
        {
            $category = new Category();
            $category->setName($name);
            $category->setParent($parent);
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

    private function getJavaScriptData()
    {
        return [
            ['VanillaJS', 9],
            ['NodeJS', 10],
            ['Deno', 11],
            ['React', 12]
        ];
    }

    private function getGOData()
    {
        return [
            ['GOsubCategory1', 13],
            ['GOsubCategory2', 14]
        ];
    }

    private function getJavaData()
    {
        return [
            ['JavaSubcategory1', 15],
            ['JavaSubcategory2', 16],
            ['JavaSubcategory3', 17]
        ];
    }


    private function getNodeJSData()
    {
        return [
            ['Express', 18],
            ['mongoose', 19],
            ['sequelize', 20]
        ];
    }






}
