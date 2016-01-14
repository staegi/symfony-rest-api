<?php

namespace AppBundle\DataFixtures\ORM;

use OAuth2\OAuth2;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserData
 *
 * @package AppBundle\DataFixtures
 */
class ClientData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return $this
     */
    public function load(ObjectManager $manager)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $frontend = $clientManager->createClient();
        $frontend->setRedirectUris(array($this->container->getParameter('frontend_uri')));
        $frontend->setAllowedGrantTypes(array(OAuth2::GRANT_TYPE_USER_CREDENTIALS));
        $clientManager->updateClient($frontend);

        $this->addReference('frontend-client', $frontend);

        return $this;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}