<?php
namespace AppBundle\Serializer;

use Doctrine\Common\Persistence\ManagerRegistry;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\DeserializationContext;

/**
 * Class DoctrineObjectConstructor
 *
 * Doctrine object constructor for new (or existing) objects during deserialization.
 *
 * @package AppBundle\Serializer
 */
class DoctrineObjectConstructor implements ObjectConstructorInterface
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var ObjectConstructorInterface
     */
    private $fallbackConstructor;

    /**
     * Constructor.
     *
     * @param ManagerRegistry            $managerRegistry     Manager registry
     * @param ObjectConstructorInterface $fallbackConstructor Fallback object constructor
     */
    public function __construct(ManagerRegistry $managerRegistry, ObjectConstructorInterface $fallbackConstructor)
    {
        $this->managerRegistry = $managerRegistry;
        $this->fallbackConstructor = $fallbackConstructor;
    }

    /**
     * @param VisitorInterface       $visitor
     * @param ClassMetadata          $metadata
     * @param mixed                  $data
     * @param array                  $type
     * @param DeserializationContext $context
     *
     * @return null|object
     */
    public function construct(VisitorInterface $visitor, ClassMetadata $metadata, $data, array $type, DeserializationContext $context)
    {
        // Locate possible ObjectManager
        $objectManager = $this->managerRegistry->getManagerForClass($metadata->name);
        if (!$objectManager) {
            // No ObjectManager found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Locate possible ClassMetadata
        $classMetadataFactory = $objectManager->getMetadataFactory();
        if ($classMetadataFactory->isTransient($metadata->name)) {
            // No ClassMetadata found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Transform SimpleXMLElement to array
        if ($data instanceof \SimpleXMLElement) {
            $data = json_decode(json_encode($data), true);
        }

        // Managed entity, check for proxy load
        if (!is_array($data)) {
            // Single identifier, load proxy
            return $objectManager->getReference($metadata->name, $data);
        }

        // Fallback to default constructor if missing identifier(s)
        $classMetadata = $objectManager->getClassMetadata($metadata->name);
        $identifierList = array();
        foreach ($classMetadata->getIdentifierFieldNames() as $name) {
            if (array_key_exists($name, $data)) {
                $identifierList[$name] = $data[$name];
            }
        }
        $object = null;

        if (count($identifierList)) {
            // Entity update, try to load it from database
            $object = $objectManager->find($metadata->name, $identifierList);
        }

        if (!is_object($object)) {
            // Entity with that identifier didn't exist, create a new Entity
            $reflection = new \ReflectionClass($metadata->name);
            $object = $reflection->newInstanceArgs($identifierList);
        }

        $objectManager->initializeObject($object);

        return $object;
    }
}