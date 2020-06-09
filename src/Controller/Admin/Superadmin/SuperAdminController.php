<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Entity\Category;
use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/su")
 */
class SuperAdminController extends AbstractController {

    /**
     * @Route("/create-post", name="create_post")
     */
    public function createPost(Request $request, UploaderInterface $fileUploader)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
       
            $em = $this->getDoctrine()->getManager();

            $file = $post->getUploadedImage();
            $fileName = $fileUploader->upload($file);
            // $fileName = 'to do';

          

            $base_path = Post::uploadFolder;
            $post->setPhoto($base_path.$fileName[0]);
            $post->setTitle($fileName[1]);

            $post->setTitle($request->request->get('post')['title']);
            $post->setBody($request->request->get('post')['body']);
            $post->setUser($this->getUser());
            $post->setCreatedAt(new \DateTime());
            // $post->setBody($request->request->get('post')['body']);
           
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('posts');
        }
        return $this->render('admin/create_post.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-post/{post}/{path}", name="delete_post", requirements={"path"=".+"})
     */
    public function deletePost(Post $post, $path, UploaderInterface $fileUploader)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        if( $fileUploader->delete($path) )
        {
            $this->addFlash(
                'success',
                'The post was successfully deleted.'
            );
        }
        else
        {
            $this->addFlash(
                'danger',
                'We were not able to delete. Check the post.'
            );
        }
        
        return $this->redirectToRoute('posts');

    }

    /**
     * @Route("/update-post-category/{post}", methods={"POST"}, name="update_post_category")
    */
    public function updatePostCategory(Request $request, Post $post)
     {

        $em = $this->getDoctrine()->getManager();

        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('post_category'));

        $post->setCategory($category);

        $em->persist($post);
        $em->flush();
 
        return $this->redirectToRoute('posts');
     }

    /**
     * @Route("/users", name="users")
     */
    public function users()
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findBy([], ['name' => 'ASC']);
        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/delete-user/{user}", name="delete_user")
     */
    public function deleteUser(User $user)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('users');
     }

}