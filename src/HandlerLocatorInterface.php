<?php

namespace Georgeff\Bus;

interface HandlerLocatorInterface
{
    /**
     * @throws \Georgeff\Bus\Exception\HandlerNotFoundException
     */
    public function locate(string $commandName): string;
}
