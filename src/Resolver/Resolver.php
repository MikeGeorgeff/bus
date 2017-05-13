<?php

namespace Georgeff\Bus\Resolver;

interface Resolver
{
    /**
     * Resolve the handler for a command object
     *
     * @param object $command
     * @return object
     */
    public function resolve($command);
}