<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach($this->PostData() as [$title, $user,  $body, $photo, $category_id])
        {
            
            $category = $manager->getRepository(Category::class)->find($category_id);
            $user = $manager->getRepository(User::class)->find($user);
            
            $post = new Post();

            $post->setTitle($title);
            $post->setUser($user);
            $post->setBody($body);
            $post->setPhoto($photo);
            $post->setCatgory($category);
            $post->setCreatedAt(new \DateTime());
            $manager->persist($post);
        }

        $manager->flush();
        $this->loadLikes($manager);
        $this->loadDislikes($manager);
    }

    public function loadLikes($manager)
    {
        foreach($this->likesData() as [$post_id, $user_id])
        {

            $post = $manager->getRepository(Post::class)->find($post_id);
            $user = $manager->getRepository(User::class)->find($user_id);

            $post->addUsersThatLike($user);
            $manager->persist($post);
        }
    
            $manager->flush();
        
    }

    public function loadDislikes($manager)
    {
        foreach($this->dislikesData() as [$post_id, $user_id])
        {

            $post = $manager->getRepository(Post::class)->find($post_id);
            $user = $manager->getRepository(User::class)->find($user_id);

            $post->addUsersThatDontLike($user);
            $manager->persist($post);
        }

        $manager->flush();
       
    }

    private function PostData()
    {
        return [

            ['Post 1', 1, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 1],
            ['Post 2', 1, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 2],
            ['Post 3', 1, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 3],
            ['Post 4', 1, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 5', 1, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 5],
            ['Post 6', 2, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 7', 2, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 8', 2, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 6],
            ['Post 9', 2, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],

            ['Post 1', 3, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],
            ['Post 2', 3, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],
            ['Family 3', 3, 'This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],

            ['Post 1', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  19],
            ['Post 2', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  19],

            ['Post 1', 1, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  20],

            ['Post  1', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  2', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  3', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  4', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  5', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  6', 4, 'This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2]

        ];
    }

    private function likesData()
    {
            return [
    
                [12,1],
                [12,2],
                [12,3],
    
                [11,1],
                [11,4],
    
                [1,1],
                [1,4],
                [3,4],
    
                [2,1],
                [2,2]
    
            ];
    }

    private function dislikesData()
    {
        return [

            [10,1],
            [10,2],
            [10,3]

        ];
    }
}
