<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuessWhoMessagesTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('guessWhoMessages', function(Blueprint $table)
		{
			$table->increments('Id');
			$table->timestamp('Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('MessageId', 100);
			$table->text('Text', 65535);
			$table->integer('GameId')->unsigned();
			$table->integer('Used')->unsigned()->default(0);
		});
	}


	public function down()
	{
		$this->schema->drop('guessWhoMessages');
	}

}
