<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController {

    /**
     * @Route("/", name="admin_main_page")
     */
    public function index()
    {
        return $this->render('admin/my_profile.html.twig');
    }
    /**
     * @Route("/posts", name="posts")
     */
    public function posts()
    {
        if ($this->isGranted('ROLE_ADMIN')) 
        {
            $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        }
        else
        {
            $posts = $this->getUser()->getLikedPosts();
        }
        
        return $this->render('admin/posts.html.twig',[
        'posts'=>$posts
        ]);
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $categories->getCategoryList($categories->buildTree());
        return $this->render('admin/_all_categories.html.twig',[
            'categories' => $categories,
            'editedCategory' => $editedCategory
        ]);
    }
}