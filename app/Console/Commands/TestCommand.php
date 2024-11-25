<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line("\nThis is a Sentense in console box.\n");
        $this->info("This is a Sentense in console box.\n");
        $this->error("This is a Sentense in console box.\n");
        $this->comment("This is a Sentense in console box.\n");
    }
}
