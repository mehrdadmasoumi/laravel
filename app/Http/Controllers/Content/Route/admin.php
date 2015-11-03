<?php

Route::group(['namespace' => 'Content\Admin','prefix' => 'Content'], function()
{
    Route::get('/', ['as' => 'admin_content','uses' => 'ContentController@index']);
});
