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
    /**
     * Mapping of named parameters.
     *
     * This array contains only numeric keys whose value represent the index and
     * name of the URI path segment that contained a named parameter.
     *
     * E.g.: `/foo/bar/:baz` will create an array with one element: [2 => 'baz']
     *
     * @var array
     */
    private $parameterMap;

    /**
     * List of acceptable rules.
     *
     * Rules are placeholders that will be translated to various regular
     * expression values that will be used by the pattern matching implementation.
     *
     * There MUST be an accompanying element in `self::translations` in order
     * for a translation to take place.
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
     * Regular expression translations for the various rules.
     *
     * There MUST be an accompanying element in `self::rules` in order for a
     * translation to take place.
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

    /**
     * Parses a route pattern and returns the output of the operation as a string.
     *
     * Parser implementations MUST analyze the incoming pattern for any regular
     * expression rules to translate and named parameters defined in the pattern.
     *
     * Implementations SHOULD NOT normalize the pattern unless the normalization
     * is to ensure parsing is performed correctly. In this case implementations
     * SHOULD undo any normalization afterwards.
     *
     * @param $pattern
     * @return string
     * @throws \InvalidArgumentException When $pattern is not a string.
     */
    public function parsePattern($pattern)
    {
        if (!is_string($pattern))
        {
            throw new \InvalidArgumentException(
                sprintf('Pattern to parse must be presented as a string. % given.', gettype($pattern))
            );
        }
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
     * Returns all mapped parameters.
     *
     * The array produced is associative where the keys represent the index of
     * the URI segment and the value is the parameter's name.
     *
     * E.g.: Given a route with a pattern of `/foo/bar/:baz`, with `:baz`
     * representing a named parameter, the following array would be produced:
     *
     *     [2 => 'baz']
     *
     * This array would inform consumers of the parser that in a matching route
     * the third segment in the URI path could be accessed by name (baz in this
     * case).
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
    private function translateRule($segment)
    {
        foreach ($this->rules as $id => $rule)
        {
            $segment = str_replace($rule, $this->translations[$id], $segment);
        }

        return $segment;
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
}