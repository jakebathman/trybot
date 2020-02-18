<?php

namespace App\Http\Remotes;

use Ixudra\Curl\Facades\Curl;

class Reddit
{
    public $token;
    public $user;

    public function __construct()
    {
        $this->token = config('services.reddit.rss_feed.token');
        $this->user = config('services.reddit.rss_feed.user');
    }
    
    public function getModqueue()
    {
        $url = "https://www.reddit.com/r/mod/about/modqueue/.json?feed={$this->token}&user={$this->user}";

        $response = Curl::to($url)
            ->withHeaders([
                'User-Agent' => 'trybot2000.com:TryBot:v2.0 (by /u/ironrectangle)',
            ])
            ->asJson(true)
            ->get();

        return $response;
    }
}
