<?php

namespace App\Console\Commands;

use App\Services\FantasyFootball;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FantasyFootballUpdateData extends Command
{
    protected $signature = 'fantasy:update';
    protected $description = 'Various scheduled updates and tasks for fantasy football';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $f = new FantasyFootball;

        Log::info("Updating NFL schedule");
        $f->updateNflSchedule();
        $f->updateNflBroadcastInfo();
        Log::info("");

        Log::info("Updating fantasy football data");
        $f->updateAllLeagues();
        Log::info("");

        Log::info("Processing notifications");
        $f->processPlayerStatusChangeNotifications();
        $f->processPlayerProTeamIdNotifications();
        $f->processTransactionNotifications();

        Log::info("DONE!");
    }
}
