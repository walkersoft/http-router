<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Collection\TraversableCollection;
use Fusion\Router\Interfaces\RouteInterface;
use Fusion\Router\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    /**
     * Collection of RouteInterface instances.
     *
     * @var \Fusion\Collection\TraversableCollection
     */
    private $routes;

    /**
     * Constructor.
     *
     * Expects an implementation of a TraversableCollection to store the
     * RoutesInterface instances.  If one is not provided a default one is
     * created instead.
     *
     * @param \Fusion\Collection\TraversableCollection Storage collection.
     */
    public function __construct(TraversableCollection $routes = null)
    {
        $this->routes = $routes;
        if(!$this->routes instanceof TraversableCollection)
        {
            $this->routes = new TraversableCollection();
        }

        $this->routes->addRestriction('\Fusion\Router\Interfaces\RouteInterface');
    }

    /**
     * Matches a target to a RouteInterface instance and returns it.
     *
     * Receives a 'target' as a string that will be used to compare to the
     * pattern of the stored RouteInterface objects in search of a match.  If a
     * match is found the RouteInterface instance that it matches is returned.
     *
     * If a target was unable to be matched to any pattern this method MUST
     * either generate a RouteInterface instance or throw an exception.
     *
     * @param string $target A target to match patterns against.
     * @return \Fusion\Router\Interfaces\RouteInterface
     * @throws \InvalidArgumentException If $target is not valid.
     * @throws \RuntimeException If a Route pattern could not be matched.
     */
    public function match($target)
    {
        $match = null;

        foreach($this->routes as $route)
        {
            if(preg_match("#{$route->getPattern()}#i", $target) === 1)
            {
                $match = $route;
                break;
            }
        }

        if(!$match instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf("Unable to match target '%s' to any patterns", $target)
            );
        }

        return $match;
    }

    /**
     * Adds a RouteInterface instance to the RouterInterface instance.
     *
     * Accepts a Route object in stores it in the Router for later matching and
     * retrieval.
     *
     * This method MUST throw an exception if the RouteInterface instance could
     * not be stored.
     *
     * @param \Fusion\Router\Interfaces\RouteInterface $route A RouteInterface
     *     instance to store.
     * @returns int
     * @throws \RuntimeException If there is an error storing the RouteInterface
     *     instance.
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes->add($route);
        return $this->routes->lastId();
    }

    /**
     * Gets a RouteInterface instance by ID number in the route collection.
     *
     * @param int $id The instance ID number.
     * @return \Fusion\Router\Interfaces\RouteInterface
     */
    public function getRoute($id)
    {
        return $this->routes->find($id);
    }

    /**
     * Gets all RouteInterface instances stored as an array.
     *
     * @returns array
     */
    public function getRoutes()
    {
        $routes = [];

        foreach($this->routes as $key => $route)
        {
            $routes[$key] = $route;
        }

        return $routes;
    }
}