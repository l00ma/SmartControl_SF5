<?php

namespace App\DataFixtures;

use App\Entity\LedsStrip;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LedsstripFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $ledstrip = new LedsStrip();
        $ledstrip
            ->setItem('Rampe_leds')
            ->setOwner($this->getReference('user_1'))
            ->setEtat(0)
            ->setRgb('255,0,0')
            ->setHOn('21h00')
            ->setHOff('23h00')
            ->setTimer(1)
            ->setEmail(0)
            ->setEffet(1);
        $manager->persist($ledstrip);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembersFixtures::class];
    }
}
