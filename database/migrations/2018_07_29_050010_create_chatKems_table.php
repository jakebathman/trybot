<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatKemsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('chatKems', function(Blueprint $table)
		{
			$table->increments('primary');
			$table->timestamp('timestamp')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('timestampMessage')->nullable();
			$table->string('messageId')->nullable()->unique('messageId');
			$table->string('name')->nullable();
			$table->integer('userId')->nullable();
			$table->integer('createdAt')->unsigned()->nullable();
			$table->integer('groupId')->unsigned()->nullable();
			$table->integer('sentToGroup')->unsigned()->nullable()->default(0);
			$table->dateTime('timestampSent')->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('chatKems');
	}

}
