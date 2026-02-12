<?php

namespace Georgeff\Bus;

interface DispatcherInterface
{
    public function dispatch(object $command): mixed;
}
