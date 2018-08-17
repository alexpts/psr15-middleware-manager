<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareManager implements RequestHandlerInterface
{
    /** @var array */
    protected $next = [];
    /** @var PathResolver|null */
    protected $pathResolver;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Throwable
     *
     * @deprecated
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }

    public function get(int $index = 0): ?array
    {
        return $this->next[$index] ?? null;
    }

    public function setPathResolver(PathResolver $pathResolver): void
    {
        $this->pathResolver = $pathResolver;
    }

    /**
     * @param MiddlewareInterface $middleware
     *
     * @return $this
     *
     * @deprecated
     */
    public function push(MiddlewareInterface $middleware): self
    {
        return $this->use($middleware);
    }

    /**
     * @param MiddlewareInterface $middleware
     * @param string|null $path
     *
     * @return $this
     */
    public function use(MiddlewareInterface $middleware, string $path = null): self
    {
        $this->next[] = [
            $middleware,
            $path
        ];

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
        $runner = $this->createRunner();
        return $runner->handle($request);
    }

    /**
     * @return Runner
     */
    protected function createRunner(): Runner
    {
        $runner = new Runner($this);
        $runner->setPathResolver($this->pathResolver);

        return $runner;
    }
}
