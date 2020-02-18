<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlackEmojiTable extends Migration
{
    protected $schema;

    public function __construct()
    {
        $this->schema = Schema::connection(config('database.slack'));
    }

    public function up()
    {
        $this->schema->create('emoji', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('url');
            $table->string('aliasFor')->nullable()->default(null);
            $table->boolean('isActive');

            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('emoji');
    }
}
