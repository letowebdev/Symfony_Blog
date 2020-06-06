<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerPostTest extends WebTestCase
{
    public function testNoResults()
    {
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');
    
        $form = $crawler->selectButton('Search')->form([
            'query' => 'my cat is awesome',
        ]);
        $crawler = $client->submit($form);

        $this->assertContains('No results were found!', $crawler->filter('h1')->text());
    }

    public function testResultsFound()
    {
        $client = static::createClient();
        $client->followRedirects();
        
        $crawler = $client->request('GET', '/');
    
        $form = $crawler->selectButton('Search')->form([
            'query' => 'post',
        ]);
        $crawler = $client->submit($form);

        $this->assertGreaterThan(2, $crawler->filter('h3')->count());
    }
}
