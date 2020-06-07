<?php

namespace App\Tests\Utils;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;
    protected $mockedCategoryTreeAdminList;
    protected $mockedCategoryTreeAdminOptionList;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $urlgenerator = $kernel->getContainer()->get('router');
        $tested_classes = [
            'CategoryTreeAdminList',
            'CategoryTreeAdminOptionList',
            'CategoryTreeFrontPage'
        ];
        foreach($tested_classes as $class)
        {
            $name = 'mocked'.$class;
            $this->$name = $this->getMockBuilder('App\Utils\\'.$class)
            ->disableOriginalConstructor()
            ->setMethods() // if no, all methods return null unless mocked
            ->getMock();
            $this->$name->urlgenerator = $urlgenerator;
        }
    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id)
    {
        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension;
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);
        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    /**
     * @dataProvider dataForCategoryTreeAdminOptionList
     */
    public function testCategoryTreeAdminOptionList($arrayToCompare, $arrayFromDb)
    {
        $this->mockedCategoryTreeAdminOptionList->categoriesArrayFromDb = $arrayFromDb;
        $arrayFromDb = $this->mockedCategoryTreeAdminOptionList->buildTree();
        $this->assertSame($arrayToCompare, $this->mockedCategoryTreeAdminOptionList->getCategoryList($arrayFromDb));
    }

    /**
     * @dataProvider dataForCategoryTreeAdminList
     */
    public function testCategoryTreeAdminList($string, $array)
    {
        $this->mockedCategoryTreeAdminList->categoriesArrayFromDb = $array;
        $array = $this->mockedCategoryTreeAdminList->buildTree();
        $this->assertSame($string, $this->mockedCategoryTreeAdminList->getCategoryList($array));
    }

    public function dataForCategoryTreeFrontPage()
    {
        yield [
            '<ul><br><li><a href="/post-list/category/symfony,5">Symfony</a></li><br><li><a href="/post-list/category/laravel,6">Laravel</a></li><br><li><a href="/post-list/category/php-api,7">PHP API</a></li><br><li><a href="/post-list/category/php-unittests,8">PHP UnitTests</a></li><br></ul>',
            [ 
                ['name'=>'PHP','id'=>1, 'parent_id'=>null],
                ['name'=>'Symfony','id'=>5, 'parent_id'=>1],
                ['name'=>'Laravel','id'=>6, 'parent_id'=>1],
                ['name'=>'PHP API','id'=>7, 'parent_id'=>1],
                ['name'=>'PHP UnitTests','id'=>8, 'parent_id'=>1]
            ],
            1
        ];

        yield [
            '<ul><br><li><a href="/post-list/category/gosubcategory1,13">GOsubCategory1</a></li><br><li><a href="/post-list/category/gosubcategory2,14">GOsubCategory2</a></li><br></ul>',
            [ 
                ['name'=>'GO','id'=>3, 'parent_id'=>null],
                ['name'=>'GOsubCategory1','id'=>13, 'parent_id'=>3],
                ['name'=>'GOsubCategory2','id'=>14, 'parent_id'=>3]
            ],
            3
        ];

        yield [
            '<ul><br><li><a href="/post-list/category/javasubcategory1,15">JavaSubcategory1</a></li><br><li><a href="/post-list/category/javasubcategory2,16">JavaSubcategory2</a></li><br><li><a href="/post-list/category/javasubcategory3,17">JavaSubcategory3</a></li><br></ul>',
            [ 
                ['name'=>'Java','id'=>4, 'parent_id'=>null],
                ['name'=>'JavaSubcategory1','id'=>15, 'parent_id'=>4],
                ['name'=>'JavaSubcategory2','id'=>16, 'parent_id'=>4],
                ['name'=>'JavaSubcategory3','id'=>17, 'parent_id'=>4]
            ],
            4
        ];

        yield [
            '<ul><br><li><a href="/post-list/category/vanillajs,9">VanillaJS</a></li><br><li><a href="/post-list/category/nodejs,10">NodeJS</a><ul><br><li><a href="/post-list/category/express,18">Express</a></li><br><li><a href="/post-list/category/mongoose,19">mongoose</a></li><br><li><a href="/post-list/category/sequelize,20">sequelize</a></li><br></ul></li><br><li><a href="/post-list/category/deno,11">Deno</a></li><br><li><a href="/post-list/category/react,12">React</a></li><br></ul>',
            [ 
                ['name'=>'JavaScript','id'=>2, 'parent_id'=>null],
                ['name'=>'VanillaJS','id'=>9, 'parent_id'=>2],
                ['name'=>'NodeJS','id'=>10, 'parent_id'=>2],
                ['name'=>'Express','id'=>18, 'parent_id'=>10],
                ['name'=>'mongoose','id'=>19, 'parent_id'=>10],
                ['name'=>'sequelize','id'=>20, 'parent_id'=>10],
                ['name'=>'Deno','id'=>11, 'parent_id'=>2],
                ['name'=>'React','id'=>12, 'parent_id'=>2]
            ],
            2
        ];
    }

    public function dataForCategoryTreeAdminOptionList()
    {
        yield [
            [
                ['name'=>'JavaScript','id'=>2],
                ['name'=>'--VanillaJs','id'=>9],
                ['name'=>'--NodeJS','id'=>10],
                ['name'=>'----Express','id'=>18]
            ],
            [ 
                ['name'=>'JavaScript','id'=>2, 'parent_id'=>null],
                ['name'=>'VanillaJs','id'=>9, 'parent_id'=>2],
                ['name'=>'NodeJS','id'=>10, 'parent_id'=>2],
                ['name'=>'Express','id'=>18, 'parent_id'=>10]
            ]
         ];
    }

    public function dataForCategoryTreeAdminList()
    {
        yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>  PHP<a href="/admin/su/edit-category/1"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/1">Delete</a></li></ul>',
            [ ['id'=>1,'parent_id'=>null,'name'=>'PHP'] ]
         ];

         yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>  PHP<a href="/admin/su/edit-category/1"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/1">Delete</a></li><li><i class="fa-li fa fa-arrow-right"></i>  JavaScript<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/2">Delete</a></li></ul>',
            [
                ['id'=>1,'parent_id'=>null,'name'=>'PHP'],
                ['id'=>2,'parent_id'=>null,'name'=>'JavaScript']
            ]
         ];

         yield [
            '<ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>  JavaScript<a href="/admin/su/edit-category/2"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/2">Delete</a></li><li><i class="fa-li fa fa-arrow-right"></i>  Go<a href="/admin/su/edit-category/3"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/3">Delete</a><ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>  NodeJS<a href="/admin/su/edit-category/4"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/4">Delete</a><ul class="fa-ul text-left"><li><i class="fa-li fa fa-arrow-right"></i>  Symfony<a href="/admin/su/edit-category/5"> Edit</a> <a onclick="return confirm(\'Are you sure?\');" href="/admin/su/delete-category/5">Delete</a></li></ul></li></ul></li></ul>',

            [
                ['id'=>2,'parent_id'=>null,'name'=>'JavaScript'],
                ['id'=>3,'parent_id'=>null,'name'=>'Go'],
                ['id'=>4,'parent_id'=>3,'name'=>'NodeJS'],
                ['id'=>5,'parent_id'=>4,'name'=>'Symfony']
            ]
         ];
    }


}
