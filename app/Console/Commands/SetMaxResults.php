<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use Illuminate\Console\Command;
use App\Http\Controllers\GifproviderController;

class SetMaxResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maxresults:set {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a max value for results';

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
        $gpc = new GifproviderController();
        $gpc->setMaxResults($this->argument('value'));
    }
}
