<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router\Interfaces;


interface RouteGroupInterface
{
    /**
     * Creates and stores a route.
     *
     * Handles the instantiation of a `RouteInterface` instance and initializes
     * it with the values provided or default values if necessary.
     *
     * All `RouteInterface` instances MUST store at a minimum the matching
     * pattern, an action, and an HTTP method. A pattern is all that is required
     * to be present when creating a `RouteInterface` instance.
     *
     * @see \Fusion\Router\Interfaces\RouterInterface::addRoute()
     * @param string $pattern The route pattern to be used for matching.
     * @param mixed $action Associated action to be taken.
     * @param array $methods An array with all responding methods.
     * @return self
     * @throws \InvalidArgumentException When any of the parameters are not valid.
     */
    public function route($pattern, $action = null, array $methods = []);

    /**
     * Assigns an action the last created Route.
     *
     * Actions are implementation-specific.  Actions may be strings that map to
     * a class name, a closure, or even an object that may be invoked directly.
     *
     * @see \Fusion\Router\Interfaces\RouteInterface::setAction()
     * @param mixed $action The action to assign.
     * @return self
     * @throws \InvalidArgumentException If `$action` is not a valid action for
     *     a given domain context.
     * @throws \RuntimeException When there is no route to update.
     */
    public function toAction($action);

    /**
     * Assigns multiple HTTP methods to the `RouteInterface` instance.
     *
     * @see \Fusion\Router\Interfaces\RouteInterface::setMethods()
     * @param array $methods Array of strings representing methods that the
     *     `RouteInterface` instance will match up against.
     * @return self
     * @throws \InvalidArgumentException When any of `$method` are not valid.
     * @throws \RuntimeException When there is no `RouteInterface` instance
     *     to update.
     */
    public function fromMethods(array $methods);

    /**
     * Assigns a single HTTP method to the `RouteInterface` instance.
     *
     * @see \Fusion\Router\Interfaces\RouteInterface::setMethods()
     * @param string $method String representing methods that the `RouteInterface`
     *     instance will match up against.
     * @return self
     * @throws \InvalidArgumentException When `$method` is not valid.
     * @throws \RuntimeException When there is no `RouteInterface` instance
     *     to update.
     */
    public function fromMethod($method);

    /**
     * Sets the default HTTP method(s) assigned to new `RouteInterface` instances.
     *
     * This application of any HTTP methods assigned as a result of this method
     * SHOULD only happen when a new `RouteInterface` instance is generated from
     * the `RouteGroupInterface::route()` method. In other words, the results of
     * any attempt to assign HTTP methods MUST take precedence over HTTP methods
     * assigned with this method.
     *
     * @see \Fusion\Router\Interfaces\RouteInterface::setMethods()
     * @param array $methods An array of default HTTP methods to assign to a
     *     `RouteInterface` instance when no other methods are specified.
     * @return self
     */
    public function setDefaultMethods(array $methods);

    /**
     * Sets a default action assigned to new `RouteInterface` instances.
     *
     * Useful in situations where a fallback action should be taken when a
     * specific action is not assigned to the `RouteInterface` instance(s).
     *
     * @see \Fusion\Router\Interfaces\RouteInterface::setAction()
     * @param mixed $action A default action to assign
     * @return self
     * @throws \InvalidArgumentException If `$action` is not a valid action for
     *     a given domain context.
     */
    public function setDefaultAction($action);

    /**
     * Specifies a prefix to assign to patterns.
     *
     * When creating endpoints for an application there may become a situation
     * when many endpoints will have similar text at the beginning of an endpoint
     * where a resource representation is general.
     *
     * For example, an application that is managing books might have the following
     * endpoints:
     *
     * <code>
     * /books/list       // lists all books
     * /books/show/:id   // shows details for a book with the specified ID number
     * /books/edit/:id   // edit details for a book with the specified ID number
     * </code>
     *
     * In this case the `/books` portion of the endpoints is redundant. By
     * declaring a prefix the endpoint patterns can focus on their specific use
     * by declaring only the differing portions.
     *
     * <code>
     * $group->setPrefix('/books');
     * $group->route('/list')        //pattern: /books/list
     *       ->route('/show/:id')    //pattern: /books/show/:id
     *       ->route('/edit/:id');   //pattern: /books/edit/:id
     * </code>
     *
     * This method MUST only edit newly created `RouteInterface` instances and
     * MUST NOT retroactively edit previously created `RouteInterface` instances.
     *
     * @param string $prefix Sets the prefix to assign to all newly created
     *     route patterns.
     * @return self
     * @throws \InvalidArgumentException If `$prefix` is not valid.
     */
    public function setPrefix($prefix);

    /**
     * Creates and returns a new RouteGroupInterface instance.
     *
     * @return \Fusion\Router\Interfaces\RouteGroupInterface
     */
    public function createGroup();
}