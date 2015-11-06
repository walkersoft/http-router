<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;


class RoutePatternParser
{
    /** @var string */
    private $parsedPattern;

    /** @var array */
    private $parameterMap;

    /** @var string */
    private $target;

    public function __construct()
    {
        $this->parameterMap = [];
    }

    public function parsePattern($pattern)
    {
        //Clear the param map
        $this->parameterMap = [];

        //Strip leading slash
        $pattern = ltrim($pattern, '/');

        //break pattern apart at '/'
        $parts = explode('/', $pattern);

        //locate names parameters, map and remove
        $parts = $this->mapParameters($parts);

        return '/' . implode('/', $parts);
    }

    private function mapParameters(array $segments)
    {
        foreach ($segments as $key => $part)
        {
            $param = preg_replace_callback(
                '#:[a-zA-Z][a-zA-Z[0-9]*$#',
                function ($matches) use ($key)
                {
                    $this->parameterMap[$key] = substr($matches[0], 1);
                    return '';
                },
                $part
            );

            if (empty($param))
            {
                $param = '.+';
            }

            $segments[$key] = $param;
        }

        return $segments;
    }

    public function getParameterMap()
    {
        return $this->parameterMap;
    }
}