<?php

namespace App\Console\Commands;

use App\Http\Remotes\Reddit;
use App\Http\Remotes\Slack;
use App\Notifications\RedditModQueueClear;
use App\Notifications\RedditModQueueItem;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class RedditCheckModQueueCommand extends Command
{
    protected $signature = 'reddit:check-modqueue';
    protected $description = 'Check for new items in the modqueue and alert if any are found.';
    protected $reddit;
    protected $slackChannel;

    public function __construct(Reddit $reddit)
    {
        parent::__construct();

        $this->reddit = $reddit;
        $this->slackChannel = config('services.slack.meowbot.modqueue_channel');
    }

    public function handle()
    {
        // Get any items in the reddit mod queue
        $data = collect($this->reddit->getModqueue())
            ->recursive();
        $posts = Arr::get($data, 'data.children');

        $this->line('Posts: ' . count($posts));

        if ($posts->isEmpty()) {
            if (! $this->hasBeenCleared($this->slackChannel)) {
                $this->line("Clearing existing Slack messages");

                $slack = new Slack($this->slackChannel);
                $hasClearMessage = false;

                collect($slack->getConversationHistory())
                ->each(function ($message) use ($slack, &$hasClearMessage) {
                    if (Arr::get($message, 'attachments.0.title') == "Mod Queue Clear") {
                        // Keep this message
                        $hasClearMessage = true;
                        return false;
                    }
                    
                    return $slack->deleteMessage($message['ts']);
                });
            
                $this->info('Successfully deleted all messages');

                if (! $hasClearMessage) {
                    Notification::route('slack', config('services.slack.modqueue_webhook'))
                        ->notifyNow(new RedditModQueueClear);
                }

                $this->logCleared($this->slackChannel);
            }
        } else {
            $this->unLogCleared($this->slackChannel);
            
            $this->line('Sending Slack notifications...');
            $posts->each(function ($post) {
                $postId = Arr::get($post, 'data.id');
                if ($this->hasBeenSent($postId)) {
                    $this->comment('Skipping: ' . Arr::get($post, 'data.title'));
                } else {
                    $this->info('Sending: ' . Arr::get($post, 'data.title'));
                
                    Notification::route('slack', config('services.slack.modqueue_webhook'))
                        ->notifyNow(new RedditModQueueItem($post));

                    $this->logSent($postId);
                }
            });
        }

        return 0;
    }

    protected function hasBeenSent($id)
    {
        if (Redis::get('reddit:check-modqueue:' . $id)) {
            return true;
        }
    }

    protected function logSent($id)
    {
        Redis::set('reddit:check-modqueue:' . $id, true);
    }

    protected function hasBeenCleared($id)
    {
        if (Redis::get('reddit:check-modqueue:channel-cleared:' . $id)) {
            return true;
        }
    }

    protected function logCleared($id)
    {
        Redis::set('reddit:check-modqueue:channel-cleared:' . $id, true);
    }

    protected function unLogCleared($id)
    {
        Redis::del('reddit:check-modqueue:channel-cleared:' . $id);
    }
}
