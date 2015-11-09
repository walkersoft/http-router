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

    /**
     * List of acceptable rules.
     *
     * @var array
     */
    private $rules = [
        'alphabetic' => '[alpha]',
        'numeric' => '[num]',
        'alphanumeric' => '[alnum]',
        'slug' => '[slug]'
    ];

    /**
     * Regex translation of the rules.
     *
     * @var array
     */
    private $translations = [
        'alphabetic' => '[a-zA-Z]+',
        'numeric' => '[0-9]+',
        'alphanumeric' => '[a-zA-Z0-9]+',
        'slug' => '[a-zA-Z0-9]+[a-zA-Z0-9\-]+'
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parameterMap = [];
    }

    public function parsePattern($pattern)
    {
        //if the pattern is only a '/', there is nothing to do
        if ($pattern == '/')
        {
            return $pattern;
        }

        //Clear the param map
        $this->parameterMap = [];

        //Strip leading slash
        $pattern = ltrim($pattern, '/');

        //break pattern apart at '/'
        $parts = explode('/', $pattern);

        //locate names parameters, map and remove
        $parts = $this->parseSegments($parts);

        return '/' . implode('/', $parts);
    }

    /**
     * Creates a map of named parameters and translates any segment rules.
     *
     * @param array $segments
     * @return array
     */
    private function parseSegments(array $segments)
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
            else
            {
                $param = $this->translateRule($param);
            }

            $segments[$key] = $param;
        }

        return $segments;
    }

    /**
     * Returns all mapped parameters.
     *
     * The array produced is associative where the keys represent the index of
     * the URI segment and the value is the parameter's name.
     *
     * @return array
     */
    public function getParameterMap()
    {
        return $this->parameterMap;
    }

    /**
     * Analyzes a segment for a matching rule and translates it.
     *
     * @param $segment
     * @return string
     */
    public function translateRule($segment)
    {
        foreach ($this->rules as $id => $rule)
        {
            $segment = str_replace($rule, $this->translations[$id], $segment);
        }

        return $segment;
    }
}