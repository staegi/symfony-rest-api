<?php
/**
 * Created by PhpStorm.
 * User: staegi
 * Date: 03.01.16
 * Time: 04:49
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use JMS\Serializer\Serializer;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class UserTest
 *
 * @package Tests\AppBundle\Entity
 */
class UserTest extends WebTestCase
{
    /**
     * Tests serialization of User
     */
    public function testSerialization()
    {
        $timeZone = new \DateTimeZone('Europe/Berlin');

        $user = new User();
        $user
            ->setEmail('admin@cocoaco.de')
            ->setEmailCanonical('admin@cocoaco.de')
            ->setUsername('admin')
            ->setUsernameCanonical('admin')
            ->setPassword('$2y$13$4mvcmdehjp2coco8sgkw8egeNvWHXplyy7ou0W0nJb0j8O33KciCy')
            ->setEnabled(true)
            ->addRole('ROLE_SUPER_ADMIN')
            ->setLastLogin(new \DateTime('2016-01-04 12:13:14', $timeZone));

        $property = new \ReflectionProperty(get_class($user), 'id');
        $property->setAccessible(true);
        $property->setValue($user, 1);

        $client = static::createClient();
        /** @var Serializer $serializer */
        $serializer = $client->getContainer()->get('serializer');
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/User.200.json',
            $serializer->serialize($user, 'json')
        );
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/User.200.xml',
            $serializer->serialize($user, 'xml')
        );
    }
}
