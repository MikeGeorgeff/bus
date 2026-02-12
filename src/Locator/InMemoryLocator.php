<?php

namespace Georgeff\Bus\Locator;

use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\Exception\HandlerNotFoundException;

final class InMemoryLocator implements HandlerLocatorInterface
{
    /**
     * [
     *    SendEmail::class => SendEmailHandler::class
     * ]
     *
     * @param array<string, string> $commandToHandlerMap
     */
    public function __construct(private array $commandToHandlerMap = []) {}

    public function locate(string $commandName): string
    {
        $handlerName = $this->commandToHandlerMap[$commandName] ?? null;

        if (!$handlerName) {
            throw new HandlerNotFoundException(sprintf('Handler for command %s not found', $commandName));
        }

        return $handlerName;
    }
}
