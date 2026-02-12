<?php

namespace Georgeff\Bus\Test\Resolver;

use Georgeff\Bus\Exception\InvalidHandlerException;
use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\Resolver\SimpleResolver;
use Georgeff\Bus\Test\Fixture\TestCommand;
use Georgeff\Bus\Test\Fixture\TestCommandHandler;
use PHPUnit\Framework\TestCase;

final class SimpleResolverTest extends TestCase
{
    public function test_resolve_returns_callable_handler(): void
    {
        $locator = $this->createMock(HandlerLocatorInterface::class);
        $locator->method('locate')->willReturn(TestCommandHandler::class);

        $resolver = new SimpleResolver($locator);
        $handler = $resolver->resolve(TestCommand::class);

        $this->assertIsCallable($handler);
        $this->assertInstanceOf(TestCommandHandler::class, $handler);
    }

    public function test_resolve_throws_when_handler_is_not_callable(): void
    {
        $locator = $this->createMock(HandlerLocatorInterface::class);
        $locator->method('locate')->willReturn(\stdClass::class);

        $resolver = new SimpleResolver($locator);

        $this->expectException(InvalidHandlerException::class);

        $resolver->resolve(TestCommand::class);
    }
}
