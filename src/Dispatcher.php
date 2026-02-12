<?php

namespace Georgeff\Bus;

final class Dispatcher implements DispatcherInterface
{
    public function __construct(private HandlerResolverInterface $resolver) {}

    public function dispatch(object $command): mixed
    {
        $handler = $this->resolver->resolve($command::class);

        return $handler($command);
    }
}
