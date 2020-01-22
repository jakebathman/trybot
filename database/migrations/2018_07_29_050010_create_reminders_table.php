<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRemindersTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('reminders', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('remindAt')->nullable();
			$table->integer('userId');
			$table->string('username');
			$table->enum('service', array('groupme','reddit'));
			$table->text('text', 65535);
		});
	}


	public function down()
	{
		$this->schema->drop('reminders');
	}

}
