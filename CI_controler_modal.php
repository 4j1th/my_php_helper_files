<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

class Model extends CI_Controller {

	/**
	 * Description
	 */
	 
	public function __construct()
	{
		parent::__construct();
		// session_start();
		$this->load->library('session');
		
		$_SESSION['userid'] = '3'; $_SESSION['sellerid'] = '1';
		

		// Is the User logged?
		if (!isset($_SESSION['userid'])) { 
			
			$this->output
		    ->set_status_header(200)
		    ->set_content_type('application/json', 'utf-8')
		    ->set_output(json_encode('Unauthorized Access'))
		    ->_display();
			exit; 
		}
	}
	
	/**
	 * Description
	 */
	
	public function index()
	{
		
		$post = $this->input->post(NULL, TRUE);

		$this->load->model('searchdb');
		$result = $this->searchdb->keyword_search($post);

		$this->output
		    ->set_status_header(200)
		    ->set_content_type('application/json', 'utf-8')
		    ->set_output(json_encode($result))
		    ->_display();
        
		exit;
	}
}
