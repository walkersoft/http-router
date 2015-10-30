<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\RouteStore;

require '../vendor/autoload.php';

class RouteStoreTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fusion\Router\Interfaces\RouteStoreInterface */
    private $store;

    public function setUp()
    {
        $this->store = new RouteStore();
    }

    public function tearDown()
    {
        unset($this->store);
    }

    public function testCreatingRouteStore()
    {
        $this->assertInstanceOf(
            '\Fusion\Router\Interfaces\RouteStoreInterface',
            $this->store
        );
    }

    public function testAddingToStore()
    {
        $this->store->add($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
        $this->assertEquals(0, $this->store->lastId());
        $this->store->add($this->getMock('\Fusion\Router\Interfaces\RouteInterface'));
        $this->assertEquals(1, $this->store->lastId());
    }

    /**
     * @dataProvider invalidStoreValue
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownForBadRoute($data)
    {
        $this->store->strictMode(true);
        $this->store->add($data);
    }

    public function invalidStoreValue()
    {
        return [
            [PHP_INT_MAX],
            [3.1472019234],
            ['foobar'],
            [true],
            [null],
            [[]],
            [fopen('php://memory', 'r')],
            [new \stdClass()]
        ];
    }
}