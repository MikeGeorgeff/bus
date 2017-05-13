<?php

namespace Georgeff\Bus\Bridge\Laravel;

use Georgeff\Bus\Container\Container;

class IlluminateContainer implements Container
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(\Illuminate\Contracts\Container\Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function make($class)
    {
        return $this->container->make($class);
    }
}