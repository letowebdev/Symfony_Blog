<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$name, $last_name, $email, $password, $roles])
        {
            $user = new User();
            $user->setName($name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($this->password_encoder->encodePassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData()
    {
        return [

            ['zache', 'leto', 'leto@symf5.de', 'passw', ['ROLE_ADMIN']],
            ['zache', 'rahil', 'rahil@symf5.de', 'passw', ['ROLE_ADMIN']],
            ['zache', 'karim', 'karim@symf5.de', 'passw', ['ROLE_USER']],
            ['zache', 'djamila', 'djamila@symf5.de', 'passw', ['ROLE_USER']]

        ];
    }
}
