<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Router\Interfaces\RouteFactoryInterface;
use Fusion\Router\Interfaces\RouteGroupInterface;
use Fusion\Router\Interfaces\RouteInterface;
use Fusion\Router\Interfaces\RouterInterface;

class RouteGroup implements RouteGroupInterface
{

    /**
     * RouterInterface instance.
     *
     * @var \Fusion\Router\Interfaces\RouterInterface
     */
    private $router;

    /**
     * RouteFactoryInterface instance.
     *
     * @var \Fusion\Router\Interfaces\RouteFactoryInterface
     */
    private $routeFactory;

    /**
     * Currently manipulated RouteInterface instance in the collection.
     *
     * @var \Fusion\Router\Interfaces\RouteInterface
     */
    private $currentRoute;

    /**
     * Default action assignment
     *
     * @var mixed
     */
    private $defaultAction;

    /**
     * Default method(s) assignment
     *
     * @var array
     */
    private $defaultMethods;

    /**
     * Pattern prefix.
     *
     * @var string
     */
    private $prefix;


    /**
     * Constructor.
     *
     * Accepts a RouterInterface instance as a dependency to store routes added
     * by the group.
     *
     * @param \Fusion\Router\Interfaces\RouterInterface $router A RouteInterface
     *     implementation.
     * @param \Fusion\Router\Interfaces\RouteFactoryInterface A RouteFactoryInterface
     *     implementation.
     *
     */
    public function __construct(RouterInterface $router, RouteFactoryInterface $factory)
    {
        $this->router = $router;
        $this->routeFactory = $factory;
        $this->defaultMethods = [];
        $this->defaultAction = '';
        $this->prefix = '';
    }

    /**
     * {@inheritdoc}
     */
    public function route($pattern, $action = null, array $methods = [])
    {
        if (!is_string($pattern))
        {
            throw new \InvalidArgumentException(
                sprintf('Route pattern must be a string. %s given.', gettype($pattern))
            );
        }

        if (is_string($this->prefix) && !empty($this->prefix))
        {
            $pattern = $this->prefix . $pattern;
        }

        $route = $this->routeFactory->make($pattern);
        $this->currentRoute = $this->router->addRoute($route);

        //Assign initial values.
        $this->currentRoute->setAction($this->defaultAction);
        $this->currentRoute->setMethods($this->defaultMethods);

        if ($action !== null)
        {
            $this->currentRoute->setAction($action);
        }

        if (!empty($methods))
        {
            $this->currentRoute->setMethods($methods);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toAction($action)
    {
        if (!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update the action because no route has been created yet.')
            );
        }

        $this->currentRoute->setAction($action);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fromMethods(array $methods)
    {
        if (!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update method(s) because no route has been created yet.')
            );
        }

        $this->currentRoute->setMethods($methods);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function fromMethod($method)
    {
        if (!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update method(s) because no route has been created yet.')
            );
        }

        if (!is_string($method))
        {
            throw new \InvalidArgumentException(
                sprintf('Method must be a string. %s given.', gettype($method))
            );
        }

        $this->currentRoute->setMethods([$method]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultMethods(array $methods)
    {
        $this->defaultMethods = $methods;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultAction($action)
    {
        $this->defaultAction = $action;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrefix($prefix)
    {
        if (!is_string($prefix))
        {
            throw new \InvalidArgumentException(
                sprintf('Method must be a string. %s given.', gettype($prefix))
            );
        }

        $this->prefix = $prefix;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createGroup()
    {
        return new self($this->router, $this->routeFactory);
    }
}