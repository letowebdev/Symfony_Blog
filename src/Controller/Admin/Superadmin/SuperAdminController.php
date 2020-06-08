<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
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
    public function createPost(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
       
            $em = $this->getDoctrine()->getManager();

            $file = $post->getUploadedImage();
            // $fileName = $fileUploader->upload($file);
            $fileName = 'to do';

          

            $base_path = Post::uploadFolder;
            $post->setPhoto($base_path.$fileName);

            $post->setBody($request->request->get('post')['title']);
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