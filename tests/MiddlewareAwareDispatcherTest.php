<?php

namespace Georgeff\Bus\Test;

use Georgeff\Bus\DispatcherInterface;
use Georgeff\Bus\MiddlewareAwareDispatcher;
use PHPUnit\Framework\TestCase;

final class MiddlewareAwareDispatcherTest extends TestCase
{
    public function test_dispatch_without_middleware_delegates_to_inner_dispatcher(): void
    {
        $command = new \stdClass();

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->method('dispatch')->willReturn('result');

        $dispatcher = new MiddlewareAwareDispatcher($inner);

        $this->assertSame('result', $dispatcher->dispatch($command));
    }

    public function test_middleware_receives_command_and_next(): void
    {
        $command = new \stdClass();
        $command->value = 'original';

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->method('dispatch')->willReturn('result');

        $receivedCommand = null;

        $middleware = function (object $command, callable $next) use (&$receivedCommand): mixed {
            $receivedCommand = $command;

            return $next($command);
        };

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$middleware]);
        $dispatcher->dispatch($command);

        $this->assertSame($command, $receivedCommand);
    }

    public function test_handler_receives_the_command_passed_to_next(): void
    {
        $original    = new \stdClass();
        $replacement = new \stdClass();

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->expects($this->once())->method('dispatch')->with($this->identicalTo($replacement));

        $middleware = fn (object $command, callable $next): mixed => $next($replacement);

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$middleware]);
        $dispatcher->dispatch($original);
    }

    public function test_each_middleware_and_the_handler_receive_the_command_passed_by_the_previous_stage(): void
    {
        $original = new \stdClass();
        $first    = new \stdClass();
        $second   = new \stdClass();

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->expects($this->once())->method('dispatch')->with($this->identicalTo($second));

        $received = null;

        $outer = fn (object $command, callable $next): mixed => $next($first);

        $innerMiddleware = function (object $command, callable $next) use (&$received, $second): mixed {
            $received = $command;

            return $next($second);
        };

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$outer, $innerMiddleware]);
        $dispatcher->dispatch($original);

        $this->assertSame($first, $received);
    }

    public function test_middleware_can_short_circuit_dispatch(): void
    {
        $inner = $this->createMock(DispatcherInterface::class);
        $inner->expects($this->never())->method('dispatch');

        $middleware = fn (object $command, callable $next): string => 'short-circuited';

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$middleware]);

        $this->assertSame('short-circuited', $dispatcher->dispatch(new \stdClass()));
    }

    public function test_middleware_executes_in_order(): void
    {
        $order = [];

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->method('dispatch')->willReturnCallback(function () use (&$order) {
            $order[] = 'handler';

            return 'result';
        });

        $first = function (object $command, callable $next) use (&$order): mixed {
            $order[] = 'first:before';
            $result = $next($command);
            $order[] = 'first:after';

            return $result;
        };

        $second = function (object $command, callable $next) use (&$order): mixed {
            $order[] = 'second:before';
            $result = $next($command);
            $order[] = 'second:after';

            return $result;
        };

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$first, $second]);
        $dispatcher->dispatch(new \stdClass());

        $this->assertSame(['first:before', 'second:before', 'handler', 'second:after', 'first:after'], $order);
    }

    public function test_middleware_persists_across_multiple_dispatches(): void
    {
        $count = 0;

        $inner = $this->createMock(DispatcherInterface::class);
        $inner->method('dispatch')->willReturn('result');

        $middleware = function (object $command, callable $next) use (&$count): mixed {
            $count++;

            return $next($command);
        };

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$middleware]);
        $dispatcher->dispatch(new \stdClass());
        $dispatcher->dispatch(new \stdClass());

        $this->assertSame(2, $count);
    }

    public function test_middleware_can_modify_return_value(): void
    {
        $inner = $this->createMock(DispatcherInterface::class);
        $inner->method('dispatch')->willReturn('original');

        $middleware = function (object $command, callable $next): string {
            $result = $next($command);

            return $result . ':modified';
        };

        $dispatcher = new MiddlewareAwareDispatcher($inner, [$middleware]);

        $this->assertSame('original:modified', $dispatcher->dispatch(new \stdClass()));
    }
}
