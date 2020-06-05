<?php
/**
 * In this test I'm going to check regardless the category I click 
 * I should get the same set of categories list urls and ids without 
 * interacting with database 
 */
namespace App\Tests\Utils;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $urlgenerator = $kernel->getContainer()->get('router');
        $this->mockedCategoryTreeFrontPage = $this->getMockBuilder('App\Utils\CategoryTreeFrontPage')
        ->disableOriginalConstructor()
        ->setMethods() // if no, all methods return null unless mocked
        ->getMock();

        $this->mockedCategoryTreeFrontPage->urlgenerator = $urlgenerator;
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






}
