<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Bower for Codeigniter 3
 * @author Yoann VANITOU
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/maltyxx/bower
 */
if ( ! function_exists('bower_css'))
{
	function bower_css($index = NULL)
	{
		return get_instance()->bower->getCss($index);
	}
}

if ( ! function_exists('bower_js'))
{
    function bower_js($index = NULL)
	{
		return get_instance()->bower->getJs($index);
	}
}