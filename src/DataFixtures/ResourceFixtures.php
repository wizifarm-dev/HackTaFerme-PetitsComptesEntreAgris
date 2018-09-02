<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $resource = new Resource();
        $resource->setName('John Deere 6145 R');
        $resource->setHourlyCost(50);
        $resource->setOwner($this->getReference('user-1'));
        $manager->persist($resource);

        $this->addReference('resource-1', $resource);

        $resource = new Resource();
        $resource->setName('Claas Arion 640 Cis');
        $resource->setHourlyCost(40);
        $resource->setOwner($this->getReference('user-1'));
        $manager->persist($resource);

        $this->addReference('resource-2', $resource);

        $resource = new Resource();
        $resource->setName('Case IH PUMA 185');
        $resource->setHourlyCost(35);
        $resource->setOwner($this->getReference('user-2'));
        $manager->persist($resource);

        $this->addReference('resource-3', $resource);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
