<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuessWhoPointsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('guessWhoPoints', function(Blueprint $table)
		{
			$table->increments('Id');
			$table->timestamp('Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('GameId')->unsigned();
			$table->string('UserId', 50)->nullable();
			$table->string('Guess', 50)->nullable();
			$table->integer('Points')->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('guessWhoPoints');
	}

}
