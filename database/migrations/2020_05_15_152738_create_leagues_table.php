<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('summoner_id')->unsigned();
            $table->foreign('summoner_id')->references('id')->on('summoners');
            $table->string('leagueId');
            $table->string('queueType');
            $table->string('tier');
            $table->string('rank');
            $table->integer('leaguePoints');
            $table->integer('wins');
            $table->integer('losses');
            $table->string('winRatio');
            $table->boolean('veteran');
            $table->boolean('inactive');
            $table->boolean('freshBlood');
            $table->boolean('hotStreak');
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
        Schema::dropIfExists('leagues');
    }
}
