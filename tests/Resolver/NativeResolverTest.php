<?php

namespace Georgeff\Test\Resolver;

use Georgeff\Bus\Test\TestCase;
use Georgeff\Bus\Resolver\Resolver;
use Georgeff\Bus\Test\Stub\Command;
use Georgeff\Bus\Resolver\NativeResolver;
use Georgeff\Bus\Test\Stub\CommandHandler;
use Georgeff\Bus\Test\Stub\MissingHandler;
use Georgeff\Bus\Exception\MissingHandlerException;

class NativeResolverTest extends TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(Resolver::class, new NativeResolver);
    }

    public function testResolve()
    {
        $resolver = new NativeResolver;

        $handler = $resolver->resolve(new Command);

        $this->assertInstanceOf(CommandHandler::class, $handler);
    }

    public function testMissingHandler()
    {
        $resolver = new NativeResolver;

        $this->expectException(MissingHandlerException::class);
        $this->expectExceptionMessage('Handler for class [Georgeff\Bus\Test\Stub\MissingHandler] was not found.');

        $resolver->resolve(new MissingHandler);
    }
}