<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRedditTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('reddit', function(Blueprint $table)
		{
			$table->string('subreddit', 254);
			$table->string('name', 254)->unique('name');
			$table->string('id', 50);
			$table->string('stickied', 254)->nullable();
			$table->string('author', 254);
			$table->string('url', 254);
			$table->string('tryBotPostedToGroupMe', 254)->nullable();
			$table->string('created_utc', 254);
			$table->string('title', 254);
			$table->text('selftext_html', 65535)->nullable();
			$table->text('selftext', 65535)->nullable();
			$table->string('isIntroPost', 10)->nullable();
			$table->string('tryBotRespondToIntroPost', 10)->nullable();
			$table->string('gamertag', 254)->nullable();
			$table->string('game', 254)->nullable();
			$table->text('botPostReturn', 65535)->nullable();
			$table->string('accountAge', 254)->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('reddit');
	}

}
