<?php

namespace Georgeff\Bus\Resolver;

use Psr\Container\ContainerInterface;
use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\HandlerResolverInterface;
use Georgeff\Bus\Exception\InvalidHandlerException;

final class PsrContainerResolver implements HandlerResolverInterface
{
    public function __construct(private ContainerInterface $container, private HandlerLocatorInterface $locator) {}

    public function resolve(string $commandName): callable
    {
        $handlerName = $this->locator->locate($commandName);

        $handler = $this->container->get($handlerName);

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
