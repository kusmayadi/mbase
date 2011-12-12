<?php defined('SYSPATH') or die('No direct script access.');

class Cobacobi_View extends Kohana_View {

	public function __construct($file = NULL, array $data = NULL)
	{
	
		$tpl_chooser = new TplChooser;
		
		if(Kohana::find_file('views', $tpl_chooser->template.$file))
		{
			$file = $tpl_chooser->template.$file;
		}
		elseif (Kohana::find_file('views', Common::get_config('template.*').'/'.$file))
		{
			$file = Common::get_config('template.*').'/'.$file;
		}
		
		parent::__construct($file, $data);
	}

}