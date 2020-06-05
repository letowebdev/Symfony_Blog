<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\Category;


class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach($this->PostData() as [$title, $body, $photo, $category_id])
        {
            
            $category = $manager->getRepository(Category::class)->find($category_id);
            $post = new Post();
            $post->setTitle($title);
            $post->setBody($body);
            $post->setPhoto($photo);
            $post->setRelation($category);
            $post->setCreatedAt(new \DateTime());
            $manager->persist($post);
        }

        $manager->flush();
    }

    private function PostData()
    {
        return [

            ['Post 1','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 1],
            ['Post 2','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 2],
            ['Post 3','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 3],
            ['Post 4','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 5','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 5],
            ['Post 6','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 7','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],
            ['Post 8','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 6],
            ['Post 9','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 4],

            ['Post 1','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],
            ['Post 2','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],
            ['Family 3','This is some content...', 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png', 17],

            ['Post 1','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  19],
            ['Post 2','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  19],

            ['Post 1','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  20],

            ['Post  1','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  2','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  3','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  4','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  5','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2],
            ['Post  6','This is some content...','http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/post-it-icon.png',  2]

        ];
    }
}
