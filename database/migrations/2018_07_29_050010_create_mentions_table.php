<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMentionsTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('mentions', function(Blueprint $table)
		{
			$table->integer('primary', true);
			$table->string('varId', 254)->unique('varId');
			$table->string('varUserId', 256);
			$table->string('varUserName', 254);
			$table->string('varDateTime', 254);
			$table->text('varText', 15000);
			$table->string('varGroupId', 254);
			$table->string('mentionType', 254)->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('mentions');
	}

}
