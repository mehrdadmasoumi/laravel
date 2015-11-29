<?php

Route::group(['namespace' => 'Content\Admin','prefix' => 'Content'], function()
{
    Route::get('/', ['as' => 'admin_content','uses' => 'ContentController@index']);
    Route::get('/Create', ['as' => 'admin_content_create','uses' => 'ContentController@create']);
    Route::post('/Store', ['as' => 'admin_content_store','uses' => 'ContentController@store']);

});
