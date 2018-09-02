<?php

namespace App\DataFixtures;

use App\Entity\Operation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class OperationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $team = $this->getReference('team-semi-croustillants');

        $user1 = $this->getReference('user-1');
        $user2 = $this->getReference('user-2');

        $resources = ['resource-1', 'resource-2', 'resource-3'];
        $names = [
            'Transport',
            'Réparation matériel',
            'Récolte',
            'Semis',
        ];

        $faker = \Faker\Factory::create();

        $operation = new Operation();
        $operation->setDate($faker->dateTimeBetween('-2 months', 'today'));
        $operation->setName($faker->randomElement($names));
        $operation->setCreatedBy($user1);
        $operation->setUser($user2);
        $operation->setTeam($team);
        $operation->setResource($this->getReference($faker->randomElement($resources)));
        $operation->setQuantity($faker->numberBetween(1,50));
        $manager->persist($operation);

        $operation = new Operation();
        $operation->setDate($faker->dateTimeBetween('-2 months', 'today'));
        $operation->setName($faker->randomElement($names));
        $operation->setCreatedBy($user2);
        $operation->setUser($user1);
        $operation->setTeam($team);
        $operation->setResource($this->getReference($faker->randomElement($resources)));
        $operation->setQuantity($faker->numberBetween(1,50));
        $manager->persist($operation);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            TeamFixtures::class,
            ResourceFixtures::class,
        );
    }
}
