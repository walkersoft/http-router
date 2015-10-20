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
     * Handles the instantiation of a Route object and initializes it with the
     * values provided or default values if necessary.
     *
     * All Route objects MUST store at a minimum the matching pattern, an action,
     * and an HTTP method that acts as an additional.  Only the pattern must be
     * supplied by the user.  A Route is capable of having no action assigned and
     * can respond to any HTTP method.
     *
     * This method will return itself to facilitate chaining using expressive syntax.
     *
     * @see \Fusion\Router\Router::createRoute()
     * @param string $pattern The route pattern to be used for matching.
     * @param mixed $action Associated action to be taken.
     * @param array $methods An array with all responding methods.
     * @returns self
     */
    public function route($pattern, $action = null, array $methods = []);

    /**
     * Assigns an action the last created Route.
     *
     * Actions are implementation-specific.  Actions may be strings that map to
     * a class name, a closure, or even an object that may be invoked directly.
     *
     * This method will return itself to facilitate chaining using expressive syntax.
     *
     * @param mixed $action The action to assign.
     * @returns self
     */
    public function toAction($action);

    /**
     * Assigns multiple HTTP methods that the Route will match.
     *
     * A Route can be restricted or permissive when matching based on one or more
     * HTTP methods.  e.g.: A single endpoint may be defined twice, under two
     * different methods (GET and POST) and also two different actions (ListAction
     * and UpdateAction).
     *
     * This method will return itself to facilitate chaining using expressive syntax.
     *
     * @param array $methods Array of strings representing methods that the Route
     *     will match up against.
     * @returns self
     */
    public function fromMethods(array $methods);

    /**
     * Assigns a single HTTP method that the route will match.
     *
     * A Route can be restricted or permissive when matching based on one or more
     * HTTP methods.  e.g.: A single endpoint may be defined twice, under two
     * different methods (GET and POST) and also two different actions (ListAction
     * and UpdateAction).
     *
     * This method will return itself to facilitate chaining using expressive syntax.
     *
     * @param string $method Array of strings representing methods that the Route
     *     will match up against.
     * @returns self
     */
    public function fromMethod($method);

    /**
     * Specifies default HTTP methods that will apply to any newly created Route.
     *
     * @param array $methods An array of default HTTP methods to assign to a route
     *     when no other methods are specified.
     */
    public function setDefaultMethods(array $methods);

    /**
     * Sets a default action for a Route.
     *
     * Useful in situations where a fallback action should be taken when a
     * specific action is not assigned with the route() or toAction() methods.
     *
     * @param mixed $action A default action to assign to all created routes.
     */
    public function setDefaultAction($action);

    /**
     * Specifies a prefix to assign to patterns.
     *
     * Although patterns may be structured per the requirements of the specific
     * application it may become tedious to re-type route patterns that begin
     * with the same text.
     *
     * For example and application that is managing books might have the following
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
     *   $group->route('/list')
     *         ->route('/show/:id')
     *         ->route('/edit/:id');
     *
     * @param string $prefix Sets the prefix to assign to all route patterns.
     */
    public function setPrefix($prefix);

    /**
     * Creates and returns a new RouteGroup.
     *
     * @returns \Fusion\Router\RouteGroup
     */
    public function createGroup();
}