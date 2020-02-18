<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('events', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->string('name', 254);
			$table->string('eventId', 254);
			$table->string('attending', 254);
			$table->string('createdAt', 254);
		});
	}


	public function down()
	{
		$this->schema->drop('events');
	}

}
