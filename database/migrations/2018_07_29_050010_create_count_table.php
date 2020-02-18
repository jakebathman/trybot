<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('count', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('groupId', 254);
			$table->string('subject', 254);
			$table->integer('total');
		});
	}


	public function down()
	{
		$this->schema->drop('count');
	}

}
