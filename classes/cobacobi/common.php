<?php defined('SYSPATH') or die('No direct script access.');

class Cobacobi_Common {

	public static function get_config($configuration)
	{
		$kohana_version = (float) Kohana::VERSION;
		
		if ($kohana_version < 3.2)
		{
			return Kohana::config($configuration);
		}
		else
		{
		
			return Kohana::$config->load($configuration);
		}
	}

}