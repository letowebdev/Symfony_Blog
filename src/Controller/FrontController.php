<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Category;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="main_page")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/post-list/category/{categoryname},{id}/{page}", defaults={"page": "1"}, name="post_list")
     */
    public function postList($id, $page, CategoryTreeFrontPage $categories, Request $request)
    {
        $ids = $categories->getChildIds($id);
        array_push($ids, $id);

        $posts = $this->getDoctrine()
        ->getRepository(Post::class)
        ->findByChildIds($ids ,$page, $request->get('sortby'));

        $categories->getCategoryListAndParent($id);
        // dump($categories);
        return $this->render('front/post_list.html.twig',[
            'subcategories' => $categories,
            'posts'=>$posts
        ]);
    }

     /**
     * @Route("/post-details", name="post_details")
     */
    public function postDetails()
    {
        return $this->render('front/post_details.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }


    /**
     * @Route("/search-results/{page}", methods={"GET"}, defaults={"page": "1"}, name="search_results")
     */
    public function searchResults($page, Request $request)
    {
        $posts = null;
        $query = null;

        if($query = $request->get('query'))
        {
            $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findByTitle($query, $page, $request->get('sortby'));

            if(!$posts->getItems()) $posts = null;
        }
       
        return $this->render('front/search_results.html.twig',[
            'posts' => $posts,
            'query' => $query,
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            dd('registering user ...');
        }
        return $this->render('front/register.html.twig',['form'=>$form->createView()]);
    }

   /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper)
    {
        return $this->render('front/login.html.twig', [
            'error' => $helper->getLastAuthenticationError()
            ]);
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout() : void
    {
        throw new \Exception('This should never be reached!');
    }


    public function mainCategories()
    {
        $categories =$this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['parent'=>null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);
    }






}
