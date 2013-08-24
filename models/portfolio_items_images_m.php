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
 
class portfolio_items_images_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'portfolio_items_images';
		//$this->load->model('files/file_folders_m');
		//$this->load->library('files/files');
		//$this->folder = $this->file_folders_m->get_by('name', 'Press');
	}
	
		//create a new item
		
	public function get_by_key($key, $value)
	{
		$this->db->where($key, $value);
		return $this->db->get($this->_table)->result();
	}
	

	
	public function create($data)
	{
		//$fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'image' => $data['file_name'],
			'parent' => $this->input->post('parent'),
			'title' => $this->input->post('title')
			
		);
		
		return $this->db->insert('portfolio_items_images', $to_insert);
	}

		//edit a new item
	public function edit($id = 0, $input)
	{
		
		//$fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');

		$to_insert = array(
			'title' => $input['title'],
			'description' => $input['description'],
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('portfolio_items_images', $to_insert);
	}
	
	
}