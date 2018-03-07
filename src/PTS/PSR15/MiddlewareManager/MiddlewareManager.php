<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareManager implements RequestHandlerInterface
{
    /** @var callable[] */
    protected $middlewares = [];

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return $this
     */
    public function push(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $runner = $this->createRunner($this->middlewares);
        return $runner->handle($request);
    }

    /**
     * @param array $handlers
     *
     * @return Runner
     */
    protected function createRunner(array $handlers): Runner
    {
        return new Runner($handlers);
    }
}
