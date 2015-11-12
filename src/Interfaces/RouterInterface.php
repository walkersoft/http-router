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
     * Matches a target to a RouteInterface instance and returns it.
     *
     * Receives a 'target' as a string that will be used to compare to the
     * pattern of the stored RouteInterface objects in search of a match.  If a
     * match is found the RouteInterface instance that it matches is returned.
     *
     * This method expects that $target is well formed according to the URI path
     * details in RFC 3986 section 3.3.
     *
     * Calling libraries SHOULD be responsible for any manipulating of a target
     * before calling this method. e.g. Decoding characters or normalization.
     *
     * If a target was unable to be matched to any pattern this method MUST
     * either generate a `RouteInterface` instance or throw an exception.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @param string $target A target to match patterns against.
     * @param string $method The incoming request method.
     * @return \Fusion\Router\Interfaces\RouteInterface
     * @throws \InvalidArgumentException If $target is not valid.
     * @throws \RuntimeException If a Route pattern could not be matched.
     */
    public function match($target, $method);

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