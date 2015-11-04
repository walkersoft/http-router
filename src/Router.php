<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Router\Interfaces\RouteInterface;
use Fusion\Router\Interfaces\RouterInterface;
use Fusion\Router\Interfaces\RouteStoreInterface;

class Router implements RouterInterface
{
    /**
     * Collection of RouteInterface instances.
     *
     * @var \Fusion\Router\Interfaces\RouteStoreInterface
     */
    private $routes;

    /**
     * Constructor.
     *
     * Expects an implementation of a TraversableCollection to store the
     * RoutesInterface instances.  If one is not provided a default one is
     * created instead.
     *
     * @param \Fusion\Router\Interfaces\RouteStoreInterface $routes
     *     A collection where routes are stored.
     */
    public function __construct(RouteStoreInterface $routes)
    {
        $this->routes = $routes;
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
            if(preg_match("#^{$route->getPattern()}$#i", $target) === 1)
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
     * @returns \Fusion\Router\Interfaces\RouteInterface
     * @throws \RuntimeException If there is an error storing the RouteInterface
     *     instance.
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes->add($route);
        return $this->getRoute($this->routes->lastId());
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

    /**
     * Checks if a RouteInterface instance exists at offset.
     *
     * The return value will be casted to boolean if non-boolean was returned.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return bool
     *
    public function offsetExists($offset)
    {
        return $this->routes->has($offset);
    }

    /**
     * Returns the route at the given offset or null if it doesn't exist.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return \Fusion\Router\Interfaces\RouteInterface|null
     *
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->routes[$offset] : null;
    }

    /**
     * Route to set at given offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     *
    public function offsetSet($offset, $value)
    {
        if($this->offsetExists($offset))
        {
            $this->routes[$offset] = $value;
        }
        else
        {
            $this->routes[] = $value;
        }
    }

    /**
     * The route to unset at given offset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     *
    public function offsetUnset($offset)
    {
        if($this->offsetExists($offset))
        {
            unset($this->routes[$offset]);
        }
    }*/
}