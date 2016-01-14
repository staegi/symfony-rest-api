<?php

namespace Tests\AppBundle\Controller;

use Tests\AppBundle\DataFixtures\UserData;
use Tests\AppBundle\Test\WebTestCase;

/**
 * Class UserControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Tests posting a new user with JSON
     */
    public function testJsonPutAction()
    {
        $this->loadFixtures([UserData::class]);

        $json = file_get_contents(__DIR__ . '/../Data/User.200.json');
        $client = static::makeClient(true);
        $client->request('PUT', '/users/1.json', [], [], [], $json);

        $this->assertStatusCode(200, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/User.200.json',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests posting a new user with XML
     */
    public function testXmlPutAction()
    {
        $this->loadFixtures([UserData::class]);

        $xml = file_get_contents(__DIR__ . '/../Data/User.200.xml');
        $client = static::makeClient(true);
        $client->request('PUT', '/users/1.xml', [], [], [], $xml);

        $this->assertStatusCode(200, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/User.200.xml',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests posting a new user with JSON
     */
    public function testJsonPostAction()
    {
        $this->loadFixtures([]);

        $json = file_get_contents(__DIR__ . '/../Data/User.json');
        $client = static::makeClient(true);
        $client->request('POST', '/users.json', [], [], [], $json);

        $this->assertStatusCode(200, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/User.200.json',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests posting a new user with XML
     */
    public function testXmlPostAction()
    {
        $this->loadFixtures([]);

        $xml = file_get_contents(__DIR__ . '/../Data/User.xml');
        $client = static::makeClient(true);
        $client->request('POST', '/users.xml', [], [], [], $xml);

        $this->assertStatusCode(200, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/User.200.xml',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests getting a user collection with JSON
     */
    public function testJsonCgetAction()
    {
        $this->loadFixtures([]);

        $client = static::makeClient(true);
        $client->request('GET', '/users.json');
        $this->assertStatusCode(200, $client);
        $this->assertJsonStringEqualsJsonString(
            '[]', $client->getResponse()->getContent()
        );

        $this->loadFixtures([UserData::class]);

        $client->request('GET', '/users.json');
        $this->assertStatusCode(200, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/UserCollection.json',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests getting a user collection with XML
     */
    public function testXmlCgetAction()
    {
        $this->loadFixtures([]);

        $client = static::makeClient(true);
        $client->request('GET', '/users.xml');

        $this->assertStatusCode(200, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/EmptyCollection.xml',
            $client->getResponse()->getContent()
        );

        $this->loadFixtures([UserData::class]);

        $client->request('GET', '/users.xml');
        $this->assertStatusCode(200, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/UserCollection.xml',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests getting a single user with JSON
     *
     * @param int $identifier
     * @param int $statusCode
     *
     * @dataProvider dataGetAction
     */
    public function testJsonGetAction($identifier, $statusCode)
    {
        $this->loadFixtures([UserData::class]);

        $client = static::makeClient(true);
        $client->request('GET', sprintf('/users/%d.json', $identifier));

        $this->assertStatusCode($statusCode, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . sprintf('/../Data/user.%d.json', $statusCode),
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests getting a single user with XML
     *
     * @param int $identifier
     * @param int $statusCode
     *
     * @dataProvider dataGetAction
     */
    public function testXmlGetAction($identifier, $statusCode)
    {
        $this->loadFixtures([UserData::class]);

        $client = static::makeClient(true);
        $client->request('GET', sprintf('/users/%d.xml', $identifier));

        $this->assertStatusCode($statusCode, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . sprintf('/../Data/user.%d.xml', $statusCode),
            $client->getResponse()->getContent()
        );
    }

    /**
     * Provides the data for testGetAction
     *
     * @return array
     */
    public function dataGetAction()
    {
        return [
            [1, 200],
            [2, 404],
        ];
    }

    /**
     * Tests deleting a user with JSON
     */
    public function testJsonDeleteAction()
    {
        $this->loadFixtures([UserData::class]);

        $client = static::makeClient(true);
        $client->request('DELETE', '/users/1.json');

        $this->assertStatusCode(200, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/User.json',
            $client->getResponse()->getContent()
        );

        $client->request('DELETE', '/users/2.json');

        $this->assertStatusCode(404, $client);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/../Data/User.404.json',
            $client->getResponse()->getContent()
        );
    }

    /**
     * Tests deleting a user with XML
     */
    public function testXmlDeleteAction()
    {
        $this->loadFixtures([UserData::class]);

        $client = static::makeClient(true);
        $client->request('DELETE', '/users/1.xml');

        $this->assertStatusCode(200, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/User.xml',
            $client->getResponse()->getContent()
        );

        $client->request('DELETE', '/users/2.xml');

        $this->assertStatusCode(404, $client);
        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Data/User.404.xml',
            $client->getResponse()->getContent()
        );
    }
}
