<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProgressTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('progress', function(Blueprint $table)
		{
			$table->string('progress', 254);
			$table->string('scriptName', 254);
			$table->integer('id', true);
			$table->string('current', 100);
			$table->string('target', 100);
			$table->string('memory', 254)->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('progress');
	}

}
