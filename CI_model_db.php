<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MediaLinks
 *
 * A MediaLinks system
 *
 * @package		MediaLinks
 * @author		MediaLinks Dev Team, Ajith Abraham
 * @copyright	Copyright (c) 2016 - 2017, MediaLinks
 * @license		
 * @link		
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Modeldb Class
 *
 * @package		MediaLinks
 * @subpackage	Model
 * @category	Model
 * @author		MediaLinks Dev Team, Ajith Abraham
 * @link		https://medialinks.in
 */

class Modeldb extends CI_Model {

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		$this->load->database();
	}

	/**
	 * Fuction Description
	 */
	public function dob_check($dob, $athmasthithiId)
	{
		
	}

}
// END Login Class

/* End of file login.php */
/* Location: ./application/models/login.php */
