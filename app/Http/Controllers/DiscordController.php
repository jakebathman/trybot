<?php

namespace App\Http\Controllers;

use App\Http\Controllers\JsonResponse;
use App\Http\Remotes\Discord;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class DiscordController extends Controller
{
    public $guildId = '143966277327781889';

    protected $discord;

    public function __construct()
    {
        $this->discord = new Discord($this->guildId);
    }

    public function create()
    {
        Log::info(json_encode(request()->all()));

        // Check the token
        if (! App::environment('testing') &&
            (! isset(request()->token) || request()->token != config('services.slack.verification_token'))
        ) {
            Log::info("Token mismatch in /discord handler. Check that the token in .env matches the Verification Token found in the Slack App's Basic Information section.");
            abort(401, 'Token mismatch');
        }

        // First, let's get a name generated
        $name = $this->generateName();

        // Create the voice channel
        $newChannelId = $this->discord->createChannel($name);

        $inviteUrl = $this->discord->getChannelInvite($newChannelId);

        // Return info about what we've done
        $responseString = "I created a voice channel for you! It's called {$name} and you can join using this URL: {$inviteUrl}";
        $responseStringMarkdown = "I created a voice channel for you! It's called *{$name}* and you can join using this URL: {$inviteUrl}";

        if (isset(request()->simple_return)) {
            return $responseString;
        }

        if (isset(request()->team_domain)) {
            // Slack request, so instead of returning anything let's post the response to the whole channel
            $sendReply = Curl::to(urldecode(request()->response_url))
                ->withData([
                    'response_type' => 'in_channel',
                    'text' => $responseStringMarkdown,
                ])
                ->asJson()
                ->returnResponseObject()
                ->post();
        } else {
            return JsonResponse::success([
                'invite_url' => $inviteUrl,
                'channel_name' => $name,
            ]);
        }
    }

    public function delete($channelId)
    {
        return $this->discord->deleteChannel($channelId);
    }

    public function generateName($numWords = 3)
    {
        $words = config('words');
        shuffle($words);

        $nameWords = [];

        for ($i = 0; $i < $numWords; $i++) {
            $nameWords[] = array_pop($words);
        }

        return implode('-', $nameWords);
    }
}
