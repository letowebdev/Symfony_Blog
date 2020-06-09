<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Category;
use App\Controller\Traits\Likes;
use App\Repository\PostRepository;
use App\Utils\CategoryTreeFrontPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    use Likes;

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
    * @Route("/delete-comment/{comment}", name="delete_comment")
    * @Security("user.getId() == comment.getUser().getId()")
    */
    public function deleteComment(Comment $comment, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
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

    public function mainCategories()
    {
        $categories =$this->getDoctrine()
        ->getRepository(Category::class)
        ->findBy(['parent'=>null], ['name' => 'ASC']);
        return $this->render('front/_main_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/post-list/{post}/like", name="like_post", methods={"POST"})
     * @Route("/post-list/{post}/dislike", name="dislike_post", methods={"POST"})
     * @Route("/post-list/{post}/unlike", name="undo_like_post", methods={"POST"})
     * @Route("/post-list/{post}/undodislike", name="undo_dislike_post", methods={"POST"})
     */
    public function toggleLikesAjax(Post $post, Request $request)
    {
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        switch($request->get('_route'))
        {
            case 'like_post':
            $result = $this->likePost($post);
            break;
            
            case 'dislike_post':
            $result = $this->dislikePost($post);
            break;

            case 'undo_like_post':
            $result = $this->undoLikePost($post);
            break;

            case 'undo_dislike_post':
            $result = $this->undoDislikePost($post);
            break;
        }

        return $this->json(['action' => $result,'id'=>$post->getId()]);
    }

    


}
