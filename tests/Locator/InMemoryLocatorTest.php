<?php

namespace Georgeff\Bus\Test\Locator;

use Georgeff\Bus\Exception\HandlerNotFoundException;
use Georgeff\Bus\Locator\InMemoryLocator;
use PHPUnit\Framework\TestCase;

final class InMemoryLocatorTest extends TestCase
{
    public function test_locate_returns_handler_from_map(): void
    {
        $locator = new InMemoryLocator([
            'App\\Command\\SendEmail' => 'App\\Handler\\SendEmailHandler',
        ]);

        $this->assertSame(
            'App\\Handler\\SendEmailHandler',
            $locator->locate('App\\Command\\SendEmail')
        );
    }

    public function test_locate_throws_when_command_not_in_map(): void
    {
        $locator = new InMemoryLocator();

        $this->expectException(HandlerNotFoundException::class);

        $locator->locate('App\\Command\\Unknown');
    }
}
