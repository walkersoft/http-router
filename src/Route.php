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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * {@inheritdoc}
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getNamedParameters()
    {
        return $this->namedParameters;
    }

    /**
     * {@inheritdoc}
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