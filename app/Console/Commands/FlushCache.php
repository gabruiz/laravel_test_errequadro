<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GifproviderController;

class FlushCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the application cache';

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
        $gpc->flushCache();
    }
}
