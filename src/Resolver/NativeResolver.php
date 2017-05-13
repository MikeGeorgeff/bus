<?php

namespace Georgeff\Bus\Resolver;

use Georgeff\Bus\Container\Container;
use Georgeff\Bus\Container\NativeContainer;
use Georgeff\Bus\Exception\MissingHandlerException;

class NativeResolver implements Resolver
{
    /**
     * @var \Georgeff\Bus\Container\Container
     */
    protected $container;

    /**
     * @param \Georgeff\Bus\Container\Container|null $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container ?: new NativeContainer;
    }

    public function resolve($command)
    {
        $class = get_class($command);

        $handler = $class . 'Handler';

        if (class_exists($handler)) {
            return $this->container->make($handler);
        }

        throw new MissingHandlerException(
            'Handler for class [' . $class . '] was not found.'
        );
    }
}