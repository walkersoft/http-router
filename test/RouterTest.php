<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\Route;
use Fusion\Router\RoutePatternParser;
use Fusion\Router\Router;
use Fusion\Router\RouteStore;

require '../vendor/autoload.php';

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fusion\Router\Router */
    private $router;

    public function setUp()
    {
        $this->router = new Router(new RouteStore(), new RoutePatternParser());
    }

    public function tearDown()
    {
        unset($this->router);
    }

    public function testAddingRoutes()
    {
        $this->router->addRoute($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
    }

    public function testGettingRoutes()
    {
        $this->router->addRoute($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
        $this->router->addRoute($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
        $this->router->addRoute($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
        $this->assertInternalType('array', $this->router->getRoutes());
        $this->assertEquals(3, count($this->router->getRoutes()));
    }

    public function testMatchingRoute()
    {
        $this->router->addRoute(new Route('/foo/bar', null, ['GET']));
        $route = $this->router->match('/foo/bar');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
    }

    public function testMatchingEmptyRoute()
    {
        $this->router->addRoute(new Route('/', null, ['GET']));
        $route = $this->router->match('/');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
    }

    public function testMatchingRouteWithNamedParams()
    {
        $this->router->addRoute(new Route('/foo/bar/:baz', null, ['GET']));
        $route = $this->router->match('/foo/bar/blah');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
        $this->assertEquals('blah', $route->getNamedParameter('baz'));
    }

    public function testMatchingRouteWithRules()
    {
        $this->router->addRoute(new Route('/foo/bar/[alpha]', null, ['GET']));
        $route = $this->router->match('/foo/bar/blah');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNotMatchingRouteWithRules()
    {
        $this->router->addRoute(new Route('/foo/bar/[alpha]', null, ['GET']));
        $this->router->match('/foo/bar/8675309');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnableToMatchRoute()
    {
        $this->router->match('/foo/bar');
    }
}