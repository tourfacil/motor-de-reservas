<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class TestCrontab extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:crontab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para testar a crontab';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Storage::append("crontab.log", "Log da cron as " . date('d/m/Y H:i:s'));
    }
}
