<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordChannelsTable extends Migration
{
    public function up()
    {
        Schema::create('discord_channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('guild_id');
            $table->bigInteger('channel_id');
            $table->string('channel_name');
            $table->tinyInteger('is_deleted')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discord_channels');
    }
}
