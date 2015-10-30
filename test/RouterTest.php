<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\Route;
use Fusion\Router\Router;
use Fusion\Router\RouteStore;

require '../vendor/autoload.php';

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fusion\Router\Router */
    private $router;

    public function setUp()
    {
        $this->router = new Router(new RouteStore());
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
        $this->router->addRoute(new Route('/foo/bar'));
        $route = $this->router->match('/foo/bar');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnableToMatchRoute()
    {
        $this->router->match('/foo/bar');
    }
}