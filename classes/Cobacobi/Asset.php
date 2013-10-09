<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Asset helper
 * 
 * 
 * @copyright cobacobi Laboratory
 * @author Kusmayadi (kusmayadi@cobacobi.com)
 * @version 1.1
 **/

abstract class Cobacobi_Asset {

	private $css_static;
	private $css_dynamic;
	
	private $js_static;
	private $js_dynamic;

	public function add_css($filename, $params = array())
	{
		$this->add_asset('css', $filename, $params);
	}
	
	public function reset_css()
	{
		$this->reset_asset('css');
	}
	
	public function add_js($filename, $params = array())
	{
		$this->add_asset('js', $filename, $params);
	}
	
	public function reset_js()
	{
		$this->reset_asset('js');
	}
	
	public function render($type = 'all')
	{
	
		$content = '';
	
		if ($type == 'all' OR $type == 'css')
		{
			if (count($this->css_static))
			{
				$content .= HTML::style('asset?f='.implode(',', $this->css_static));
				$content .= "\n\n";
			}
			
			if (count($this->css_dynamic))
			{
				$content .= '<style type="text/css" media="all">'."\n";
				$content .= implode("\n\n", $this->css_dynamic);
				$content .= "\n".'</style>'."\n\n";
			}
		}
		
		if ($type == 'all' OR $type == 'js')
		{
			if (count($this->js_static))
			{
				$content .= HTML::script('asset?f='.implode(',', $this->js_static));
				$content .= "\n\n";
			}
			
			if (count($this->js_dynamic))
			{
				$content .= '<script type="text/javascript">'."\n";
				$content .= implode("\n\n", $this->js_dynamic);
				$content .= "\n".'</script>'."\n\n";
			}
		}
		
		return $content;
	
	}
	
	private function add_asset($type, $filename, $params)
	{

		if (count($params))
		{
			// Dynamic assets
			
			switch($type)
			{
				case 'css':
					$asset_path = Common::get_config('asset.asset_folder').'/'.Common::get_config('asset.css_folder');
				break;
				
				case 'js':
					$asset_path = Common::get_config('asset.asset_folder').'/'.Common::get_config('asset.js_folder');
				break;
			}
			
			if (Kohana::find_file('views', $asset_path.'/'.$filename))
			{
				$view = View::factory($asset_path.'/'.$filename);

				foreach($params as $key => $value)
				{
					$view->$key = $value;
				}
				
				if ($type == 'css')
				{
					$this->css_dynamic[] = $view->render();
				}
				else if ($type == 'js')
				{
					$this->js_dynamic[] = $view->render();
				}
			}
			
			
		}
		else
		{
			// Static assets
			if ($type == 'css')
			{
				$this->css_static[] = Common::get_config('asset.css_folder').'/'.$filename;
			}
			else if ($type == 'js')
			{
				$this->js_static[] = Common::get_config('asset.js_folder').'/'.$filename;
			}
			
		}
	
	}
	
	private function reset_asset($type)
	{
		switch ($type)
		{
			case 'css':
				$this->css_static  = array();
				$this->css_dynamic = array();
			break;
			
			case 'js':
				$this->js_static  = array();
				$this->js_dynamic = array();
			break;
		}
	}
	
}
