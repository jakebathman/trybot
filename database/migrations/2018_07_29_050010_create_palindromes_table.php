<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePalindromesTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('palindromes', function(Blueprint $table)
		{
			$table->increments('primary');
			$table->string('id', 100)->nullable();
			$table->text('text', 65535)->nullable();
			$table->integer('length')->unsigned()->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('palindromes');
	}

}
