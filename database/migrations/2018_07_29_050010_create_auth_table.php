<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('auth', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->string('authToken', 254);
			$table->string('userToken', 254)->comment('internal use');
			$table->string('service', 254);
			$table->string('userId', 150)->nullable();
			$table->integer('expiresAt');
			$table->unique(['authToken','userToken','service'], 'authToken');
		});
	}


	public function down()
	{
		$this->schema->drop('auth');
	}

}
