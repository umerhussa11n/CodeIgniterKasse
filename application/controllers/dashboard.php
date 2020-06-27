<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }
    
	public function index()
	{
		
		$data['title'] = 'Vælg handling';
		
		$this->global_model->check_if_logged_in();
			
		$data['yield'] = "dashboard/index";
		$this->load->view('layout/application',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */