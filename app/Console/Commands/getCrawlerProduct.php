<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Crawler;

class getCrawlerProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:product {appId : The crawler app id} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get crawl results and save';


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
        $appId = $this->argument('appId');
        Crawler::getModel()->getGraphQLResult($appId);
    }
}
