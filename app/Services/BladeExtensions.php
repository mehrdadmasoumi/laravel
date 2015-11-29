<?php namespace App\Services;

Class BladeExtensions {

	public static function register() 
	{
		/* @eval($var++) */
		\Blade::extend(function($view)
		{
		    return preg_replace('/\@eval\s*\((.+)\)/', '<?php eval($1); ?>', $view);
		});

		//## @var(param1 [,param2])
		\Blade::extend(function($value) {
		    return preg_replace('/(\s*)@var\(([^,]+),(.*)\)/', '$1<?php ${$2} = $3; ?>', $value);
		});

		//## break|continue;
		\Blade::extend(function($value) {
		    return preg_replace('/(\s*)@(break|continue)(\s*)/', '$1<?php $2; ?>$3', $value);
		});
	}
}