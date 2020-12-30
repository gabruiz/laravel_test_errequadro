<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GifproviderController;

class SetProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'provider:set {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Gif provider';

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
        $gpc->setProviderId($this->argument('value'));
    }
}
