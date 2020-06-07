<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerSecurityTest extends WebTestCase
{
    /**
     * @dataProvider getSecureUrls
     */
    public function testSecureUrls($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertContains('/login', $client->getResponse()->getTargetUrl());
    }

    public function getSecureUrls()
    {
        yield ['/admin/posts'];
        yield ['/admin'];
        yield ['/admin/su/categories'];
        yield ['/admin/su/delete-category/1'];
    }
}
