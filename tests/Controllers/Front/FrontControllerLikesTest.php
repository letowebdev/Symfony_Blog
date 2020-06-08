<?php

namespace App\Tests;

use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerLikesTest extends WebTestCase
{
    use RoleUser;
    
    public function testLike()
    {
        $this->client->request('POST', '/post-list/16/like');
        $crawler = $this->client->request('GET', '/post-list/category/javascript,2');

        $this->assertSame('(1)', $crawler->filter('small.number-of-likes-16')->text());
    }

    public function testDislike()
    {

        $this->client->request('POST', '/post-list/10/dislike');
        $crawler = $this->client->request('GET', '/post-list/category/java,4');

        $this->assertSame('(4)', $crawler->filter('small.number-of-dislikes-10')->text());
    }

    public function testNumberOfLikedPost1()
    {
        $this->client->request('POST', '/post-list/2/like');
        $this->client->request('POST', '/post-list/2/like');

        $crawler = $this->client->request('GET', '/admin/posts');

        $this->assertEquals(5, $crawler->filter('tr')->count());
    }

    public function testNumberOfLikedPost2()
    {
        $this->client->request('POST', '/post-list/1/unlike');
        $this->client->request('POST', '/post-list/11/unlike');
        $this->client->request('POST', '/post-list/3/unlike');

        $crawler = $this->client->request('GET', '/admin/posts');

        $this->assertEquals(1, $crawler->filter('tr')->count());
    }
}
