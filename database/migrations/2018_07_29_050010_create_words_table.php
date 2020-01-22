<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWordsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('words', function(Blueprint $table)
		{
			$table->increments('Primary');
			$table->string('Word', 50)->nullable()->unique('Word');
		});
	}


	public function down()
	{
		$this->schema->drop('words');
	}

}
