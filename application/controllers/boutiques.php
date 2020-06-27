<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boutiques extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }
    
	public function index()
	{
		
		$this->global_model->check_permission('boutique_overview');
		
		$data['title'] = 'Butikker';
		
		$this->load->model('boutique_model');
		
		if($this->input->post('create_boutique')){
			$this->boutique_model->create();
		}
		
		$data['boutiques'] = $this->boutique_model->get();
		
		$data['yield'] = "boutiques/index";
		$this->load->view('layout/application',$data);
	}
	
	
	public function edit()
	{
		
		$id = $this->input->post('id');
		
		$this->global_model->check_permission('boutique_overview');
		
		$data['title'] = 'Butikker';
		
		$this->load->model('boutique_model');
		
		if($this->input->post('edit_boutique')){
			$this->boutique_model->edit($id);
		}
		
		$data['boutiques'] = $this->boutique_model->get();
		
		$data['boutique'] = $this->boutique_model->get_by_id($id);
		
		$this->load->view('boutiques/_edit',$data);
	}
	
	function change($id = false){
		
		$this->db->like('boutiques',$id);
		$users = $this->db->get('users_kasse')->result();
		
		if($users){
			$newdata = array(
               'active_boutique' => $id
           );

		   $this->session->set_userdata($newdata);
		   
		   redirect('');
		}else{
			echo 'Ingen adgang til butik';
			exit;
		}
		
	}
	
	
	function cancel($id){
		$string = array(
			'active' => 0
		);
		$this->db->where('id',$id);
		$this->db->update('boutiques',$string);
		
		redirect('boutiques');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */