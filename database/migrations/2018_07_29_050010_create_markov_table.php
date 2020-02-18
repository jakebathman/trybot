<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarkovTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('markov', function(Blueprint $table)
		{
			$table->increments('Id');
			$table->timestamp('Timestamp')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->char('Type', 50)->nullable();
			$table->text('Text', 65535)->nullable();
			$table->string('UserId', 50)->nullable();
			$table->string('GroupId', 50)->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('markov');
	}

}
