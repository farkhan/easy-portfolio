<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author 		Farhan Khan	
 * @package 	PyroCMS
 * @subpackage 	Easy Portfolio
 * @category	Module
 * @license 	MIT
 * 
 */
 
class Admin_Categories extends Admin_Controller
{
	protected $section = 'items';

	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('portfolio_categories_m');
		$this->load->library('form_validation');
		$this->lang->load('portfolio');
		$this->load->library('asset');
		Asset::add_path('module_path', BASE_URL.$this->module_details['path'].'/');



		// Set the validation rules
		$this->item_validation_rules = array(

				array(
					'field' => 'title',
					'label' => 'title',
					'rules' => '',
				),

		);

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
						->append_css('module::admin.css');
	}
	
	 /**
	 ** List all categories
	 */
	public function index()
	{
		$categories = $this->portfolio_categories_m->get_all();
		$this->template
		->title($this->module_details['name'])
		->set('categories', $categories)
		->build('admin/categories');
	}
	
	/*
	 * 
	 * 
	 */
	public function create()
	{
		$categories = new StdClass();
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->portfolio_categories_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('portfolio.success'));
				redirect('admin/'.$this->module.'/categories');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('portfolio.error'));
				redirect('admin/'.$this->module.'/categories/create');
			}
		}
		$categories->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$categories->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using portfolio/views/admin/form_categories.php
		$this->template->title($this->module_details['name'], lang('portfolio.new_item'))
						->build('admin/form_categories', $categories->data);
	}

	public function edit($id = 0)
	{
		$this->load->library('upload');
		$this->data = $this->portfolio_categories_m->get($id);


		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->portfolio_categories_m->edit($id, $this->upload->data()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('portfolio.success'));
				//redirect('admin/portfolio/categories');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('portfolio.error'));
				//redirect('admin/portfolio/categories/create');
			}
		}
		// starting point for file uploads
		$this->_form_data();
		// Build the view using portfolio/views/admin/form_categories.php
		$this->template
						->append_js('module_path::delete-jquery.js')
						//Load jQuery and jQuery UI
						->append_js('module_path::jquery.js')
						->append_js('module_path::jquery-ui.min.js')
						//Load jQuery File Upload plugin by blueImp
					   	->append_js('module_path::jquery.fileupload.js')
					   	->append_js('module_path::jquery.fileupload-ui.js')
					   	//->append_js('theme_path::jquery.isotope.min.js')
					   	->append_js('module_path::jquery.tmpl.min.js')
					   	->append_js('module_path::load-image.min.js')
					   	->append_js('module_path::jquery.iframe-transport.js')
					   	->append_css('module_path::jquery.fileupload-ui.css')
					   	->append_css('module_path::admin.css')
					   	->append_js('module_path::jquery.fileupload-process.js')
					   	->append_js('module_path::jquery.fileupload-image.js')
					   	->append_js('module_path::jquery.fileupload-audio.js')
					   	->append_js('module_path::jquery.fileupload-video.js')
					   	->append_js('module_path::jquery.fileupload-validate.js')
		
		
		->title($this->module_details['name'], lang('portfolio.edit'))
						->build('admin/form_categories', $this->data);
	}

	public function do_upload()
	{
		//Set the upload path
		$upload_path_url = base_url().'uploads/default/portfolio/';
		//Set the preferences for the upload
		$config['upload_path'] = FCPATH.'uploads/default/portfolio/';
		$config['allowed_types'] = 'jpg';
		$config['max_size'] = '30000';
		//Encrypts the filename to a random string.
		$config['encrypt_name'] = TRUE;
		
	  	$this->load->library('upload', $config);
		
		//Initiate the upload. Show any errors if upload failed.
	  	if ( ! $this->upload->do_upload('image')) {
	  		$error = array('error' => $this->upload->display_errors());
	  		$this->load->view('error', $error);
		
		} else {
			//No errors. Upload proceeds as normal
			$data = $this->upload->data();
			
	         // Re-size for admin thumbnail images	
			$thumb_config = array(
				'source_image' => FCPATH.'uploads/default/portfolio/'.$data['file_name'],
				'new_image' => FCPATH.'uploads/default/portfolio/thumb/'.$data['file_name'],
				'maintain_ratio' => TRUE,
				'width' => 200,
				'height' => 200);
			$this->load->library('image_lib', $thumb_config);
			$this->image_lib->initialize($thumb_config);
			$this->image_lib->resize();
			$this->image_lib->clear();


			//If the request is from "Items" page, we must be viewing a portfolio item. Save the data to portfolio_items_m.
			if ($this->input->post('is_parent')){
				$this->portfolio_categories_m->create($data);
				redirect('admin/press');
			}
			else 
			{
			//Else if the request is from "Images" page, then we are viewing the images of a portfolio item. Save the data to portfolio_items_images_m.
				$saved_data = $this->portfolio_categories_m->edit($data);
				//set the data for the json array
				$info->thumb = $this->image_lib->display_errors();
				$info->name = $data['orig_name'];
				$info->title = $this->input->post('title');
		        $info->size = $data['file_size'];
				$info->type = $data['file_type'];
			    $info->url = $upload_path_url .$data['file_name'];
				$info->thumbnail_url = $upload_path_url.'thumb/'.$data['file_name'];
			    $info->delete_url = base_url().'upload/deleteImage/'.$data['file_name'];
			    $info->delete_type = 'DELETE';
				//this is why we put this in the constants to pass only json data
				if (IS_AJAX) {
					echo json_encode(array($info));
					//this has to be the only data returned or you will get an error.
					//if you don't give this a json array it will give you a Empty file upload result error
					//it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)
	
				 //so that this will still work if javascript is not enabled
				} 
				else {
			  		$file_data['upload_data'] = $this->upload->data();
				  	$this->load->view('admin/upload_success', $file_data);

				}
			}
		}
	}








	public function _form_data()
	{
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->portfolio_categories_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->portfolio_categories_m->delete($id);
		}
		redirect('admin/'.$this->module.'/categories');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->portfolio_categories_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
