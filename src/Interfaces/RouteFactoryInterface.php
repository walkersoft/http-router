<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router\Interfaces;


interface RouteFactoryInterface
{
    /**
     * Factory method to make a `RouteInterface` instance.
     *
     * @see \Fusion\Router\Interfaces\RouteInterface
     * @param string $pattern The pattern used for matching a target.
     * @param mixed $action Action that will be taken when a `RouteInterface`
     *     instance is selected as a match.
     * @param array $methods Array list of HTTP methods the `RouteInterface`
     *     instance can respond to.
     * @param array $parameters Array list of parameters for the `RouteInterface`
     *     instance.
     * @return \Fusion\Router\Interfaces\RouteInterface
     */
    public function make($pattern, $action = null, array $methods = [], array $parameters = []);
}