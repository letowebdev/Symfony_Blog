<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach($this->CommentData() as [$content, $user, $post, $created_at])
        {
            $comment = new Comment;

            $user = $manager->getRepository(User::class)->find($user);
            $post = $manager->getRepository(Post::class)->find($post);

            $comment->setContent($content);
            $comment->setUser($user);
            $comment->setPost($post);
            $comment->setCreatedAtForFixtures(new \DateTime($created_at));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    private function CommentData()
    {
        return [

            ['Comment 1 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',1,2,'2020-6-08 12:34:45'],
            ['Comment 2 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',2,2,'2020-7-08 10:34:45'],
            ['Comment 3 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',3,1,'2020-5-08 23:34:45'],

            ['Comment 1 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',1,1,'2020-6-08 11:23:34'],
            ['Comment 2 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',4,2,'2020-6-08 15:17:06'],
            ['Comment 3 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',3,2,'2019-08-08 21:34:45'],
            ['Comment 4 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',3,3,'2019-08-08 22:34:45'],
            ['Comment 5 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',4,3,'2019-08-08 23:34:45']

        ];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }

}
