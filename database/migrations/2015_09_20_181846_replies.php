<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Replies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Create('replies',function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->increments('id');
            $table->integer('bundle_id');
            $table->text('reply');
            $table->integer('q_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
