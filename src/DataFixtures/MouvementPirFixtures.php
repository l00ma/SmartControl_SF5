<?php

namespace App\DataFixtures;

use App\Entity\MouvementPir;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MouvementPirFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $mouv = new MouvementPir();
        $mouv
            ->setOwner($this->getReference('user_1'))
            ->setGraphRafraich(5)
            ->setEnreg(0)
            ->setEnregDetect(0)
            ->setAlert(0)
            ->setAlertDetect(5)
            ->setEspaceTotal('7.027')
            ->setEspaceDispo('4.321')
            ->setTauxUtilisation('38.5');
        $manager->persist($mouv);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [MembersFixtures::class];
    }
}
