<?php
namespace test\unit\classes;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareB implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next - MiddlewareManager
     * @return ResponseInterface|mixed
     */
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next)
    {
        return $this->process($request, $next);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $next
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $next): ResponseInterface
    {
        return $next->handle($request);
    }
}