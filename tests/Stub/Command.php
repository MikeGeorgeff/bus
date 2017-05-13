<?php

namespace Georgeff\Bus\Test\Stub;

class Command
{
    public $name;

    public function __construct($name = 'test')
    {
        $this->name = $name;
    }
}