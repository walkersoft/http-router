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
use Fusion\Router\Interfaces\RoutePatternParserInterface;

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
     * @param RouteStoreInterface $routes A collection where routes are stored.
     * @param RoutePatternParserInterface $parser A parsing implementation that
     *     will map parameters and translate rules.
     */
    public function __construct(RouteStoreInterface $routes, RoutePatternParserInterface $parser)
    {
        $this->routes = $routes;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
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
                    $match->setParameters($this->mapSegments($target));
                    break;
                }
            }
        }

        if (!$match instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf("Unable to match target '%s' to any patterns.", $target)
            );
        }

        return $match;
    }

    /**
     * {@inheritdoc}
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes->add($route);
        return $this->getRoute($this->routes->lastId());
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute($id)
    {
        return $this->routes->find($id);
    }

    /**
     * {@inheritdoc}
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