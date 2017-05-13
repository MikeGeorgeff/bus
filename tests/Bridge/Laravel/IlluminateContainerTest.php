<?php

namespace Georgeff\Bus\Test\Bridge\Laravel;

use Georgeff\Bus\Test\TestCase;
use Illuminate\Container\Container;
use Georgeff\Bus\Bridge\Laravel\IlluminateContainer;
use Georgeff\Bus\Container\Container as ContainerInterface;

class IlluminateContainerTest extends TestCase
{
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            ContainerInterface::class, new IlluminateContainer(new Container)
        );
    }

    public function testMake()
    {
        $container = new IlluminateContainer(new Container);

        $instance = $container->make('stdClass');

        $this->assertInstanceOf(\stdClass::class, $instance);
    }
}