<?php

namespace App\DataFixtures;

use App\Entity\Members;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MembersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $member = new Members();
        $member
            ->setUsername('fred')
            ->setRoles(["ROLE_USER"])
            ->setPassword($this->userPasswordHasher->hashPassword(
                $member,
                'fred'
            ))
            ->setEmail('fred@email.com');
        $manager->persist($member);
        $this->addReference('user_1', $member);
        $manager->flush();
    }

}
