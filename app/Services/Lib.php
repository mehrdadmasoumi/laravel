<?php namespace App\Services;

use App;
use Auth;
use File;
use Request;
use Config;
use Session;

Class Lib {

	/**
	 * call action 
	 * @param string $prefix
	 * @param string $controller
	 * @param string $controller
	 * @param string $action
	 * @param string $args
	 * @return mixed
	 */
	public function callAction($prefix = '', $controller='', $action = 'index', $args = '')
	{
	    Config::set('app.section', trim($prefix));
	    Config::set('app.controller', trim($controller));
	    Config::set('app.action', trim($action));
	    Config::set('app.random', str_random());


	    $controller = 'App\\Http\\Controllers\\' . $controller . '\\' . ($prefix ? (studly_case($prefix) . '\\')  : '') . studly_case($controller) . "Controller";

	    $params = explode("/", $args);
	    if ($params && preg_match('/\d+/', $params[0])) {
	        Config::set('app.id', $params[0]);
	    } else {
	        Config::set('app.id', 0);
	    }
	    $app = app();

	    if (!class_exists($controller)) {
	        return abort(404);
	    }
	    if (preg_match('/^\d+$/', $action)) {
	        $params = array($action);
	        Config::set('app.id', $action);
	    }

	    $action = !method_exists($controller, $action) ? 'index' : $action;
	    $controller = $app->make($controller);
	    return $controller->callAction($action, $params);
	}

	/**
	 * get current url
	 * @param string $partname
	 * @param bool $siteurl_perfix
	 * @return string
	 */
	public function getCurrentURL($partname = '', $siteurl_perfix = false) 
	{
	    $appurl = config('app.url');
	    $url = $siteurl_perfix ? $appurl . '/' : '/';
	    $localization = config('app.localization') ? config('app.localization') . '/' : '';
	    switch ($partname) {
	        case 'localization':
	            $url .= (config('app.localization') ? config('app.localization') : '');
	            break;
	        case 'section':
	            $url .= $localization . config('app.section');
	            break;
	        case 'controller':
	            $section = (strtolower(config('app.section')) == 'index' ? '' : config('app.section') . '/');
	            $url .= $localization . $section . config('app.controller');
	            break;
	        case 'action':
	            $url .= $localization . config('app.section') . '/' . config('app.controller') . '/' . config('app.action');
	            break;
	        default:
	            $url .= Request::path();
	            break;
	    }
	    return $url;
	}

	/**
	 * get path in array 
	 * @param string $path
	 * @param array $current
	 * @return array
	 */
	public function getPath($path, $current = array())
	{
	    $localization = @$current['localization'] ? $current['localization'] : '';
	    $section = @$current['section'] ? $current['section'] : '';
	    $controller = @$current['controller'] ? $current['controller'] : '';
	    $method = @$current['action'] ? $current['action'] : '';
	    $param = @$current['param'] ? $current['param'] : '';

	    $id = 0;
	    $res = '/';
	    if (is_array($path)) {
	        $localization = @$path['localization'] ? $path['localization'] . '/' : (config('app.localization') ? config('app.localization') . '/' : '');
	        $section = @$path['section'] ? $path['section'] : config('app.section');
	        $res .= $localization . $section;
	        if (@$path['controller'] and @$path['action'] and is_array(@$path['param']) or (@$path['controller'] and is_array(@$path['param']))) {
	            $res .= '/' . $path['controller'] . (@$path['action'] ? '/' . $path['action'] : '');
	            foreach ($path['param'] as $key => $param) {
	                if (preg_match('/\d+/', $param) and $key == 'id') {
	                    $res .= '/' . $param;
	                    $id = $param;
	                } else {
	                    preg_match('/\?/', $res, $match);
	                    if (!$match) {
	                        $res .= "?$key=$param";
	                        $param .= "?$key=$param";
	                    } else {
	                        $res .= "&$key=$param";
	                        $param .= "&$key=$param";
	                    }
	                }
	            }
	            $controller = $path['controller'];
	            $method = @$path['action'];
	        } elseif (@$path['controller'] and @$path['action']) {
	            $res .= '/' . $path['controller'] . '/' . $path['action'];
	            $controller = $path['controller'];
	            $method = $path['action'];
	        } else if (@$path['controller']) {
	            $res .= '/' . $path['controller'];
	            $controller = $path['controller'];
	        }
	    } else {
	        $isURL = preg_match('/\//', $path) ? true : false;
	        if ($isURL) {
	            $segments = explode('/', $path);
	            if ($segments) {
	                foreach ($segments as $segment) {
	                    if (!trim($segment)) continue;
	                    if (!$localization and in_array($segment, config('app.locales', ['en']))) {
	                        $localization = $segment;
	                    } elseif (!$section and in_array($segment, config('app.prefixes', []))) {
	                        $section = $segment;
	                    } elseif (!$controller and !preg_match('/\d+/', $segment)) {
	                        $controller = $segment;
	                    } elseif (!$method and !preg_match('/\d+/', $segment)) {
	                        $method = $segment;
	                    } else {
	                        $id = intval($segment);
	                    }
	                }
	            }
	            $localization = ($localization ? $localization : config('app.localization') ? config('app.localization') . '/' : '');
	            $section = $section ? $section : config('app.section');
	            $res .= $localization . $section . $path;
	        }
	    }
	    return array(
	        'path' => preg_replace('/\/+/', '/', $res),
	        'localization' => str_replace('/', '', $localization),
	        'section' => $section,
	        'controller' => $controller,
	        'action' => $method,
	        'id' => $id,
	        'param' => $param
	    );
	}

	//### translate with explode
	public function transExplode($msg, $seperator = ',', $perfix = 'language', $returnSeperator = '، ') {
	    $msgList = explode($seperator, $msg);
	    $res = '';
	    if ($msgList) {
	        $count = count($msgList);
	        $cnt = 0;
	        foreach ($msgList as $txt) {
	            $cnt++;
	            $res .= trans($perfix . '.' . trim($txt)) . ($cnt < $count ? "$returnSeperator" : '');
	        }
	    }
	    return $res;
	}

	//## convert object to array
	public function object2array(&$obj, $recursive = false)
	{
		if(is_array($obj) or is_object($obj)) {
			foreach($obj as $key=>$val) {
				if($recursive and (is_array($val) or is_object($val))) {
					$res[$key] = self::object2array($val, true);
				} else {
					$res[$key] = $val;
				}	
			}	
		} else {
			$res = $obj;
		}
		return $res;
	}
}