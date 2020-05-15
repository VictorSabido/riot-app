<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummonersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoners', function (Blueprint $table) {
            $table->id();
            $table->string('summId')->nullable();
            $table->string('accountId')->nullable();
            $table->string('puuid')->nullable();
            $table->string('name')->nullable();
            $table->integer('profileIconId')->nullable();
            $table->string('revisionDate')->nullable();
            $table->string('summonerLevel')->nullable();
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
        Schema::dropIfExists('summoners');
    }
}