<?php

namespace App\Console\Commands;

use App\Services\FantasyFootball;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateFantasyLeagues extends Command
{
    protected $signature = 'fantasy:update-leagues';

    protected $description = 'Update info about all fantasy leagues';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info("Updating leagues");
        $f = new FantasyFootball;
        $f->updateAllLeagues();
    }
}
