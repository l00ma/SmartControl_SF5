<?php

namespace App\DataFixtures;

use App\Entity\Meteo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MeteoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $meteo = new Meteo();
        $meteo
            ->setOwner($this->getReference('user_1'))
            ->setPression('1015')
            ->setVitesseVent('6')
            ->setDirectionVent('N')
            ->setLocation('Pierre-Bénite')
            ->setHumidite('44')
            ->setWeather('couvert')
            ->setIconId('wi-owm-day-804')
            ->setLeveSoleil('07:00')
            ->setCoucheSoleil('20:22')
            ->setTempF1('27')
            ->setTempF2('24')
            ->setTempF3('20')
            ->setTimeF1('20:00')
            ->setTimeF2('23:00')
            ->setTimeF3('02:00')
            ->setWeatherF1('couvert')
            ->setWeatherF2('couvert')
            ->setWeatherF3('couvert')
            ->setIconF1('wi-owm-day-804')
            ->setIconF2('wi-owm-day-804')
            ->setIconF3('wi-owm-day-804')
            ->setTempInt('21.0')
            ->setTempBas('19.2')
            ->setTempExt('14.8');
        $manager->persist($meteo);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembersFixtures::class];
    }
}
