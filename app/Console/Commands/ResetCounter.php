<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\KeywordController;

class ResetCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:counter {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the counter of a keyword';

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
     * @return int
     */
    public function handle()
    {
        $kc = new KeywordController();
        $kc->resetCounter($this->argument('value'));
    }
}
