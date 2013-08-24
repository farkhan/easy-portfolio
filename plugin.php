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

class Plugin_portfolio extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ portfolio:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /portfolio:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('portfolio/portfolio_items_m');
		return $this->portfolio_items_m->get_all();
	}
}

/* End of file plugin.php */