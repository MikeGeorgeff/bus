<?php

namespace Georgeff\Bus;

interface CommandBus
{
    /**
     * Execute the given command class
     *
     * @param object $command
     * @return mixed
     */
    public function execute($command);
}