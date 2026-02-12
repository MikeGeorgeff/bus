<?php

namespace Georgeff\Bus\Test\Fixture;

final class TestCommandHandler
{
    public function __invoke(TestCommand $command): string
    {
        return $command->value;
    }
}
