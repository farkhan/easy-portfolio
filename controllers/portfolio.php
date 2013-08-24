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
 
class portfolio extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('portfolio');
      $this->load->model('portfolio_items_m');
	  $this->load->model('portfolio_categories_m');
	  Asset::add_path ( 'theme_path', BASE_URL.'addons/shared_addons/themes/cbd/' );
      $this->template
     ->append_css('module::portfolio.css')
	 ->append_css('module::960.css');
	  
    }
     /**
     * List all portfolios
     *
     *
     * @access  public
     * @return  void
     */
     public function index($category, $item)
     {
     //We are viewing portfolio->category->item
     if (isset($category) && isset($item)){
     	//Get the current item being viewed
     	$instance = $this->portfolio_items_m->get_by('slug', $item);
		//Get the current category being viewed
		$category = $this->portfolio_categories_m->get_by('slug', $category);
		//Get the related items for the item (recent 5 items from the category)
		$related_items = $this->portfolio_items_m->get_category_items('category', $category->id);
		//Get all the item images
     	$item_images = $this->portfolio_items_m->get_item_images($item)->result_array();
		$this->template->set('item_images', $item_images)
		 				->set('related_items', $related_items)
		 				->set('instance', $instance)
						//Required for FotoramaJS
						->append_metadata('<script src="http://fotorama.s3.amazonaws.com/4.3.0/fotorama.js"></script>')
						->append_metadata('<link  href="http://fotorama.s3.amazonaws.com/4.3.0/fotorama.css" rel="stylesheet">')
						// Build the view using portfolio/views/item.php
						->build('item');
     }
	 //We are viewing portfolio->category
	 elseif (isset($category) && !isset($item))
	 {
	 	//Get all the categories
	 	$categories = $this->portfolio_categories_m->all();
		//Get the current category being viewed
	 	$category = $this->portfolio_categories_m->get_by('slug', $category);
		//Get all the items related to the category being viewed
	 	$items = $this->portfolio_items_m->get_category_items('category', $category->id);
		// Build the view using portfolio/views/category.php
		$this->template->set('categories', $categories)
					   ->set('items', $items)
					   ->build('category');
	 	
	 }
	//We are viewing portfolio index page
	else {
		// bind the information to a key
	    $data['portfolio'] = (array)$this->portfolio_items_m->get_all();
		//Get all the categories
		$categories = $this->portfolio_categories_m->all_with_item_count();
	    // Build the view using portfolio/views/index.php
	    $this->template->title($this->module_details['name'])
	    			   ->set('categories', $categories)
				  	   ->build('index', $data);
	 }

    }

  }

/* End of file portfolio.php */