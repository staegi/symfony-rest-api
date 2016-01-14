<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractController
 *
 * @package AppBundle\Controller
 */
abstract class AbstractController extends FOSRestController
{
    /**
     * Returns the class name of the repository
     *
     * @return string
     */
    abstract protected function getEntityClass();

    /**
     * Creates a new entity
     *
     * @param Request $request
     *
     * @return object
     * @throws BadRequestHttpException
     */
    protected function createEntity(Request $request)
    {
        $entity = $this->deserializePayload($request);
        if (!is_object($entity)) {
            throw $this->createBadRequestException("No valid payload found");
        }

        try {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($entity);
            $manager->flush($entity);
            $manager->refresh($entity);

            return $entity;
        } catch (ConstraintViolationException $exception) {
            throw $this->createBadRequestException('Constraint violation occured', $exception);
        }

        throw $this->createBadRequestException('An unknown error occured while persisting this entity', $exception);
    }

    /**
     * Updates an existing entity
     *
     * @param Request $request
     * @param int     $identifier Unique identifier of an entity
     *
     * @return object
     * @throws BadRequestHttpException
     */
    protected function updateEntity(Request $request, $identifier)
    {
        // Checks existence before
        $this->findEntityByIdentifier($identifier);

        $entity = $this->deserializePayload($request);
        if ($identifier != $entity->getId()) {
            throw $this->createBadRequestException("Identifier in JSON and URI aren't equal");
        }

        try {
            $manager = $this->getDoctrine()->getManager();
            $entity = $manager->merge($entity);
            $manager->flush($entity);
            $manager->refresh($entity);

            return $entity;
        } catch (ConstraintViolationException $exception) {
            throw $this->createBadRequestException('Constraint violation occured', $exception);
        }

        throw $this->createBadRequestException('An unknown error occured while persisting this entity', $exception);
    }

    /**
     * Deletes an existing entity
     *
     * @param int $identifier Unique identifier of an entity
     *
     * @return object
     */
    protected function deleteEntity($identifier)
    {
        $entity = $this->findEntityByIdentifier($identifier);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entity);
        $manager->flush($entity);

        return $entity;
    }

    /**
     * Helper to find an entity.
     *
     * @param int $identifier Unique identifier of an entity
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    protected function findEntityByIdentifier($identifier)
    {
        $entity = $this->getRepository()->find($identifier);

        if (is_object($entity)) {
            return $entity;
        }

        throw $this->createNotFoundException(sprintf("No entity found with identifier %s", $identifier));
    }

    /**
     * Returns a collection of entities
     *
     * @param array    $criteria
     * @param array    $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    protected function findEntities(array $criteria, array $orderBy = array(), $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param Request $request Request object
     *
     * @return object|array
     */
    protected function deserializePayload(Request $request)
    {
        $payload = $request->getContent();
        if (!$payload) {
            throw $this->createBadRequestException('No payload found');
        }

        try {
            $format = $request->getRequestFormat('json');
            $class = $this->getEntityClass();

            return $this->get('serializer')->deserialize($payload, $class, $format);
        } catch (\Exception $exception) {
            throw $this->createBadRequestException($exception->getMessage(), $exception);
        }
    }

    /**
     * @param string     $message
     * @param \Exception $previous
     * @param int        $code
     *
     * @return BadRequestHttpException
     */
    protected function createBadRequestException($message = 'Bad Request', \Exception $previous = null, $code = 0)
    {
        return new BadRequestHttpException($message, $previous, $code);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository($this->getEntityClass());
    }
}
