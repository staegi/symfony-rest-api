<?php
/**
 * Created by PhpStorm.
 * User: staegi
 * Date: 08.01.16
 * Time: 00:56
 */

namespace Tests\AppBundle\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use AppBundle\Serializer\DoctrineObjectConstructor;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * Class AppExtensionTest
 *
 * @package Tests\AppBundle\DependencyInjection
 */
class AppExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Test extension load
     */
    public function testLoad()
    {
        $this->load();

        $this->assertContainerBuilderHasService('jms_serializer.object_constructor', DoctrineObjectConstructor::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('jms_serializer.object_constructor', 0, 'doctrine');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('jms_serializer.object_constructor', 1, 'jms_serializer.unserialize_object_constructor');

        $this->assertContainerBuilderHasService('jms_serializer.doctrine_object_constructor', DoctrineObjectConstructor::class);
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('jms_serializer.doctrine_object_constructor', 0, 'doctrine');
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('jms_serializer.doctrine_object_constructor', 1, 'jms_serializer.unserialize_object_constructor');
    }

    /**
     * @return array
     */
    protected function getContainerExtensions()
    {
        return array(
            new AppExtension()
        );
    }
}
