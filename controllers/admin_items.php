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

class Admin_Items extends Admin_Controller
{


	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('portfolio_items_m');
		$this->load->model('portfolio_items_images_m');
		$this->load->model('portfolio_categories_m');
		$this->load->library('form_validation');
		$this->load->library('asset');
		$this->load->library('session');
		$this->load->helper('url');
		$this->lang->load('portfolio');
		Asset::add_path ('theme_path', BASE_URL.'addons/shared_addons/themes/cbd/' );
		Asset::add_path('module_path', BASE_URL.$this->module_details['path'].'/');


		// Set the validation rules
		$this->item_validation_rules = array(

				array(
					'field' => 'title',
					'label' => 'title',
					'rules' => '',
				),
				array(
					'field' => 'completed_date',
					'label' => 'completed_date',
					'rules' => '',
				),
				array(
					'field' => 'status',
					'label' => 'status',
					'rules' => '',
				),
				array(
					'field' => 'category',
					'label' => 'category',
					'rules' => '',
				),

		);

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
						->append_css('module::admin.css');
	}
	
	 /**
	 ** List all Portfolio items
	 **/
	public function index()
	{
		//Get all the items from the database
		$items = $this->portfolio_items_m->get_all();
		$this->template->title($this->module_details['name'])
						//Set the breadcumbs
					   ->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module)
					   //Enable Lex Parser
					   ->enable_parser(true)
					   ->set('items', $items->result())
					   //Build the view using portfolio/views/admin/items.php
					   ->build('admin/items');
	}
	

	 /**
	 ** Portfolio Images page. Shown when user clicks on a Portfolio item.
	 **/
	public function images($id=0)
	{
		//Get the item
		$item = $this->portfolio_items_m->get($id);
		//Get all images of the item
		$item_images = $this->portfolio_items_images_m->get_by_key('parent', $id);
		//Set a session variable for easy redirection to the portfolio item being viewed.
		$this->session->set_flashdata('redirectToCurrent', current_url());
		$this->template
		////->set_breadcrumb('Portfolio', '/admin/portfolio')
					   ->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module.'/items')
					   ->set_breadcrumb($item->title, '/admin/'.$this->module.'/items/images/'.$item->id)
					   ->set_breadcrumb(lang('items:images'))
					   ->set('item_title', $item->title)
					   ->set('item_images', $item_images)
					   //'parent' is used as a hidden form input, and submitted to do_upload() when the form posts.
					   ->set('parent', $id)
					   ->set('id', $id)
					   //Enable Lex parser
					   ->enable_parser(true)
					    //Build the view using portfolio/views/admin/images.php
					   ->build('admin/images');
	}
	
	/**
	 ** Add Portfolio Item Images page. Not for form POSTing.
	 **/
	public function add_item_images($parent=0)
	{
		//Get the parent item. Here parent is just the id for the current portfolio item being viewed.
		$item = $this->portfolio_items_m->get($parent);
		$items->data = new StdClass();
		$this->_form_data();
		// Build the view using portfolio/views/admin/form_upload.php
		$this->template
						//Delete the stock version of Jquery and load Jquery 1.10.2
						//Probably not very efficient, but works. 
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
					   

					   //Set the breadcrumbs
					   ////->set_breadcrumb('Portfolio', '/admin/portfolio')
					   ->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module.'/items')
					   ->set_breadcrumb($item->title, '/admin/'.$this->module.'/items/images/'.$item->id)
					   ->set_breadcrumb(lang('portfolio:add_images'))
					   ->title($this->module_details['name'], lang('portfolio.new_item'))
					   ->set('parent', $parent)
					   //Enable LEX Parser
					   ->enable_parser(true)
					   ///Build the view using portfolio/views/admin/form_upload.php
					   ->build('admin/form_upload', $items->data);
		
	}

	/**
	 * The actual upload function to which Fileupload.js POSTs its data
	 * 
	 * Here we will create 3 versions of the file
	 * 1. The regular large version to display on the front end item page
	 * 2. A thumb version (100x100px) to display in the admin area
	 * 3. A 200x250px version to display on the front end portfolio page
	 **/
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
				'width' => 100,
				'height' => 100);
			$this->load->library('image_lib', $thumb_config);
			$this->image_lib->initialize($thumb_config);
			$this->image_lib->resize();
			$this->image_lib->clear();
			
			//Set up some variables for cropping image.
			$crop_width = 200;
			$crop_height = 250;
			$ratio = $data['image_width'] / $data['image_height'];
			$crop_ratio = $crop_width / $crop_height;
			if ($ratio >= $crop_ratio and $crop_height > 0) {
					$width	= $ratio * $crop_height;
					$height	= $crop_height;
				} else {
					$width	= $crop_width;
					$height	= $crop_width / $ratio;
				}
			$width	= ceil($width);
			$height	= ceil($height);

			
			// Re-size for front-end 200x250px using the above variables.
			$medium_config = array(
				'image_library' => 'gd2',
				'source_image' => FCPATH.'uploads/default/portfolio/'.$data['file_name'],
				'new_image' => FCPATH.'uploads/default/portfolio/200x250/'.$data['file_name'],
				'maintain_ratio' => TRUE,
				//'master_dim' => ((intval($data["image_width"]) / intval($data["image_height"])) - ($medium_config['width'] / $medium_config['height']) > 0)? "height" : "width",
				'width' => $width,
				'height' => $height);
				

			$this->load->library('image_lib', $medium_config);
			$this->image_lib->initialize($medium_config);
			$this->image_lib->resize();
			$this->image_lib->clear();
			

			//After the above image has been saved, reload and crop the image to fit. The new image will overwrite the old one.

			$x_axis = floor(($width - $crop_width) / 2);
			$y_axis = floor(($height - $crop_height) / 2);
			
			$medium_crop_config = array(
				'image_library' => 'gd2',
				'source_image' => FCPATH.'uploads/default/portfolio/200x250/'.$data['file_name'],
				'new_image' => FCPATH.'uploads/default/portfolio/200x250/'.$data['file_name'],
				'quality' => '100%',
				'maintain_ratio' => FALSE,
				'width' => $crop_width,
				'height' => $crop_height,
				'x_axis' => $x_axis,
				'y_axis' => $y_axis
			);
			$this->image_lib->initialize($medium_crop_config);
			$this->image_lib->crop();
			$this->image_lib->clear();

			//If the request is from "Items" page, we must be viewing a portfolio item. Save the data to portfolio_items_m and redirect to admin/press.
			if ($this->input->post('is_parent')){
				$this->portfolio_items_m->create($data);
				redirect('admin/press');
			}
			else 
			{
			//Else if the request is from "Images" page, then we are viewing the images of a portfolio item. Save the data to portfolio_items_images_m.
				$saved_data = $this->portfolio_items_images_m->create($data);
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
	
	/**
	 * Create a new Portfolio Item
	 **/
	public function create($param = 0)
	{
		$items = new StdClass();
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->portfolio_items_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('portfolio.success'));
				// If user clicks on Create and Add Images then recirect to add_item_images using the ID of the last created DB record
				if ($param == 'add_images'){
					redirect('admin/'.$this->module.'/add_item_images/'.$this->db->insert_id());
				}
				else {
					redirect('admin/'.$this->module.'/items');
				}
				
				
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('portfolio.error'));
				redirect('admin/'.$this->module.'/items/create');
			}
		}
		$items->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$items->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		//Get all the categories
		$query = $this->portfolio_categories_m->all();
		//Create an empty array to hold categories
		$category_list = array();
		//Add items from $query to $category_list. 
		//Array format is $category_list = array ('1' => 'Construction', '2' => 'Residential', ...) for populating the dropdown menu
		foreach ($query as $key):
				$category_list[strval($key->id)] = $key->title;
		endforeach;


		$status_list = array(
		                  'draft'   => 'draft',
		                  'live'   => 'live',
		                );

		// Build the view using portfolio/views/admin/form_items.php
		$this->template->title($this->module_details['name'], lang('portfolio.new_item'))
						->set('category_list', $category_list)
						->set('status_list', $status_list)
						->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module.'/items')
						->set_breadcrumb(lang('portfolio:create'))
						->enable_parser(true)
						->build('admin/form_items', $items->data);

	}

	/**
	 * Edit a Portfolio Item
	 **/
	public function edit($id = 0)
	{
		$item = $this->portfolio_items_m->get($id);
		//$category = $this->db->from('portfolio_categories')->get();
		$category = $this->portfolio_categories_m->all();

		////$result->category = $options;

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);
		
		//Get all the categories
		$categories = $this->portfolio_categories_m->all();
		//Create an empty array to hold categories
		$item->category_list = array();
		//Add items from $query to $category_list. 
		//Array format is $category_list = array ('1' => 'Construction', '2' => 'Residential', ...) for populating the dropdown menu
		foreach ($categories as $category):
				$item->category_list[strval($category->id)] = $category->title;
		endforeach;


		$result->status_list = array(
		                  'draft'   => 'draft',
		                  'live'   => 'live',
		                );

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->portfolio_items_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('portfolio.success'));
				redirect('admin/'.$this->module.'/items');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('portfolio.error'));
				redirect('admin/'.$this->module.'/items/create');
			}
		}
		$this->_form_data();
		// Build the view using portfolio/views/admin/form_items.php
		$this->template->title($this->module_details['name'], lang('portfolio.edit'))
						->enable_parser(TRUE)
						//Set the breadcrumbs
					   ////->set_breadcrumb('Portfolio', '/admin/portfolio')
					   ->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module.'/items')
					   ->set_breadcrumb($item->title, '/admin/'.$this->module.'/items/images/'.$item->id)
					   ->set_breadcrumb(lang('portfolio:edit'))
						->build('admin/form_items', $item);
	}
	
	/**
	 * Edit a Portfolio Item Image
	 **/
	public function edit_image($id = 0)
	{
		//Get the image record
		$image = $this->portfolio_items_images_m->get($id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->portfolio_items_images_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('portfolio.success'));
				redirect('admin/'.$this->module.'/items/images/'.$image->parent);
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('portfolio.error'));
				redirect('admin/'.$this->module.'/items/images/edit/'.$id);
			}
		}
		$this->_form_data();
		// Build the view using portfolio/views/admin/form_items_images.php
		$this->template->title($this->module_details['name'], lang('portfolio.edit'))
						->enable_parser(TRUE)
						//Set the breadcrumbs
					   ->set_breadcrumb(lang('portfolio:items'), '/admin/'.$this->module.'/items')
					   ->set_breadcrumb($image->title, '/admin/'.$this->module.'/items/images/'.$image->id)
					   ->set_breadcrumb($image->title, '/admin/'.$this->module.'/items/images/'.$image->id)
					   ->set_breadcrumb(lang('portfolio:edit'))
						->build('admin/form_items_images', $image);
	}


	public function _form_data()
	{
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}
	
	
	 /**
	 * Delete Portfolio Item(s)
	 **/
	public function delete_items($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->portfolio_items_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->portfolio_items_m->delete($id);
		}
		redirect('admin/'.$this->module.'/items');
	}
	
	/**
	 * Delete Portfolio Item Image(s)
	 **/
	public function delete_item_images($id = 0)
	{
		$image = $this->portfolio_items_images_m->get($id);
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->portfolio_items_images_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->portfolio_items_images_m->delete($id);
		}
		redirect('admin/'.$this->module.'/items/images/'.$image->parent);
	}
	
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->portfolio_items_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
