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
     * Currently manipulated RouteInterface position in the collection.
     *
     * @var int
     */
    private $currentIndex;

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
     * Creates and stores a route.
     *
     * Handles the instantiation of a Route object and initializes it with the
     * values provided or default values if necessary.
     *
     * All Route objects MUST store at a minimum the matching pattern, an action,
     * and an HTTP method that acts as an additional.  Only the pattern must be
     * supplied by the user.  A Route is capable of having no action assigned and
     * can respond to any HTTP method.
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @see \Fusion\Router\Router::createRoute()
     * @param string $pattern The route pattern to be used for matching.
     * @param mixed $action Associated action to be taken.
     * @param array $methods An array with all responding methods.
     * @returns self
     * @throws \InvalidArgumentException When any of the parameters are not valid.
     */
    public function route($pattern, $action = null, array $methods = [])
    {
        if(!is_string($pattern))
        {
            throw new \InvalidArgumentException(
                sprintf('Route pattern must be a string. %s given.', gettype($pattern))
            );
        }

        $route = $this->routeFactory->make($pattern, $action, $methods);
        $id = $this->router->addRoute($route);

        //Assign initial values.
        $this->currentRoute->setAction($this->defaultAction);
        $this->currentRoute->setMethods($this->defaultMethods);

        if($action !== null)
        {
            $this->currentRoute->setAction($action);
        }

        if(!empty($methods))
        {
            $this->currentRoute->setMethods($methods);
        }

        return $this;
    }

    /**
     * Assigns an action the last created Route.
     *
     * Actions are implementation-specific.  Actions may be strings that map to
     * a class name, a closure, or even an object that may be invoked directly.
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @see \Fusion\Router\Intefaces\RouteInterface::setAction()
     * @param mixed $action The action to assign.
     * @returns self
     * @throws \InvalidArgumentException If $action is not a valid action for
     *     a given domain context.
     * @throws \RuntimeException When there is no route to update.
     */
    public function toAction($action)
    {
        if(!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update the action because no route has been created yet.')
            );
        }

        $this->currentRoute->setAction($action);
        return $this;
    }

    /**
     * Assigns multiple HTTP methods that the Route will match.
     *
     * A Route can be restricted or permissive when matching based on one or more
     * HTTP methods.  e.g.: A single endpoint may be defined twice, under two
     * different methods (GET and POST) and also two different actions (ListAction
     * and UpdateAction).
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @param array $methods Array of strings representing methods that the Route
     *     will match up against.
     * @returns self
     * @throws \InvalidArgumentException When any of $method are not valid
     *     HTTP methods.
     * @throws \RuntimeException When there is no route to update.
     */
    public function fromMethods(array $methods)
    {
        if(!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update method(s) because no route has been created yet.')
            );
        }

        $this->currentRoute->setMethods($methods);
        return $this;
    }

    /**
     * Assigns a single HTTP method that the route will match.
     *
     * A Route can be restricted or permissive when matching based on one or more
     * HTTP methods.  e.g.: A single endpoint may be defined twice, under two
     * different methods (GET and POST) and also two different actions (ListAction
     * and UpdateAction).
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @param string $method Array of strings representing methods that the Route
     *     will match up against.
     * @returns self
     * @throws \InvalidArgumentException When $method is not valid.
     * @throws \RuntimeException When there is no route to update.
     */
    public function fromMethod($method)
    {
        if(!$this->currentRoute instanceof RouteInterface)
        {
            throw new \RuntimeException(
                sprintf('Unable to update method(s) because no route has been created yet.')
            );
        }

        if(!is_string($method))
        {
            throw new \InvalidArgumentException(
                sprintf('Method must be a string. %s given.', gettype($method))
            );
        }

        $this->currentRoute->setMethods([$method]);
        return $this;
    }

    /**
     * Specifies default HTTP methods that will apply to any newly created Route.
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @param array $methods An array of default HTTP methods to assign to a route
     *     when no other methods are specified.
     * @returns self
     */
    public function setDefaultMethods(array $methods)
    {
        $this->defaultMethods = $methods;
        return $this;
    }

    /**
     * Sets a default action for a Route.
     *
     * Useful in situations where a fallback action should be taken when a
     * specific action is not assigned with the route() or toAction() methods.
     *
     * This method will return itself to facilitate chaining using a fluent interface.
     *
     * @param mixed $action A default action to assign to all created routes.
     * @returns self
     * @throws \InvalidArgumentException If $action is not a valid action for
     *     a given domain context.
     */
    public function setDefaultAction($action)
    {
        $this->defaultAction = $action;
        return $this;
    }

    /**
     * Specifies a prefix to assign to patterns.
     *
     * Although patterns may be structured per the requirements of the specific
     * application it may become tedious to re-type route patterns that begin
     * with the same text.
     *
     * For example, an application that is managing books might have the following
     * endpoints:
     *
     *   /books/list      (lists all books)
     *   /books/show/:id  (shows details for book with the specified ID number)
     *   /books/edit/:id  (edit details for book with the specified ID number)
     *
     * In this case the `/books` portion of the endpoints is redundant. By
     * declaring a prefix the endpoint patterns can focus on their specific use
     * by declaring only the differing portions.
     *
     * Example:
     *
     *   $group->setPrefix('/books');
     *   $group->route('/list')        //pattern: /books/list
     *         ->route('/show/:id')    //pattern: /books/show/:id
     *         ->route('/edit/:id');   //pattern: /books/edit/:id
     *
     * @param string $prefix Sets the prefix to assign to all route patterns.
     * @returns self
     * @throws \InvalidArgumentException If $prefix is not valid.
     */
    public function setPrefix($prefix)
    {
        if(!is_string($prefix))
        {
            throw new \InvalidArgumentException(
                sprintf('Method must be a string. %s given.', gettype($prefix))
            );
        }

        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Creates and returns a new RouteGroup.
     *
     * @returns \Fusion\Router\RouteGroup
     */
    public function createGroup()
    {
        return new self($this->router, $this->routeFactory);
    }
}