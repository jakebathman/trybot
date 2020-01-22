<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiteStatusTable extends Migration {

    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.trybot2000'));
    }

	public function up()
	{
		$this->schema->create('siteStatus', function(Blueprint $table)
		{
			$table->increments('Id');
			$table->timestamp('Timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('Domain', 100)->nullable();
			$table->enum('Status', array('down','degraded','partially up','having issues','up','unknown'))->nullable();
			$table->text('StatusInfo', 65535)->nullable();
			$table->text('StatusDetail', 65535)->nullable();
			$table->text('StatusTypes', 65535)->nullable();
			$table->dateTime('StartDate')->nullable();
			$table->dateTime('EndDate')->nullable();
		});
	}


	public function down()
	{
		$this->schema->drop('siteStatus');
	}

}
