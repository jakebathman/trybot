<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBotsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('bots', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('groupName', 254);
			$table->string('groupId', 254);
			$table->string('botName', 254);
			$table->string('botId', 254);
			$table->string('botAvatarUrl', 254)->nullable();
			$table->string('botCallbackUrl', 254)->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('bots');
	}

}
