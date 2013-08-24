<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 * @author 		Farhan Khan	
 * @package 	PyroCMS
 * @subpackage 	Easy Portfolio
 * @category	Module
 * @copyright 	MIT
 * 
 */
 
class portfolio_items_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'portfolio_items';
		$this->load->library('easy_portfolio/slug');
		$this->load->library('files/files');
		//$this->folder = $this->file_folders_m->get_by('name', 'Portfolio');
	}
	
	public function get_all()
	{
		$query = 'SELECT i.id, i.title, i.slug, i.status, i.order, i.completed_date, c.title as item_category
				FROM default_portfolio_items AS i
				LEFT JOIN default_portfolio_categories AS c ON c.id = i.category';
		
		return $this->db->query($query);
	}
	
	public function get_category_items($key, $value)
    {
    	$query = 'SELECT i.id AS item_id, i.title AS item_title, i.slug AS item_slug, i.status as item_status, i.completed_date AS item_completed_date, c.title AS item_category, c.slug AS category_slug,
				(SELECT images.image FROM default_portfolio_items_images AS images WHERE images.parent = i.id LIMIT 1) AS item_image
				FROM default_portfolio_items AS i
				JOIN default_portfolio_categories AS c ON c.id = i.category
				WHERE c.id = %s';
		
		return $this->db->query(sprintf($query, $value))->result();
    }

	public function get_item_images($slug)
    {
    	$item = $this->get_by('slug', $slug);
    	$query = 'SELECT *
    			FROM default_portfolio_items_images AS images
    			WHERE images.parent = %s';
		
		return $this->db->query(sprintf($query, $item->id));
    }
	

	//create a new item
	public function create($input)
	{
		//Create a folder with the same name as project using the Files module
		//$parent_id=4 is the id of the top-level Portfolio folder
		//$folder = Files::create_folder($parent_id=4, $input['title']);
		//$fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$id = $this->input->post('id');
		$slug = Slug::create_unique_slug($input['title'], $this->_table);
		//If user doesn't enter a date, use today's date
		$completed_date = ($input['completed_date'] == '' ? date('Y-m-d') : $input['completed_date']);
		//print_r($slug);
		$to_insert = array(
			//'fileinput' => json_encode($fileinput),
			//'Test' => $input['Test'],
			'title' => $input['title'],
			'description' => $input['description'],
			'slug' => $slug,
			'completed_date' => $completed_date,
			//'folder' => $folder['data']['id']
			'category' => $input['category'],
			'status' => $input['status']

		);
		//print_r($input);
		return $this->db->insert('portfolio_items', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		$item = $this->get($id);
		$to_insert = array(
			'description' => $input['description'],
			'completed_date' => $input['completed_date'],
			'status' => $input['status'],
			'category' => $input['category'],
		
		);
		//If Title is changed, update the title and create a new Slug based off the new title.
		if ($item->title != $input['title'])
		{
			$slug = Slug::create_unique_slug($input['title'], $this->_table);
			$to_insert['title'] = $input['title'];
			$to_insert['slug'] = $slug;
		}
		return $this->db->where('id', $id)->update('portfolio_items', $to_insert);
	}
}
