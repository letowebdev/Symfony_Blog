<?php
namespace App\Controller\Traits;

use App\Entity\User;

trait Likes {
    
    private function likePost($post)
    {  
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->addLikedPost($post);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush(); 
        return 'liked';
    }
    private function dislikePost($post)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->addDislikedPost($post);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush(); 
        return 'disliked';
    }
    private function undoLikePost($post)
    {  
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->removeLikedPost($post);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush(); 
        return 'undo liked';
    }
    private function undoDislikePost($post)
    {   
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user->removeDislikedPost($post);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return 'undo disliked';
    }

}