<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }
	public function index()
	{
		
		$this->db->order_by('id','desc');
		$this->db->limit(500);
		$data['log'] = $this->db->get('log')->result();
	
		$data['yield'] = "log/index";
		$this->load->view('layout/application',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */