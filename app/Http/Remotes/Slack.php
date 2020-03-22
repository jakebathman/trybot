<?php

namespace App\Http\Remotes;

use Exception;
use Ixudra\Curl\Facades\Curl;

class Slack
{
    const BASE = 'https://slack.com/api/';

    protected $token;
    protected $channelId;

    public function __construct($channelId)
    {
        $this->channelId = $channelId;
        $this->token = config('services.slack.meowbot.oauth_token');
        $this->token = config('services.slack.meowbot.oauth_token');
    }
    
    public function getConversationHistory($limit = 100)
    {
        $method = 'conversations.history';

        $response = Curl::to(static::BASE . $method)
            ->withData([
                'token' => $this->token,
                'channel' => $this->channelId,
                'limit' => 100,
            ])
            ->returnResponseObject()
            ->post();

        $data = json_decode($response->content, true);

        if (isset($data['error'])) {
            // Something went wrong with the request
            throw new Exception("Getting channel history failed (code {$data['error']})");
        }

        return $data['messages'];
    }

    public function deleteMessage($ts)
    {
        $method = 'chat.delete';

        $response = Curl::to(static::BASE . $method)
            ->withData([
                'token' => $this->token,
                'channel' => $this->channelId,
                'ts' => $ts,
            ])
            ->returnResponseObject()
            ->post();

        $data = json_decode($response->content, true);

        if (isset($data['error'])) {
            // Something went wrong with the request
            throw new Exception("Deleting message failed (code {$data['error']})");
        }

        return $data;
    }
}
