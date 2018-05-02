<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PTS\Events\EmitterTrait;

class Runner implements RequestHandlerInterface
{
    use EmitterTrait;

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

        $request = $this->filter('middleware.before.process', $request, [$middleware]);
        $response = $middleware->process($request, $this);

        return $this->filter('middleware.after.process', $response, [$middleware, $request]);
    }
}
