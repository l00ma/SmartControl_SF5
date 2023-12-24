<?php

namespace App\DataFixtures;

use App\Entity\MeteoMemory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MeteoMemoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $date = $faker->dateTimeThisMonth();
        $dateTime = \DateTimeImmutable::createFromMutable($date);

        $meteoMemory = new MeteoMemory();
        $meteoMemory
            ->setOwner($this->getReference('user_1'))
            ->setTempIntMin("17.2")
            ->setTempIntMinDate($dateTime)
            ->setTempIntMax("22.5")
            ->setTempIntMaxDate($dateTime)
            ->setTempExtMin("7.8")
            ->setTempExtMinDate($dateTime)
            ->setTempExtMax("10.7")
            ->setTempExtMaxDate($dateTime)
            ->setTempBasMin("14.0")
            ->setTempBasMinDate($dateTime)
            ->setTempBasMax("15.5")
            ->setTempBasMaxDate($dateTime);
        $manager->persist($meteoMemory);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembersFixtures::class];
    }
}
