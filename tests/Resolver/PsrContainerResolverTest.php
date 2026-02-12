<?php

namespace Georgeff\Bus\Test\Resolver;

use Georgeff\Bus\Exception\InvalidHandlerException;
use Georgeff\Bus\HandlerLocatorInterface;
use Georgeff\Bus\Resolver\PsrContainerResolver;
use Georgeff\Bus\Test\Fixture\TestCommand;
use Georgeff\Bus\Test\Fixture\TestCommandHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class PsrContainerResolverTest extends TestCase
{
    public function test_resolve_returns_callable_handler_from_container(): void
    {
        $handler = new TestCommandHandler();

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturn($handler);

        $locator = $this->createMock(HandlerLocatorInterface::class);
        $locator->method('locate')->willReturn(TestCommandHandler::class);

        $resolver = new PsrContainerResolver($container, $locator);
        $result = $resolver->resolve(TestCommand::class);

        $this->assertIsCallable($result);
        $this->assertInstanceOf(TestCommandHandler::class, $result);
    }

    public function test_resolve_throws_when_handler_is_not_callable(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturn(new \stdClass());

        $locator = $this->createMock(HandlerLocatorInterface::class);
        $locator->method('locate')->willReturn(\stdClass::class);

        $resolver = new PsrContainerResolver($container, $locator);

        $this->expectException(InvalidHandlerException::class);

        $resolver->resolve(TestCommand::class);
    }
}
