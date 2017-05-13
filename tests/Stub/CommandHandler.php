<?php

namespace Georgeff\Bus\Test\Stub;

class CommandHandler
{
    public function handle(Command $command)
    {
        return 'hello '.$command->name;
    }
}