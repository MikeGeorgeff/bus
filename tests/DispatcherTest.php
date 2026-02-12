<?php

namespace Georgeff\Bus\Test;

use Georgeff\Bus\Dispatcher;
use Georgeff\Bus\HandlerResolverInterface;
use PHPUnit\Framework\TestCase;

final class DispatcherTest extends TestCase
{
    public function test_dispatch_returns_handler_result(): void
    {
        $command = new \stdClass();

        $resolver = $this->createMock(HandlerResolverInterface::class);
        $resolver->method('resolve')->willReturn(fn (object $cmd): string => 'handled');

        $dispatcher = new Dispatcher($resolver);

        $this->assertSame('handled', $dispatcher->dispatch($command));
    }

    public function test_dispatch_passes_command_to_handler(): void
    {
        $command = new \stdClass();
        $command->value = 'test-value';

        $resolver = $this->createMock(HandlerResolverInterface::class);
        $resolver->method('resolve')->willReturn(fn (object $cmd): string => $cmd->value);

        $dispatcher = new Dispatcher($resolver);

        $this->assertSame('test-value', $dispatcher->dispatch($command));
    }
}
