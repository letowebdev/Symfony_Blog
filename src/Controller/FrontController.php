<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
     * @Route("/post-details/{post}", name="post_details")
     */
    public function postDetails(PostRepository $repo, $post)
    {
        return $this->render('front/post_details.html.twig', [
            'post' => $repo->postDetails($post),
        ]);
    }

    /**
     * @Route("/new-comment/{post}", methods={"POST"}, name="new_comment")
    */
    public function newComment(Post $post, Request $request )
     {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        
        if ( !empty( trim($request->request->get('comment')) ) ) 
        {   

            // $post = $this->getDoctrine()->getRepository(post::class)->find($post_id);
        
            $comment = new Comment();
            $comment->setContent($request->request->get('comment'));
            $comment->setUser($this->getUser());
            $comment->setPost($post); //symfony will find the post using param converter

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }
        
        return $this->redirectToRoute('post_details',['post'=>$post->getId()]);
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
    public function register(UserPasswordEncoderInterface $password_encoder, Request $request)
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
    
            $user->setName($request->request->get('user')['name']);
            $user->setLastName($request->request->get('user')['last_name']);
            $user->setEmail($request->request->get('user')['email']);
            $password = $password_encoder->encodePassword($user, $request->request->get('user')['password']['first']);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->loginUserAutomatically($user, $password);

            return $this->redirectToRoute('admin_main_page');
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

    private function loginUserAutomatically($user, $password)
    {
        $token = new UsernamePasswordToken(
            $user,
            $password,
            'main', // security.yaml
            $user->getRoles()
        );
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main',serialize($token));
    }





}
