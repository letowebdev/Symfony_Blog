<?php

namespace App\Controllers\Admin;


use App\Tests\RoleAdmin;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerPostTest extends WebTestCase
{

    use RoleAdmin;

    public function testDeletePost()
    {
        $this->client->request('GET', '/admin/su/delete-post/99');
        $post = $this->entityManager->getRepository(Post::class)->find(99);
        $this->assertNull($post);
    }

   
}