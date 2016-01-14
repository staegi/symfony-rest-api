<?php

namespace Tests\AppBundle\Test;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
/**
 * Class WebTestCase
 *
 * @package Tests\AppBundle\Test
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        self::runCommand('doctrine:schema:create');
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        self::runCommand('doctrine:schema:drop', ['--force' => true]);

        parent::tearDown();
    }
}