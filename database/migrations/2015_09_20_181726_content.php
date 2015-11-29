<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Content extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Create('content',function(Blueprint $table){
            $table->engine = 'MyISAM';
            $table->increments('content_id');
            $table->string('content_Headline');
            $table->string('content_title');
            $table->mediumText('content_keywords');
            $table->mediumText('content_description');
            $table->longText('content_text');
            $table->integer('content_hit');
            $table->boolean('content_status',array('approve'));
            $table->dateTime('content_created_at');
            $table->dateTime('content_updated_at');
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
