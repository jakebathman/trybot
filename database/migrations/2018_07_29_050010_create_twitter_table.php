<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTwitterTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('twitter', function(Blueprint $table)
		{
			$table->string('varTimeTweetCreatedStamp', 254);
			$table->string('varTimeTweetCreated', 254)->comment('DATE_ISO8601 Format');
			$table->string('varTweetId', 254)->unique('varTweetId');
			$table->text('varText', 65535);
			$table->string('varSentAt', 254);
		});
	}


	public function down()
	{
		$this->schema->drop('twitter');
	}

}
