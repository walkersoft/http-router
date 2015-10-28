<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Router\Interfaces\RouteInterface;

class Route implements RouteInterface
{

    /**
     * Route pattern.
     *
     * @var string
     */
    private $pattern;

    /**
     * Route action
     *
     * @var mixed
     */
    private $action;

    /**
     * Responding methods.
     *
     * @var array
     */
    private $methods;

    /**
     * Route parameters.
     *
     * @var array
     */
    private $parameters;

    /**
     * Route named parameters.
     *
     * @var array
     */
    private $namedParameters;

    /**
     * Constructor.
     *
     * Accepts a pattern, action, list of HTTP methods and list of parameters.
     *
     * The Route object SHOULD NOT be concerned with any parsing of the given
     * arguments.  E.g.: The $pattern SHOULD already be in parsed for (in this
     * case ready for regex) before being provided.
     *
     * The same goes for all parameters. The provided arguments SHOULD already
     * be filtered and/or verified higher up in client code.
     *
     * @param string $pattern The pattern used for matching a target.
     * @param mixed $action Action that will be taken when this Route is selected
     *     as a match.
     * @param array $methods Array list of HTTP methods the Route can respond to.
     * @param array $parameters Array list of parameters for the Route.
     */
    public function __construct($pattern, $action = null, array $methods = [], array $parameters = [])
    {
        $this->setPattern($pattern);
        $this->setAction($action);
        $this->setMethods($methods);
        $this->setParameters($parameters);
    }

    /**
     * Sets the pattern that is used to match a target to the route.
     *
     * When defining routes the implementing router MUST require a pattern to be
     * present.  Typically the pattern will be used in part or whole for a match
     * using a regular expression.
     *
     * @param string $pattern The route pattern to be used for matching.
     * @return self
     * @throws \InvalidArgumentException When $pattern is not a valid string.
     */
    public function setPattern($pattern)
    {
        if(!is_string($pattern))
        {
            throw new \InvalidArgumentException(
                sprintf('Route pattern must be a string. %s given.', gettype($pattern))
            );
        }

        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Returns the route pattern.
     *
     * @returns string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets an array of HTTP methods the route will respond to.
     *
     * In addition to matching a URI target to the Route via pattern matching
     * a router MAY also choose to scrutinize the HTTP request method.
     *
     * @param array $methods An array of HTTP methods as strings.
     * @returns self
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * Returns the list of responding HTTP methods as an array.
     *
     * @returns array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Sets the action taken when the RouteInterface instance is a match.
     *
     * Actions are domain-specific and may be a number of different things.
     * Essentially the action will specify what will happen in the domain when
     * the RouteInterface instance is selected as a match.  The RouteInterface
     * instance SHOULD NOT need to do anything beyond storing the action data.
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
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Returns the action value.
     *
     * @returns mixed
     */
    public function getAction()
    {
        return $this->action;
    }

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
     * it by name in addition to number.  A route pattern will define a named
     * parameter by using the`:name` format.  A pattern defined in a route as
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
     * Implementations of this interface MUST maintain the capability to locate
     * the value of a named parameter under a numerical index if needed.
     *
     * @param array $params An array of parameters.
     * @returns self
     */
    public function setParameters(array $params)
    {
        $this->parameters = [];
        $this->namedParameters = [];
        $i = 0;

        foreach($params as $key => $param)
        {
            if(is_string($key))
            {
                $this->namedParameters[$key] = $param;
            }

            $this->parameters[$i] = $param;
            ++$i;
        }

        return $this;
    }

    /**
     * Returns all parameters as an array.
     *
     * @returns array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Returns a specific parameter specified by its key or null if not found.
     *
     * @param mixed $key The key where the parameter is stored.
     * @returns mixed|null
     * @throws \InvalidArgumentException When an invalid key is given.
     */
    public function getParameter($key)
    {
        if(!is_int($key))
        {
            throw new \InvalidArgumentException(
                sprintf('Parameter key should be an integer. %s given.', gettype($key))
            );
        }

        $param = null;

        if(array_key_exists($key, $this->parameters))
        {
            $param = $this->parameters[$key];
        }

        return $param;
    }

    /**
     * Returns all named parameters as an array.
     *
     * Named parameters SHOULD be considered any parameter value whose key is
     * not numeric.
     *
     * @returns array
     */
    public function getNamedParameters()
    {
        return $this->namedParameters;
    }

    /**
     * Returns a specific named parameter specified by its key or null if not found.
     *
     * @param mixed $key The key where the named parameter is stored.
     * @returns mixed|null
     * @throws \InvalidArgumentException When an invalid key is given.
     */
    public function getNamedParameter($key)
    {
        if(!is_string($key))
        {
            throw new \InvalidArgumentException(
                sprintf('Named parameter key must be a string. %s given', gettype($key))
            );
        }
        $param = null;

        if(array_key_exists($key, $this->namedParameters))
        {
            $param = $this->namedParameters[$key];
        }

        return $param;
    }
}