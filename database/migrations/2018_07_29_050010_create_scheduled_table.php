<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateScheduledTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('scheduled', function(Blueprint $table)
		{
			$table->string('varTimeToSend', 254);
			$table->string('varTimeToSendPretty', 254)->comment('DATE_ISO8601 Format');
			$table->text('varText', 65535);
			$table->string('varSent', 12);
			$table->string('varSentAt', 254);
			$table->string('varSentAtDiff', 254);
			$table->string('varMessageName', 254);
			$table->string('varGroups', 254)->default('all');
			$table->primary(['varTimeToSend','varGroups']);
		});
	}


	public function down()
	{
		$this->schema->drop('scheduled');
	}

}
