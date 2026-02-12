<?php

namespace Georgeff\Bus;

final class MiddlewareAwareDispatcher implements DispatcherInterface
{
    private DispatcherInterface $dispatcher;

    /**
     * @var callable[]
     */
    private array $middleware;

    /**
     * @param callable[] $middleware
     */
    public function __construct(DispatcherInterface $dispatcher, array $middleware = [])
    {
        $this->dispatcher = $dispatcher;
        $this->middleware = $middleware;
    }

    public function dispatch(object $command): mixed
    {
        $pipeline = fn(): mixed => $this->dispatcher->dispatch($command);

        foreach (array_reverse($this->middleware) as $middleware) {
            $pipeline = function (object $command) use ($pipeline, $middleware) {
                return $middleware($command, $pipeline);
            };
        }

        return $pipeline($command);
    }
}
