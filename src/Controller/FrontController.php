<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/post-list", name="post_list")
     */
    public function postList()
    {
        return $this->render('front/post_list.html.twig', [
            'controller_name' => 'FrontController',
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
     * @Route("/search-results", methods={"POST"}, name="search_results")
     */
    public function searchResults()
    {
        return $this->render('front/search_results.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('front/register.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('front/login.html.twig');
    }








}
