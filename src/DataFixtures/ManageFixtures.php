<?php

namespace App\DataFixtures;

use App\Entity\Manage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ManageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manage = new Manage();
        $manage
            ->setOwner($this->getReference('user_1'))
            ->setMotion(false)
            ->setReboot(false)
            ->setHalt(false);
        $manager->persist($manage);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembersFixtures::class];
    }
}
