<?php

namespace Georgeff\Bus\Resolver;

use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\HandlerResolverInterface;
use Georgeff\Bus\Exception\InvalidHandlerException;

final class SimpleResolver implements HandlerResolverInterface
{
    public function __construct(private HandlerLocatorInterface $locator) {}

    public function resolve(string $commandName): callable
    {
        $handlerName = $this->locator->locate($commandName);

        $handler = new $handlerName();

        if (!is_callable($handler)) {
            throw new InvalidHandlerException(sprintf(
                'Handler %s for command %s must be a callable',
                $handlerName,
                $commandName
            ));
        }

        return $handler;
    }
}
