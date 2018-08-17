<?php

use PHPUnit\Framework\TestCase;
use PTS\PSR15\MiddlewareManager\PathResolver;
use Zend\Diactoros\ServerRequest;

/**
 * @covers \PTS\PSR15\MiddlewareManager\PathResolver::isMatch
 */
class IsMatchTest extends TestCase
{

    /**
     * @param string $path
     * @param string $uriPath
     * @param bool $expected
     *
     * @dataProvider dataProvider
     */
    public function testRegexp(string $path, string $uriPath, bool $expected): void
    {
        $resolver = new PathResolver;

        $request = new ServerRequest([], [], $uriPath);

        $actual = $resolver->isMatch($path, $request);
        self::assertEquals($expected, $actual);
    }

    public function dataProvider(): array
    {
        return [
            'simple' => ['/controller/action/', '/controller/action/', true],
            'placeholder' => ['/controller/{action}/', '/controller/some/', true],
            'regext' => ['/controller/(?<action>[^\/]+)/', '/controller/some2/', true],
            ['/controller/action/', '/controller/', false],
            ['/admin/.*/', '/controller/', false],
            ['/admin/.*', '/admin/user/1', true],
        ];
    }

}
