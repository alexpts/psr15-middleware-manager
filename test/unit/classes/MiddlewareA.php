<?php
namespace test\unit\classes;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class MiddlewareA implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $next - MiddlewareManager
     *
     * @return ResponseInterface|mixed
     * @throws \InvalidArgumentException
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next)
    {
        return $this->process($request, $next);
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $next
     *
     * @return ResponseInterface
     *
     * @throws \InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        return new JsonResponse([
            'status' => 200,
            'body' => 'Hello World',
            'from' => 'A'
        ]);
    }
}