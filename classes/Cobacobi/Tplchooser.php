<?php defined('SYSPATH') or die('No direct script access.');

class Cobacobi_TplChooser {

	private $browser, $platform, $version, $config;
	public $template = 'general';
	

	public function __construct()
	{
	
		$this->browser	= Request::user_agent('browser');
		$this->mobile	= Request::user_agent('mobile');
		$this->platform	= Request::user_agent('platform');
		$this->version	= Request::user_agent('version');
		
		if( ! empty($this->mobile))
		{
			$this->platform = $this->mobile;
		}
		
		$this->config	= Common::get_config('template.'.$this->browser);
		
		$this->template = $this->get_template();

	}
	
	private function get_template()
	{
	
		$template = 'general';
	
		if (is_null($this->config))
		{
			$this->config = Common::get_config('template.*');
		}

		if (is_array($this->config))
		{
			if (isset($this->config[$this->platform]))
			{
				if (is_array($this->config[$this->platform]))
				{
					if (isset($this->config[$this->platform][$this->version]))
					{
						$template = $this->config[$this->platform][$this->version];
					}
					elseif (isset($this->config[$this->platform][(int) $this->version]))
					{
						$template = $this->config[$this->platform][(int) $this->version];
					}
					else
					{
						foreach ($this->config[$this->platform] as $ver => $tpl)
						{
						
							if (substr($ver, strlen($ver) - 1, 1) == '>')
							{
								if ((int)$this->version >= (int)substr($ver, 0, strlen($ver) - 1))
								{
									$template = $tpl;
								}
							}
							elseif (substr($ver, strlen($ver) - 1, 1) == '<')
							{
								if ((int)$this->version <= (int)substr($ver, 0, strlen($ver) - 1))
								{
									$template = $tpl;
								}
							}
							elseif ($ver == (int) $this->version)
							{
								$template = $tpl;
							}
						}
					}
				}
				else
				{
					$template = $this->config[$this->platform];
				}
				
			}
			elseif (isset($this->config['*']))
			{
				if (is_array($this->config['*']))
				{
					foreach ($this->config['*'] as $ver => $tpl)
					{
						if (substr($ver, strlen($ver) - 1, 1) == '>')
						{
							if ((int)$this->version >= (int)substr($ver, 0, strlen($ver) - 1))
							{
								$template = $tpl;
							}
						}
						elseif (substr($ver, strlen($ver) - 1, 1) == '<')
						{
							if ((int)$this->version <= (int)substr($ver, 0, strlen($ver) - 1))
							{
								$template = $tpl;
							}
						}
						elseif ($ver == (int) $this->version)
						{
							$template = $tpl;
						}
					}
				}
				else
				{
					$template = $this->config['*'];
				}
			}
		}
		else
		{
			if (! is_null($this->config))
			{
				$template = $this->config;
			}
		}
		
		if(file_exists(APPPATH.'views/'.$template))
		{
			return $template.DIRECTORY_SEPARATOR;
		}
		else
		{
			return '';
		}
		
		
	}

}