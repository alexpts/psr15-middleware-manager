<?php

use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use PTS\PSR15\MiddlewareManager\MiddlewareManager;
use test\unit\classes\MiddlewareA;
use test\unit\classes\MiddlewareB;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequestFactory;

include_once __DIR__ . '/classes/MiddlewareA.php';
include_once __DIR__ . '/classes/MiddlewareB.php';

class MiddlewareManagerTest extends TestCase
{
    /** @var MiddlewareManager */
    protected $manager;
    /** @var ServerRequestInterface */
    protected $request;

    protected function setUp(): void
    {
        $this->manager = new MiddlewareManager;
        $this->request = ServerRequestFactory::fromGlobals();
    }

    public function testCreate(): void
    {
        $this->assertInstanceOf(MiddlewareManager::class, $this->manager);
    }

    /**
     * @throws Throwable
     */
    public function testSimpleMiddleware(): void
    {
        $this->manager->push(new MiddlewareA);
        /** @var JsonResponse $response */
        $response = $this->manager->handle($this->request);

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInstanceOf(JsonResponse::class, $response);

        $data = $response->getPayload();
        $this->assertCount(3, $data);
        $this->assertEquals(200, $data['status']);
    }

    /**
     * @throws Throwable
     */
    public function testRunNotDefinedNext(): void
    {
        $this->expectException('OutOfRangeException');
        $this->expectExceptionMessage('Handler not found');

        $this->manager->push(new MiddlewareB);
        $this->manager->handle($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testWithoutMiddlewares(): void
    {
        $this->expectException('OutOfRangeException');
        $this->expectExceptionMessage('Handler not found');
        $this->manager->handle($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testMultiInvoke(): void
    {
        $this->manager->push(new MiddlewareA);
        /** @var JsonResponse $response1 */
        $response1 = $this->manager->handle($this->request);
        /** @var JsonResponse $response2 */
        $response2 = $this->manager->handle($this->request);

        $this->assertCount(3, $response1->getPayload());
        self::assertSame(200, $response1->getStatusCode());

        $this->assertCount(3, $response2->getPayload());
        self::assertSame(200, $response2->getStatusCode());
    }
}
