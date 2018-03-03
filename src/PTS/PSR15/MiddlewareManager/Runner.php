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
    /** @var int */
    protected $position = 0;

    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
        $this->position = 0;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!array_key_exists($this->position, $this->handlers)) {
            throw new \OutOfRangeException('Handler not found');
        }

        $middleware = $this->handlers[$this->position];
        $this->position++;

        return $middleware->process($request, $this);
    }
}
