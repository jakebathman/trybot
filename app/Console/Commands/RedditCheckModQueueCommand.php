<?php

namespace App\Console\Commands;

use App\Http\Remotes\Reddit;
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

    public function __construct(Reddit $reddit)
    {
        parent::__construct();

        $this->reddit = $reddit;
    }

    public function handle()
    {
        $data = collect($this->reddit->getModqueue())
            ->recursive();
        $posts = Arr::get($data, 'data.children');

        $this->line('Posts: ' . count($posts));

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
}
