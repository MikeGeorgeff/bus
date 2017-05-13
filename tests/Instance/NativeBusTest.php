<?php

namespace Georgeff\Bus\Test\Instance;

use Georgeff\Bus\CommandBus;
use Georgeff\Bus\Test\TestCase;
use Georgeff\Bus\Test\Stub\Command;
use Georgeff\Bus\Instance\NativeBus;
use Georgeff\Bus\Test\Stub\InvalidHandler;
use Georgeff\Bus\Exception\InvalidHandlerException;

class NativeBusTest extends TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(CommandBus::class, new NativeBus);
    }

    public function testExecute()
    {
        $bus = new NativeBus;

        $this->assertEquals('hello mike', $bus->execute(new Command('mike')));
    }

    public function testInvalidHandler()
    {
        $bus = new NativeBus;

        $this->expectException(InvalidHandlerException::class);
        $this->expectExceptionMessage('Handler must contain the method handle.');

        $bus->execute(new InvalidHandler);
    }
}