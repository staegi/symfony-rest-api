<?php

namespace Tests\AppBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

/**
 * Class UserData
 *
 * @package Tests\AppBundle\DataFixtures
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
        $timeZone = new \DateTimeZone('Europe/Berlin');

        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@cocoaco.de')
            ->setSuperAdmin(true)
            ->setEnabled(true)
            ->setPassword('$2y$13$4mvcmdehjp2coco8sgkw8egeNvWHXplyy7ou0W0nJb0j8O33KciCy')
            ->setLastLogin(new \DateTime('2016-01-04 12:13:14', $timeZone));

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