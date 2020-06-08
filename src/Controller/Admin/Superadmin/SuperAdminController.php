<?php

namespace App\Controller\Admin\Superadmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/su")
 */
class SuperAdminController extends AbstractController {

    /**
     * @Route("/upload-post", name="upload_post")
     */
    public function uploadPost()
    {
        return $this->render('admin/upload_post.html.twig');
    }

    /**
     * @Route("/users", name="users")
     */
    public function users()
    {
        return $this->render('admin/users.html.twig');
    }

}