<?php

Route::group(['namespace' => 'Config\Admin','prefix' => 'Config'], function()
{
    Route::get('/Config', ['as' => 'admin_config','uses' => 'configController@index']);
});