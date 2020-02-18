<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateSlackEmojiList::class,
        Commands\GetCurrentTwitchStreams::class,
        Commands\FantasyFootballUpdateData::class,
        Commands\UpdateFortniteTrackerCommand::class,
        Commands\UpdateOverbuffCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('slack:emoji')->hourly();
        $schedule->command('twitch:streams')->everyMinute();

        $schedule->command('fortnite:update')->cron('*/30  *  *  *  *');
        $schedule->command('overbuff:update')->cron('*/20  *  *  *  *');

        $schedule->command('reddit:check-modqueue')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        
        require base_path('routes/console.php');
    }
}
