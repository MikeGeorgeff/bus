<?php

namespace Georgeff\Bus\Test\Locator;

use Georgeff\Bus\Exception\HandlerNotFoundException;
use Georgeff\Bus\Locator\ClassNameLocator;
use Georgeff\Bus\Test\Fixture\TestCommand;
use Georgeff\Bus\Test\Fixture\TestCommandHandler;
use PHPUnit\Framework\TestCase;

final class ClassNameLocatorTest extends TestCase
{
    public function test_locate_returns_handler_class_name(): void
    {
        $locator = new ClassNameLocator();

        $this->assertSame(
            TestCommandHandler::class,
            $locator->locate(TestCommand::class)
        );
    }

    public function test_locate_throws_when_handler_class_does_not_exist(): void
    {
        $locator = new ClassNameLocator();

        $this->expectException(HandlerNotFoundException::class);

        $locator->locate('NonExistent\\Command');
    }
}
