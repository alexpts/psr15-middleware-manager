<?php

use PHPUnit\Framework\TestCase;
use PTS\PSR15\MiddlewareManager\MiddlewareManager;
use PTS\PSR15\MiddlewareManager\PathResolver;

/**
 * @covers \PTS\PSR15\MiddlewareManager\MiddlewareManager::setPathResolver
 */
class SetPathResolverTest extends TestCase
{

    protected const TEST_CLASS = MiddlewareManager::class;

    /**
     * @throws ReflectionException
     */
    public function testMethod(): void
    {
        $manager = new MiddlewareManager;
        $resolver = $this->createMock(PathResolver::class);
        $manager->setPathResolver($resolver);

        $property = new ReflectionProperty(self::TEST_CLASS, 'pathResolver');
        $property->setAccessible(true);
        $actual = $property->getValue($manager);

        $this->assertInstanceOf(PathResolver::class, $actual);
    }
}
