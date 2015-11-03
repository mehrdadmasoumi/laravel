<?php

Route::group(['namespace' => 'Base'], function()
{
    Route::get('/', ['as' => 'base','uses' => 'HomeController@index']);
});