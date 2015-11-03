<?php
use Illuminate\Support\Facades\App as App;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// set locale
$locale = Request::segment(1);
if (in_array($locale, Config::get('app.locales'))) {
    App::setLocale($locale);
    $lang = $locale ;
} else {
    $locale = App::getLocale();
    $lang = null ;
}
// set dir pages
$dir = ($locale == 'fa') ? 'rtl' : 'ltr';
Config::set('app.dir', $dir);

// base routes
Route::group(['prefix' => $lang ], function()
{
    include_once(__DIR__.'/Controllers/Base/Route/base.php');
});

// admin routes
Route::group(['prefix' => $lang.'/Admin'], function(){

    // admin route
    include_once(__DIR__.'/Controllers/Admin/Route/admin.php');

    // config route
    include_once(__DIR__.'/Controllers/Config/Route/config.php');

    // content route
    include_once(__DIR__ . '/Controllers/Content/Route/admin.php');
});