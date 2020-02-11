<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'App\Events\DiscordChannelCreated' => [
            'App\Listeners\LogDiscordChannelCreated',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\\Slack\\SlackExtendSocialite@handle',
        ],
    ];
    
    public function boot()
    {
        parent::boot();
        
        //
    }
}
