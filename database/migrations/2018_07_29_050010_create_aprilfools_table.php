<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAprilfoolsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('aprilfools', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('botId', 254);
			$table->string('botAvatar', 254);
			$table->string('botName', 254);
			$table->string('userName', 254);
			$table->string('groupId', 254);
		});
	}


	public function down()
	{
		$this->schema->drop('aprilfools');
	}

}
