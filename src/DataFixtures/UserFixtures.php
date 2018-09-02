<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('hacktaferme@wizi.farm');
        $user->setFirstName('Jean-Baptiste');
        $user->setLastName('Vervy');
        $user->setPlainPassword('changeme');
        $user->setEnabled(true);
        $user->setCustomTitle('Le roi du cageot');

        $this->addReference('user-1', $user);

        $this->userManager->updateUser($user);

        $user = new User();
        $user->setEmail('hacktaferme-jm@wizi.farm');
        $user->setFirstName('Jean-Marc');
        $user->setLastName('Ter');
        $user->setPlainPassword('changeme');
        $user->setEnabled(true);
        $user->setCustomTitle('L\'ami des bêtes');

        $this->addReference('user-2', $user);

        $this->userManager->updateUser($user);
    }
}
