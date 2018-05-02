<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Runner implements RequestHandlerInterface
{
    /** @var MiddlewareInterface[] */
    protected $handlers = [];

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (\count($this->handlers) === 0) {
            throw new \OutOfRangeException('Handler not found');
        }

        $middleware = array_shift($this->handlers);
        return $middleware->process($request, $this);
    }
}
