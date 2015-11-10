<?php
/**
 * Part of the Fusion.Router package.
 *
 * @author Jason L. Walker
 * @license MIT
 */

namespace Fusion\Tests;

use Fusion\Router\RoutePatternParser;

require '../vendor/autoload.php';

class RoutePatternParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Fusion\Router\Interfaces\RoutePatternParserInterface */
    private $parser;

    public function setUp()
    {
        $this->parser = new RoutePatternParser();
    }

    public function tearDown()
    {
        unset($this->parser);
    }

    public function testRulesAreTranslated()
    {
        $parsed = $this->parser->parsePattern('/[alpha]/[num]/[alnum]/[slug]');
        $this->assertEquals(
            '/[a-zA-Z]+/[0-9]+/[a-zA-Z0-9]+/[a-zA-Z0-9]+[a-zA-Z0-9\-]+',
            $parsed
        );
    }

    public function testParametersAreMapped()
    {
        $this->parser->parsePattern('/:foo/:bar/:baz/:qux');
        $map = $this->parser->getParameterMap();
        $this->assertEquals(4, count($map));
        $this->assertEquals('bar', $map[1]);
        $this->assertEquals('qux', $map[3]);
        $this->assertEquals('foo', $map[0]);
        $this->assertEquals('baz', $map[2]);
    }

    /**
     * @dataProvider invalidStringKey
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownWithBadString($data)
    {
        $this->parser->parsePattern($data);
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