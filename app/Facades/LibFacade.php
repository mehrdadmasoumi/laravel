<?php
/**
 * Created by PhpStorm.
 * User: Mehrdad
 * Date: 11/27/2015
 * Time: 9:28 PM
 */
namespace App\Facades ;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\LibFacade
 */
class LibFacade extends Facade {

    protected static function getFacadeAccessor() { return 'lib'; }

}