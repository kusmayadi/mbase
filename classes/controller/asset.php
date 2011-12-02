<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana modules for adding css & javascript easily.
 * 
 * @copyright cobacobi Laboratory
 * @author Kusmayadi (kusmayadi@cobacobi.com)
 * @version 1.1
 **/

class Controller_Asset extends Controller {

	public function action_index()
	{
		$files = isset($_GET['f']) ? $_GET['f'] : '';

		if($files)
		{
		
			$files = explode(',', $files);
			$content = '';
			
			foreach($files as $file)
			{
				$ext = $this->find_ext($file);
				$file = substr($file, 0, strpos($file, '.'.$ext));
				
				if($asset_file = Kohana::find_file('views', Common::get_config('asset.asset_folder').'/'.$file, $ext))
				{
					
					$content .= Kohana::load($asset_file);
					$content .= "\n\n";
				}
			
			}
			
			$content_type = Common::get_config('mimes.'.$ext);

			header('Content-type: '.$content_type[0]);
			echo $content;
			exit;
			
		}
	}
	
	private function find_ext($filename)
	{
		
		$reverse_filename = strrev($filename);
		$dot_pos = strpos($reverse_filename, '.');
		
		if($dot_pos)
		{
		
			$ext = substr($reverse_filename, 0, $dot_pos);
			$ext = strrev($ext);
		
		}
		else
		{
			$ext = NULL;
		}
		
		return($ext);
	
	}

}