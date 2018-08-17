<?php

use PHPUnit\Framework\TestCase;
use PTS\PSR15\MiddlewareManager\PathResolver;

/**
 * @covers \PTS\PSR15\MiddlewareManager\PathResolver::makeRegExp
 */
class MakeRegExpTest extends TestCase
{

    /**
     * @param string $path
     * @param string $expected
     *
     * @dataProvider dataProvider
     */
    public function testRegexp(string $path, string $expected): void
    {
        $resolver = new PathResolver;

        $regexp = $resolver->makeRegExp($path);
        self::assertEquals($expected, $regexp);
    }

    public function dataProvider(): array
    {
        return [
            'simple' => ['/controller/action/', '/controller/action/'],
            'placeholder' => ['/controller/{action}/', '/controller/(?<action>[^\/]+)/'],
            //'placeholder + restriction' => ['/controller/{action}/', '/controller/(?<action>\w+)/', ['action' => '\w+']],
        ];
    }

}
