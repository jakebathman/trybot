<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKicklogTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('kicklog', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('userId', 254);
			$table->string('name', 254);
			$table->string('groupId', 254);
			$table->string('bootDuration', 254)->comment('seconds');
		});
	}


	public function down()
	{
		$this->schema->drop('kicklog');
	}

}
