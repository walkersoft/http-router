<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router\Interfaces;


interface RouterInterface
{
    /**
     * Matches a target to a `RouteInterface` instance and returns it.
     *
     * Receives a target as a string that will be used to compare to the
     * patterns of the stored `RouteInterface` objects in search of a match.  If a
     * match is found the `RouteInterface` instance that it matches is returned.
     *
     * If a target was unable to be matched to any pattern this method MUST
     * either generate a `RouteInterface` instance or throw an exception.
     *
     * @param string $target A target to match patterns against.
     * @return \Fusion\Router\Interfaces\RouteInterface
     * @throws \InvalidArgumentException If `$target` is not valid.
     * @throws \RuntimeException If a route pattern could not be matched.
     */
    public function match($target);

    /**
     * Adds a `RouteInterface` instance to the `RouterInterface` instance.
     *
     * Accepts a `RouteInterface` instance and stores it in the `RouterInterface`
     * instance for later matching and retrieval.
     *
     * This method MUST throw an exception if the `RouteInterface` instance could
     * not be stored.
     *
     * @param \Fusion\Router\Interfaces\RouteInterface $route A `RouteInterface`
     *     instance to store.
     * @throws \RuntimeException If there is an error storing the `RouteInterface`
     *     instance.
     */
    public function addRoute(RouteInterface $route);

    /**
     * Gets a `RouteInterface` instance by ID in the `RouteStoreInterface` instance.
     *
     * @param int $id The instance ID number.
     * @return \Fusion\Router\Interfaces\RouteInterface
     */
    public function getRoute($id);

    /**
     * Gets all stored `RouteInterface` instances as an array.
     *
     * @return array
     */
    public function getRoutes();
}