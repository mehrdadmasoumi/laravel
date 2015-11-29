<?php

use Illuminate\Support\Facades\App AS App;
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

$locale = Request::segment(1);
if (in_array($locale, Config::get('app.locales'))) {
    App::setLocale($locale);
    Config::set('app.localization', $locale);
} else if (Request::get('lang') && in_array(Request::get('lang'), Config::get('app.locales'))) {
    App::setLocale(Request::get('lang'));
    $locale = null;
} else {
    $locale = null;
}

$dir = (App::getLocale() == 'fa') ? 'rtl' : 'ltr';
Config::set('app.dir', $dir);

Route::group(array('prefix' => $locale), function () {
    $prefixes = Config::get('app.prefixes');
    foreach ($prefixes as $prefix) {
        Route::group(array('prefix' => $prefix, 'namespace' => studly_case($prefix)), function () use ($prefix) {
            // for prefix route example ^/admin or ^/user or ^/agent
            Route::get('/', function() use ($prefix) {
                return Lib::callAction('', $prefix);
            });
            // for other route in namespace example ^/admin/content/store/{args}/?getParams
            Route::any('{controller?}/{action?}/{args?}', function ($controller, $action = 'index', $args = '') use($prefix) {
                return Lib::callAction($prefix, $controller, $action, $args);
            })->where(array(
                'controller' => '[^/]+',
                'action'     => '[^/]+',
                'args'       => '[^?$]+'
            ));
        });
    }
});
