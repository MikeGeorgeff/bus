<?php

namespace Georgeff\Bus\Container;

class NativeContainer implements Container
{
    /**
     * {@inheritdoc}
     */
    public function make($class)
    {
        return new $class;
    }
}