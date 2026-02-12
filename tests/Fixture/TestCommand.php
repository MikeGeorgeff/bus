<?php

namespace Georgeff\Bus\Test\Fixture;

final class TestCommand
{
    public function __construct(public readonly string $value = 'test') {}
}
