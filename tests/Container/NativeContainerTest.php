<?php

namespace Georgeff\Bus\Test\Container;

use Georgeff\Bus\Test\TestCase;
use Georgeff\Bus\Container\Container;
use Georgeff\Bus\Container\NativeContainer;

class NativeContainerTest extends TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(Container::class, new NativeContainer);
    }

    public function testMake()
    {
        $container = new NativeContainer;

        $instance = $container->make('stdClass');

        $this->assertInstanceOf(\stdClass::class, $instance);
    }
}