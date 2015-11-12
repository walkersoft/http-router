<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Router;

use Fusion\Router\Interfaces\RouteFactoryInterface;

class RouteFactory implements RouteFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function make($pattern, $action = null, array $methods = [], array $parameters = [])
    {
        return new Route($pattern, $action, $methods, $parameters);
    }
}