<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConversationsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('conversations', function(Blueprint $table)
		{
			$table->string('varDateStart', 254);
			$table->string('varDateEnd', 254);
			$table->string('varActive', 12);
			$table->string('varName', 12);
			$table->text('varInitialMessage', 65535);
			$table->string('varUserId', 254);
			$table->string('groupId', 254);
		});
	}


	public function down()
	{
		$this->schema->drop('conversations');
	}

}
