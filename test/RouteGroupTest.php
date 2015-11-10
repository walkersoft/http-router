<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\RoutePatternParser;
use Fusion\Router\RouteStore;
use Fusion\Router\RouteFactory;
use Fusion\Router\RouteGroup;
use Fusion\Router\Router;

require '../vendor/autoload.php';

class RouteGroupTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Fusion\Router\RouteGroup */
    private $group;

    /** @var \Fusion\Router\Router */
    private $router;

    public function setUp()
    {
        $this->router = new Router(new RouteStore(), new RoutePatternParser());
        $this->group = new RouteGroup($this->router, new RouteFactory());
        $this->group->setDefaultMethods(['GET']);
    }

    public function tearDown()
    {
        unset($this->group);
    }

    public function testSettingRoutePattern()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->route('/foo/bar')
        );
    }

    public function testSettingRoutePatternWithAllArguments()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->route(
                '/foo/bar',
                'FooAction',
                ['GET', 'POST']
            )
        );
    }

    public function testSettingRouteAction()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
        );
    }

    public function testSettingRouteMethod()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
                        ->fromMethod('GET')
        );
        $route = $this->router->match('/foo/bar');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
        $this->assertEquals('FooAction', $route->getAction());
        $this->assertEquals('GET', $route->getMethods()[0]);
    }

    public function testSettingRouteMethods()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
                        ->fromMethods(['GET', 'POST'])
        );
        $route = $this->router->match('/foo/bar');
        $this->assertInstanceOf('\Fusion\Router\Interfaces\RouteInterface', $route);
        $this->assertEquals('FooAction', $route->getAction());
        $this->assertEquals('GET', $route->getMethods()[0]);
        $this->assertEquals('POST', $route->getMethods()[1]);
    }

    public function testSettingRoutePrefix()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->setPrefix('/foo')
        );
        $this->group->route('/bar');
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteInterface',
            $this->router->match('/foo/bar')
        );
    }

    public function testSettingDefaultRouteAction()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->setDefaultAction('FooAction')
        );
    }

    public function testSettingDefaultRouteMethods()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteGroupInterface',
            $this->group->setDefaultMethods(['GET', 'POST'])
        );
    }

    public function testMultipleGroupsUsingSameRouter()
    {
        //Create a route in the first group
        $this->group->route('/foo/bar');
        $this->assertEquals(1, count($this->router->getRoutes()));

        //Create a route in the second group
        $group = $this->group->createGroup();
        $group->setDefaultMethods(['GET']);
        $group->route('/foo/baz');
        $this->assertEquals(2, count($this->router->getRoutes()));

        //Create a route in the third group
        $group = $group->createGroup();
        $group->setDefaultMethods(['GET']);
        $group->route('/foo/bam');
        $this->assertEquals(3, count($this->router->getRoutes()));

        //Match the first route
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteInterface',
            $this->router->match('/foo/bar')
        );

        //Match the second route
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteInterface',
            $this->router->match('/foo/baz')
        );

        //Match the third route
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteInterface',
            $this->router->match('/foo/bam')
        );

    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionThrownWhenEditingActionWithNoRoute()
    {
        $this->group->toAction('Action');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionThrownWhenEditingMethodWithNoRoute()
    {
        $this->group->fromMethod('POST');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionThrownWhenEditingMethodsWithNoRoute()
    {
        $this->group->fromMethods(['GET', 'POST']);
    }

    /**
     * @dataProvider invalidStringData
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenCreatingRoute($data)
    {
        $this->group->route($data);
    }

    /**
     * @dataProvider invalidStringData
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenSettingMethod($data)
    {
        $this->group->route('/foo/bar')
                    ->fromMethod($data);
    }

    /**
     * @dataProvider invalidStringData
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenSettingPrefix($data)
    {
        $this->group->setPrefix($data);
    }

    public function invalidStringData()
    {
        return [
            [false],
            [null],
            [PHP_INT_MAX],
            [2093.211092],
            [fopen('php://memory', 'r+')],
            [new \stdClass()],
            [[]]
        ];
    }
}