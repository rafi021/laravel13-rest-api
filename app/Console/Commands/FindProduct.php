<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('find-product')]
#[Description('Command description')]
class FindProduct extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
