<?php

Route::group(['namespace' => 'Admin'], function()
{
    Route::get('/', ['as' => 'admin','uses' => 'HomeController@index']);
});