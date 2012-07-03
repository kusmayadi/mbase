<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana modules for handling template
 * 
 * 
 * @copyright cobacobi Laboratory
 * @author Kusmayadi (kusmayadi@cobacobi.com)
 * @version 1.0
 **/

class Cobacobi_Controller_Template extends Kohana_Controller_Template {

	public $template = 'template/default';
	protected $session;
	protected $messages = array();
	
	public function before()
	{
		
		parent::before();
		
		// Default title
		$this->template->title = Common::get_config('site.name');
		
		// load asset module
		$this->asset = new Asset;
		
		$this->session = Session::instance();
		
		$this->messages = $this->session->get_once('messages');
		
	}
	
	/**
 	 * Add js file to template. 
 	 * @uses	cl_asset library
 	 * @param	string	js file stored inside views/media/javascript
 	 * @param	array	key = value formatted for injecting php vars to js file
 	 */
 	protected function add_js($jsfile, $params = array())
 	{
 		$this->asset->add_js($jsfile, $params);
 	}
	
	protected function add_css($cssfile, $params = array())
	{
		$this->asset->add_css($cssfile, $params);
	}
	
	protected function add_message($message)
	{
		$messages = $this->session->get('messages');
		
		$messages[] = $message;
		
		$this->session->set('messages', $messages);
	}
	
	/* 
 	 * Generate form globally
 	 * @param	string	view file
 	 * @param	array	input submitted
 	 * @param	array	additional variables
 	 * @return	string	form view
 	 */
	protected function display_form($view, $post='', $vars='')
	{
		$form = View::factory($view);
 	
 		if($post)
 		{
 			foreach($post as $input_name => $value)
 			{
 				$form->$input_name = $value;
 			}
 		}
 		
 		if($vars)
 		{
 			foreach($vars as $key => $value)
 			{
 				$form->$key = $value;
 			}
 		}
 		
 		return $form;
	}
	
	public function after()
	{

		$this->template->css		= $this->asset->render('css');
		$this->template->js			= $this->asset->render('js');
		$this->template->messages	= $this->messages;
		
		parent::after();
	}

}