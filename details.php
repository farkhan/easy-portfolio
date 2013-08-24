<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Easy Portfolio
 * 
 * @author 		Farhan Khan	
 * @package 	PyroCMS
 * @subpackage 	Easy Portfolio
 * @category	Module
 * @license 	MIT
 * 
 */


class Module_Easy_portfolio extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Easy Portfolio'
				),
			'description' => array(
				'en' => 'An easy to manage Portfolio module that integrates into your existing PyroCMS installation.'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content',
			'sections' => array(
				'projects' => array(
					'name' 	=> 'portfolio:items', // These are translated from your language file
					'uri' 	=> 'admin/easy_portfolio/items',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'portfolio:create_item',
							'uri' 	=> 'admin/easy_portfolio/create_item',
							'class' => 'add'
							)
						)
					),
				'categories' => array(
					'name' 	=> 'portfolio:categories', // These are translated from your language file
					'uri' 	=> 'admin/easy_portfolio/categories',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'portfolio:create_category',
							'uri' 	=> 'admin/easy_portfolio/create_category',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('portfolio');
		$this->dbforge->drop_table('portfolio_items');
		$this->dbforge->drop_table('portfolio_items_images');
		$this->dbforge->drop_table('portfolio_categories');
		
		$portfolio = array(
		'id' => array(
			'type' =>'INT',
			'constraint' => '11',
			'auto_increment' => TRUE)
			);

		$portfolio_items = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'auto_increment' => TRUE
				),
			'order' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => true
				),
			'slug' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'category' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'title' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'completed_date' => array(
				'type' => 'DATE',
				'constraint' => '0',
				'null' => TRUE
				),
			'status' => array(
				'type' => 'VARCHAR',
				'constraint' => '6',
				'null' => TRUE
				),
			'description' => array(
				'type' => 'TEXT',
				'null' => true
				)
			);
		$portfolio_items_images = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'auto_increment' => TRUE
				),
			'parent' => array(
				'type' => 'INT',
				'constraint' => '11',
				),
			'image' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'title' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE
				)
			);
		$portfolio_categories = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'auto_increment' => TRUE
				),
			'title' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'slug' => array(
				'type' => 'TEXT',
				'null' => TRUE
				),
			'image' => array(
				'type' => 'TEXT',
				'null' => TRUE
				)
			);

		$this->dbforge->add_field($portfolio);
		$this->dbforge->add_key('id', TRUE);
		if (!$this->dbforge->create_table('portfolio'))
		{
			return FALSE;
		}
		$this->dbforge->add_field($portfolio_items);
		$this->dbforge->add_key('id', TRUE);
		if (!$this->dbforge->create_table('portfolio_items'))
		{
			return FALSE;
		}
		$this->dbforge->add_field($portfolio_items_images);
		$this->dbforge->add_key('id', TRUE);
		if (!$this->dbforge->create_table('portfolio_items_images'))
		{
			return FALSE;
		}
		$this->dbforge->add_field($portfolio_categories);
		$this->dbforge->add_key('id', TRUE);
		if (!$this->dbforge->create_table('portfolio_categories'))
		{
			return FALSE;
		}

		
		//Create the folders for uploading images
		if ( ! is_dir($this->upload_path.'portfolio') AND ! @mkdir($this->upload_path.'portfolio',0777,TRUE))
		{
			return FALSE;
		}
		if ( ! is_dir($this->upload_path.'portfolio/200x250') AND ! @mkdir($this->upload_path.'portfolio/200x250',0777,TRUE))
		{
			return FALSE;
		}
		if ( ! is_dir($this->upload_path.'portfolio/categories') AND ! @mkdir($this->upload_path.'portfolio/categories',0777,TRUE))
		{
			return FALSE;
		}
		if ( ! is_dir($this->upload_path.'portfolio/thumb') AND ! @mkdir($this->upload_path.'portfolio/thumb',0777,TRUE))
		{
			return FALSE;
		}

		return TRUE;
		
	}

	public function uninstall()
	{
		$path = $this->upload_path.'portfolio';
		  foreach(glob($path . '/*') as $file) { 
		    if(is_dir($file)) rrmdir($file); else unlink($file); 
		  } rmdir($path);

		$this->dbforge->drop_table('portfolio');
		$this->dbforge->drop_table('portfolio_items');
		$this->dbforge->drop_table('portfolio_items_images');
		$this->dbforge->drop_table('portfolio_categories');

		{
			return TRUE;
		}
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
