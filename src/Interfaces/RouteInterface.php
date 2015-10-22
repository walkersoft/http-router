<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router\Interfaces;


interface RouteInterface
{
    /**
     * Sets the pattern that is used to match a target to the route.
     *
     * When defining routes the implementing router MUST require a pattern to be
     * present.  Typically the pattern will be used in part or whole for a match
     * using a regular expression.
     *
     * @param string $pattern The route pattern to be used for matching.
     * @returns self
     */
    public function setPattern($pattern);

    /**
     * Returns the route pattern.
     *
     * @returns string
     */
    public function getPattern();

    /**
     * Sets an array of HTTP methods the route will respond to.
     *
     * In addition to matching a URI target to the Route via pattern matching
     * a router MAY also choose to scrutinize the HTTP request method.
     *
     * @param array $methods An array of HTTP methods as strings.
     * @returns self
     */
    public function setMethods(array $methods);

    /**
     * Returns the list of responding HTTP methods as an array.
     *
     * @returns array
     */
    public function getMethods();

    /**
     * Sets the action to take with this routes is selected as a match.
     *
     * Actions are domain-specific and may be a number of different things.
     * Essentially the action will specify what will happen in the domain when
     * the route is selected as a match.  The Route does not need to do anything
     * beyond storing the action information.  Client code will decide what
     * to do with the action when a route is selected as a match.
     *
     * Some examples of an action are:
     *
     *   - A closure/anonymous function
     *   - An invokable object
     *   - A string of a class that will be instantiated/invoked
     *   - An array with values compatible with call_user_func_array()
     *
     * This list is not exhaustive and only represents some of what would be
     * possible in terms of storing an action.
     *
     * @param mixed $action The action value to store.
     * @returns self
     */
    public function setAction($action);

    /**
     * Returns the action value.
     *
     * @returns mixed
     */
    public function getAction();

    /**
     * Sets a list of parameters read in from route segments.
     *
     * All route parameters MUST be stored as an array.
     *
     * In this context the route segments are the pieces of information from
     * the URI target separated by the slashes.  A target of `/show/book/5` has
     * three parameters: show, book, and 5.
     *
     * Example:
     *
     *   array(
     *     0 => 'show',
     *     1 => 'book',
     *     2 => 5
     *   )
     *
     * Route patterns may define named parameters that takes a segment and stores
     * it by name instead of number.  A route pattern will define a named
     * parameter with using the`:name` format.  A pattern defined in a route as
     * `/show/book/:id` will match the target `/show/book/5`.
     *
     * Example:
     *
     *   array(
     *     0 => 'show',
     *     1 => 'book',
     *     'id' => 5
     *   )
     *
     * @param array $params An array of parameters.
     * @returns self
     */
    public function setParameters($params);

    /**
     * Returns all parameters as an array.
     *
     * @returns array
     */
    public function getParameters();

    /**
     * Returns a specific parameter specified by its key.
     *
     * @param mixed $key The key where the parameter is stored.
     * @returns mixed
     * @throws \InvalidArgumentException When an invalid key is given.
     */
    public function getParameter($key);

    /**
     * Returns all named parameters as an array.
     *
     * Named parameters SHOULD be considered any parameter value whose key is
     * not numeric.
     *
     * @returns array
     */
    public function getNamedParameters();

    /**
     * Returns a specific named parameter specified by its key.
     *
     * @param mixed $key The key where the named parameter is stored.
     * @returns mixed
     * @throws \InvalidArgumentException When an invalid key is given.
     */
    public function getNamedParameter($key);
}