<?php

namespace PTS\PSR15\MiddlewareManager;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Runner implements RequestHandlerInterface
{
    /** @var MiddlewareManager */
    protected $manager;
    /** @var int */
    protected $index = 0;

    /** @var PathResolver|null */
    protected $pathResolver;

    public function __construct(MiddlewareManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        [$next, $path] = $this->manager->get($this->index);

        if (null === $next) {
            throw new \OutOfRangeException('Handler not found');
        }

        $this->rewindBy(1);
        $response = $this->callNext($path, $request, $next);
        $this->rewindBy(-1);

        return $response;
    }

    protected function callNext(?string $path, ServerRequestInterface $request, MiddlewareInterface $next)
    {
        if (null === $path) {
            return $next->process($request, $this);
        }

        return $this->pathResolver->isMatch($path, $request)
            ? $next->process($request, $this)
            : $this->handle($request);
    }

    public function setPathResolver(?PathResolver $resolver): void
    {
        $this->pathResolver = $resolver;
    }

    protected function rewindBy(int $val = 1): void
    {
        $this->index += $val;
    }
}
