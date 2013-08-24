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
 
class portfolio_categories_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'portfolio_categories';
		$this->load->library('easy_portfolio/slug');
	}
	public function all()
	{
		$this->db->select('*')->from('portfolio_categories');
		return $this->db->get()->result();
	}
		public function all_with_item_count()
	{
		$query = 'SELECT cat.*, (SELECT COUNT(*) FROM default_portfolio_items WHERE default_portfolio_items.category = cat.id) AS num_items
				FROM default_portfolio_categories AS cat';
		return $this->db->query($query)->result();
	}

	//create a new item
	public function create($input)
	{
		//$fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$slug = @Slug::create_unique_slug($input['title'], $this->_table);
		$to_insert = array(
			//'fileinput' => json_encode($fileinput),
			'slug' => $slug,
			'title' => $input['title'],

		);

		return $this->db->insert('portfolio_categories', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		//$fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$title = $this->input->post('title');
		$slug = @Slug::create_unique_slug($title, $this->_table);
		$to_insert = array(
				'image' => $input['file_name'],
				'title' => $title,
				'slug' => $slug
		);

		var_dump($_FILES['userfile']['name'] );
		//return $this->db->where('id', $id)->update('portfolio_categories', $to_insert);
	}
}
