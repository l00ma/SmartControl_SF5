<?php

namespace App\DataFixtures;

use App\Entity\Security;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SecurityFixtures extends Fixture
{
    public const ALERT = [
        '/var/www/smartcontrol/data/images/15-08-2022_10h08m57s_event06.mp4',
        '/var/www/smartcontrol/data/images/15-08-2022_12h03m54s_event08.mp4',
        '/var/www/smartcontrol/data/images/15-08-2022_17h18m32s_event09.mp4',
        '/var/www/smartcontrol/data/images/snap/15-08-2022_17h18m33s_Photo.jpg',
        '/var/www/smartcontrol/data/images/snap/16-08-2022_11h53m04s_Photo.jpg',
        '/var/www/smartcontrol/data/images/snap/16-08-2022_18h49m08s_Photo.jpg',
        '/var/www/smartcontrol/data/images/snap/15-09-2022_16h49m12s_Photo.jpg',
        '/var/www/smartcontrol/data/images/snap/16-09-2022_18h17m34s_Photo.jpg'
        ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (self::ALERT as $uniqAlert) {

            $date = $faker->dateTimeThisMonth();
            $dateTime = \DateTimeImmutable::createFromMutable($date);

            $security = new Security();
            $security
                ->setCamera(0)
                ->setFilename($uniqAlert)
                ->setTimeStamp($dateTime);

            str_ends_with($uniqAlert, 'mp4') ? $security->setFileType(8) : $security->setFileType(2);
            $manager->persist($security);
        }
        $manager->flush();
    }
}
