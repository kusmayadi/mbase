<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Kohana modules for handling template
 * 
 * 
 * @copyright cobacobi Laboratory
 * @author Kusmayadi (kusmayadi@cobacobi.com)
 * @version 1.0
 **/

class Cobacobi_Template extends Controller_Template {

	public $template = 'template/default';
	
	public function before()
	{
		
		$tpl_chooser = new TplChooser;
		$this->template = $tpl_chooser->template.$this->template;
		
		parent::before();
		
		// Default title
		$this->template->title = Common::get_config('site.name');
		
		// load asset module
		$this->asset = new Asset;
		
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
	
	/*
	 * Setup pagination
	 */
	protected function pagination($total_records)
	{
		$items_per_page = Common::get_config('pagination.items_per_page');
		$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
	
		$pagination = Pagination::factory($total_records, $items_per_page, $current_page);
		
		$pagination_view = new View_Pagination_Extended;
		$pagination_view->pagination = $pagination;
		$pagination_view->request = $this->request;
		
		return array('pagination' => $pagination, 'view' => $pagination_view);
	}
	
	public function after()
	{

		$this->template->css	= $this->asset->render('css');
		$this->template->js		= $this->asset->render('js');
		
		parent::after();
	}

}