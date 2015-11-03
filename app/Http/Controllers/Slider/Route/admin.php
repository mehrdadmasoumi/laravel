<?php

Route::group(['namespace' => 'Slider','prefix' => 'Slider'], function()
{
    Route::get('/', 'Admin\SliderController@index');
});