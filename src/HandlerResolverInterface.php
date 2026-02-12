<?php

namespace Georgeff\Bus;

interface HandlerResolverInterface
{
    /**
     * @throws \Georgeff\Bus\Exception\InvalidHandlerException
     */
    public function resolve(string $commandName): callable;
}
