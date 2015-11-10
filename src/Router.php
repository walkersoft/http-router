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
     * Pattern parser.
     *
     * @var \Fusion\Router\RoutePatternParser
     */
    private $parser;

    /**
     * Constructor.
     *
     * Expects an implementation of a TraversableCollection to store the
     * RoutesInterface instances.  If one is not provided a default one is
     * created instead.
     *
     * @param \Fusion\Router\Interfaces\RouteStoreInterface $routes
     *     A collection where routes are stored.
     * @param RoutePatternParser $parser
     *     A parsing implementation that will map parameters and translate rules.
     */
    public function __construct(RouteStoreInterface $routes, RoutePatternParser $parser)
    {
        $this->routes = $routes;
        $this->parser = $parser;
    }

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
     * Calling libraries SHOULD be responsible for any manipulating of $target
     * before calling this method. e.g. Decoding characters or normalization.
     *
     * If a target was unable to be matched to any pattern this method MUST
     * either generate a RouteInterface instance or throw an exception.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @param string $target A target to match patterns against.
     * @param string $method The incoming request method.
     * @return \Fusion\Router\Interfaces\RouteInterface
     * @throws \InvalidArgumentException If $target is not valid.
     * @throws \RuntimeException If a Route pattern could not be matched.
     */
    public function match($target, $method = 'GET')
    {
        $match = null;

        foreach ($this->routes as $route)
        {
            if (in_array(strtoupper($method), $route->getMethods()))
            {
                $pattern = $this->parser->parsePattern($route->getPattern());

                if (preg_match("#^{$pattern}$#i", $target) === 1)
                {
                    $match = $route;
                    $match->setPattern($pattern);
                    $match->setParameters($this->mapSegments($target));
                    break;
                }
            }
        }

        if (!$match instanceof RouteInterface)
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

        foreach ($this->routes as $key => $route)
        {
            $routes[$key] = $route;
        }

        return $routes;
    }

    /**
     * Separates route target into segments and maps any named parameters.
     *
     * @param string $target
     * @return array
     */
    private function mapSegments($target)
    {
        $target = ltrim($target, '/');
        $rebuilt = [];

        if (!empty($target))
        {
            $segments = explode('/', $target);
            $map = $this->parser->getParameterMap();

            for ($i = 0; $i < count($segments); ++$i)
            {
                if (array_key_exists($i, $map))
                {
                    $rebuilt[$map[$i]] = $segments[$i];
                }
                else
                {
                    $rebuilt[$i] = $segments[$i];
                }
            }
        }

        return $rebuilt;
    }
}