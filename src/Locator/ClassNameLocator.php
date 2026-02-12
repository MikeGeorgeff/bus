<?php

namespace Georgeff\Bus\Locator;

use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\Exception\HandlerNotFoundException;

final class ClassNameLocator implements HandlerLocatorInterface
{
    public function locate(string $commandName): string
    {
        $handlerName = $commandName . 'Handler';

        if (!class_exists($handlerName)) {
            throw new HandlerNotFoundException(sprintf(
                'Unable to find handler %s for command %s',
                $handlerName,
                $commandName
            ));
        }

        return $handlerName;
    }
}
