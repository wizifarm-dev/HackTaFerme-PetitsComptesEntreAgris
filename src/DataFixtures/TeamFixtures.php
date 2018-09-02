<?php

namespace App\DataFixtures;

use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Les semi-croustillants');
        $team->addUser($this->getReference('user-1'));
        $team->addUser($this->getReference('user-2'));
        $manager->persist($team);

        $this->addReference('team-semi-croustillants', $team);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
