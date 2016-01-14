<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

/**
 * Class UserData
 *
 * @package AppBundle\DataFixtures
 */
class UserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return $this
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@cocoaco.de');
        $admin->setSuperAdmin(true);
        $admin->setEnabled(true);
        $admin->setPlainPassword('admin');

        $manager->persist($admin);
        $manager->flush();

        $this->addReference('admin-user', $admin);

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}