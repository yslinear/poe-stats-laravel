<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GggLadder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ggg_ladder', function (Blueprint $table) {
            $table->text('character_id');
            $table->timestampTz('cached_since');
            $table->text('character_name');
            $table->text('account_name');
            $table->text('league');
            $table->integer('rank');
            $table->boolean('dead');
            $table->boolean('online');
            $table->integer('character_level');
            $table->text('character_class');
            $table->bigInteger('character_experience');
            $table->integer('character_depth_default');
            $table->integer('character_depth_solo');
            $table->integer('account_challenges_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ggg_ladder');
    }
}
