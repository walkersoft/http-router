<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    /** @var \Fusion\Router\Route */
    private $route;

    public function setUp()
    {
        $this->route = new Route('/', 'Action', [], []);
    }

    public function tearDown()
    {
        unset($this->route);
    }

    public function testReadingInitValues()
    {
        $this->assertEquals('/', $this->route->getPattern());
        $this->assertEquals('Action', $this->route->getAction());
        $this->assertInternalType('array', $this->route->getMethods());
        $this->assertEmpty($this->route->getMethods());
        $this->assertInternalType('array', $this->route->getParameters());
        $this->assertEmpty($this->route->getParameters());
        $this->assertInternalType('array', $this->route->getNamedParameters());
        $this->assertEmpty($this->route->getNamedParameters());
    }

    public function testSettingPattern()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setPattern('/foo/bar'));
        $this->assertEquals('/foo/bar', $this->route->getPattern());
    }

    public function testSettingAction()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setAction('FooAction'));
        $this->assertEquals('FooAction', $this->route->getAction());
    }

    public function testSettingMethods()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setMethods(['GET', 'POST']));
        $this->assertInternalType('array', $this->route->getMethods());
        $this->assertEquals(2, count($this->route->getMethods()));
        $this->assertEquals('POST', $this->route->getMethods()[1]);
    }

    public function testSettingParameters()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setParameters(['show', 'books', 5]));
        $this->assertInternalType('array', $this->route->getParameters());
        $this->assertEquals('books', $this->route->getParameter(1));
    }

    public function testSettingNamedParameters()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setParameters(['show', 'books', 'id' => 5]));

        //Test the named parameter at its index
        $this->assertInternalType('array', $this->route->getParameters());
        $this->assertEquals(3, count($this->getParameters()));
        $this->assertEquals(5, $this->route->getParameter(2));

        //Test the named parameter by its name
        $this->assertInternalType('array', $this->route->getNamedParameters());
        $this->assertEquals(1, count($this->route->getNamedParameters()));
        $this->assertEquals(5, $this->route->getNamedParameter('id'));
    }

    public function testNotFindingParameter()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setParameters(['show', 'books', 5]));
        $this->assertNull($this->route->getParameter(29));
    }

    public function testNotFindingNamedParameter()
    {
        $this->assertInstanceOf('\Fusion\Router\Route', $this->route->setParameters(['show', 'books', 'id' => 5]));
        $this->assertNull($this->route->getParameter('foo'));
    }

    /**
     * @dataProvider invalidIntKey
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenBadIntParameterGiven($data)
    {
        $this->route->getParameter($data);
    }

    /**
     * @dataProvider invalidStringKey
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWhenBadStringParameterGiven($data)
    {
        $this->route->getNamedParameter($data);
    }

    public function invalidIntKey()
    {
        return [
            [false],
            [null],
            ['foobar'],
            [2093.211092],
            [fopen('php://memory', 'r+')],
            [new \stdClass()],
            [[]]
        ];
    }

    public function invalidStringKey()
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