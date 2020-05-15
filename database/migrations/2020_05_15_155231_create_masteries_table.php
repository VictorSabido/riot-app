<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masteries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('summoner_id')->unsigned();
            $table->foreign('summoner_id')->references('id')->on('summoners');
            $table->bigInteger('champion_id')->unsigned();
            $table->foreign('champion_id')->references('key')->on('champions');
            $table->integer('championLevel');
            $table->integer('championPoints');
            $table->string('lastPlayTime');
            $table->integer('championPointsSinceLastLevel');
            $table->integer('championPointsUntilNextLevel');
            $table->boolean('chestGranted');
            $table->integer('tokensEarned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('masteries');
    }
}
