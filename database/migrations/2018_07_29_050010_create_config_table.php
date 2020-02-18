<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('config', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('key')->nullable();
			$table->string('value')->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('config');
	}

}
