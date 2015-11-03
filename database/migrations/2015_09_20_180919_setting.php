<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Setting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Create('setting',function($table){
            $table->engine = 'MyISAM';
            $table->increments('id');
             $table->string('title');
             $table->string('keywords');
             $table->string('description');
             $table->string('domain');
             $table->string('site_name');
             $table->string('admin');
             $table->string('lang');
             $table->string('dir');

             
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
