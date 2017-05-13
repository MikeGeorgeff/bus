<?php

namespace Georgeff\Bus\Instance;

use Georgeff\Bus\CommandBus;
use Georgeff\Bus\Resolver\Resolver;
use Georgeff\Bus\Resolver\NativeResolver;
use Georgeff\Bus\Exception\InvalidHandlerException;

class NativeBus implements CommandBus
{
    /**
     * @var \Georgeff\Bus\Resolver\Resolver
     */
    protected $resolver;

    /**
     * @param \Georgeff\Bus\Resolver\Resolver|null $resolver
     */
    public function __construct(Resolver $resolver = null)
    {
        $this->resolver = $resolver ?: new NativeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($command)
    {
        $handler = $this->resolver->resolve($command);

        if (! method_exists($handler, 'handle')) {
            throw new InvalidHandlerException('Handler must contain the method handle.');
        }

        return $handler->handle($command);
    }
}