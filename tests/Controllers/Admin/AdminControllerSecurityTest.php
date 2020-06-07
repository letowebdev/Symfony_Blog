<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerSecurityTest extends WebTestCase
{
     /**
     * @dataProvider getUrlsForRegularUsers
     */
    public function testAccessDeniedForRegularUsers($httpMethod, $url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'karim@symf5.de',
            'PHP_AUTH_PW' => 'passw',
        ]);

        $client->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/admin/su/categories'];
        yield ['GET', '/admin/su/edit-category/1'];
        yield ['GET', '/admin/su/delete-category/2'];
        yield ['GET', '/admin/su/users'];
        yield ['GET', '/admin/su/upload-post'];
    }

    public function testAdminSu()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'leto@symf5.de',
            'PHP_AUTH_PW' => 'passw',
        ]);

        $crawler = $client->request('GET', '/admin/su/categories');

        $this->assertSame('Categories list', $crawler->filter('h2')->text());
    }

}
