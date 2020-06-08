<?php

namespace App\Tests;

use App\Entity\User;
use App\Tests\RoleUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerUserAccountTest extends WebTestCase
{
    use RoleUser;
    
    public function testUserDeletedAccount()
    {
        $crawler = $this->client->request('GET', '/admin/delete-account');

        $user = $this->entityManager->getRepository(User::class)->find(4);
        $this->assertNull($user);
    }

    public function testUserChangedCredentials()
    {

        $crawler = $this->client->request('GET', '/admin/');

        $form = $crawler->selectButton('Save')->form([

            'user[name]' => 'name',
            'user[last_name]' => 'last name',
            'user[email]' => 'email@email.email',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password'
        ]);
        $this->client->submit($form);

       $user = $this->entityManager->getRepository(User::class)->find(4);

        $this->assertSame('name',$user->getName());

    }
}
