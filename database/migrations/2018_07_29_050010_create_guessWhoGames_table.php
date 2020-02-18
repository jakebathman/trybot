<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuessWhoGamesTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('guessWhoGames', function(Blueprint $table)
		{
			$table->increments('Id');
			$table->timestamp('Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('GroupId', 50)->nullable();
			$table->string('UserIdInitiator', 50)->nullable();
			$table->string('MysteryUserId', 50)->nullable();
			$table->integer('IsActive')->unsigned()->nullable()->default(1);
			$table->integer('OmitFromStatistics')->unsigned()->nullable()->default(0);
		});
	}


	public function down()
	{
		$this->schema->drop('guessWhoGames');
	}

}
