<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router\Interfaces;


interface RoutePatternParserInterface
{
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
     * @param string $pattern The pattern to parse.
     * @return string
     * @throws \InvalidArgumentException When $pattern is not a string.
     */
    public function parsePattern($pattern);

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
     * case).  Implementations SHOULD NOT be responsible for updating a matched
     * route with the values of named parameters.
     *
     * @return array
     */
    public function getParameterMap();
}