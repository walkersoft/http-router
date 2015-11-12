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
     * Parses a pattern and returns the output of the operation as a string.
     *
     * Parser implementations MUST analyze the incoming pattern for special rules
     * and named parameters.
     *
     * Implementations SHOULD NOT normalize the pattern unless the normalization
     * is to ensure parsing is performed correctly. In this case implementations
     * SHOULD undo any normalization afterwards.
     *
     * @param string $pattern The pattern to parse.
     * @return string
     * @throws \InvalidArgumentException When `$pattern` is not a string.
     */
    public function parsePattern($pattern);

    /**
     * Returns all mapped named parameters as an array.
     *
     * The array produced is associative where the keys represent the index of
     * the URI segment and the value is the parameter's name.
     *
     * E.g.: Given a route with a pattern of `/foo/bar/:baz`, with `:baz`
     * representing a named parameter, the following array would be produced:
     *
     * <code>
     * array (
     *     2 => 'baz'
     * )
     * </code>
     *
     * This array would inform consumers of the parser that in a matching endpoint
     * the third segment in the URI path could be accessed by name (`baz` in this
     * case).
     *
     * Implementations of this method MUST NOT be responsible for updating a
     * matched `RouteInterface` instance with the values of named parameters.
     *
     * @return array
     */
    public function getParameterMap();
}