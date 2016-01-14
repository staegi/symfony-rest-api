<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserController
 *
 * @RouteResource("User")
 * @package AppBundle\Controller
 */
class UserController extends AbstractController
{
    /**
     * Returns a single user
     *
     * @View
     * @ApiDoc(
     *     output="AppBundle\Entity\User",
     *     statusCodes={
     *         200="Returned when the user is existing",
     *         403="Returned when the user is not authorized",
     *         404="Returned when the user has not been found"
     *     }
     * )
     *
     * @param int $identifier Unique identifier of an user
     *
     * @return User
     * @throws NotFoundHttpException
     */
    public function getAction($identifier)
    {
        return $this->findEntityByIdentifier($identifier);
    }

    /**
     * Creates a new user
     *
     * @View
     * @ApiDoc(
     *     input="AppBundle\Entity\User",
     *     output="AppBundle\Entity\User",
     *     statusCodes={
     *         200="Returned when the user has been created",
     *         400="Returned when the user has a violation",
     *         403="Returned when the user is not authorized"
     *     }
     * )
     *
     * @param Request $request
     *
     * @return User
     * @throws BadRequestHttpException
     */
    public function postAction(Request $request)
    {
        return $this->createEntity($request);
    }

    /**
     * Updates an existing user
     *
     * @View
     * @ApiDoc(
     *     input="AppBundle\Entity\User",
     *     output="AppBundle\Entity\User",
     *     statusCodes={
     *         200="Returned when the user has been updated",
     *         400="Returned when the user has a violation",
     *         403="Returned when the user is not authorized",
     *         404="Returned when the user has not been found"
     *     }
     * )
     *
     * @param Request $request
     * @param int     $identifier Unique identifier of an user
     *
     * @return object
     */
    public function putAction(Request $request, $identifier)
    {
        return $this->updateEntity($request, $identifier);
    }

    /**
     * Deletes an existing user
     *
     * @View
     * @ApiDoc(
     *     output="AppBundle\Entity\User",
     *     statusCodes={
     *         200="Returned when the user has been deleted",
     *         403="Returned when the user is not authorized",
     *         404="Returned when the user has not been found"
     *     }
     * )
     *
     * @param int $identifier Unique identifier of an user
     *
     * @return object
     */
    public function deleteAction($identifier)
    {
        return $this->deleteEntity($identifier);
    }

    /**
     * Returns a collection
     *
     * @View
     * @ApiDoc(
     *     output="array<AppBundle\Entity\User>",
     *     statusCodes={
     *         200="Returned when no usage mistake occurs",
     *         403="Returned when the user is not authorized"
     *     }
     * )
     *
     * @QueryParam(name="filter", array=true, nullable=true, description="Filter for user listing.")
     * @QueryParam(name="offset", requirements="\d+", default="0", description="Offset from which to start listing entities.")
     * @QueryParam(name="limit", requirements="\d+", default="10", description="Maximum number of entities in the result set.")
     * @QueryParam(name="sort", array=true, nullable=true, description="Attribute to sort with")
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function cgetAction(ParamFetcher $paramFetcher)
    {
        $filter = $paramFetcher->get('filter');
        $sort = $paramFetcher->get('sort');
        $limit = intval($paramFetcher->get('limit'));
        $offset = intval($paramFetcher->get('offset'));

        return $this->findEntities($filter, $sort, $limit, $offset);
    }

    /**
     * @return string
     */
    protected function getEntityClass()
    {
        return User::class;
    }
}
