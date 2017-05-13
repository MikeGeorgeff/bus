<?php

namespace Georgeff\Bus\Container;

interface Container
{
    /**
     * Instantiate the handler instance
     *
     * @param string $class
     * @return object
     */
    public function make($class);
}