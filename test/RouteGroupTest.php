<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\RouteGroup;

require '../vendor/autoload.php';

class RouteGroupTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Fusion\Router\RouteGroup */
    private $group;

    public function setUp()
    {
        $this->group = new RouteGroup();
    }

    public function tearDown()
    {
        unset($this->group);
    }

    public function testSettingRoutePattern()
    {
        $this->assertInstanceOf('\Fusion\Router\RouteGroup', $this->group->route('/foo/bar'));
    }

    public function testSettingRoutePatternWithAllArguments()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
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
            '\Fusion\Router\RouteGroup',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
        );
    }

    public function testSettingRouteMethod()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
                        ->fromMethod('GET')
        );
    }

    public function testSettingRouteMethods()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
            $this->group->route('/foo/bar')
                        ->toAction('FooAction')
                        ->fromMethods(['GET', 'POST'])
        );
    }

    public function testSettingRoutePrefix()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
            $this->group->setPrefix('/foo')
        );
    }

    public function testSettingDefaultRouteAction()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
            $this->group->setDefaultAction('FooAction')
        );
    }

    public function testSettingDefaultRouteMethods()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\RouteGroup',
            $this->group->setDefaultMethods(['GET', 'POST'])
        );
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
    public function testExceptionThrownWhenSettingAction($data)
    {
        $this->group->route('/foo/bar')
                    ->toAction($data);
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