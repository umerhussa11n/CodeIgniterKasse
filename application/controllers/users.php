<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('users_overview');
    }
	
	public function index()
	{
		
		
		$data['title'] = 'Brugere';
		
		$this->load->model('user_model');
		
		if($this->input->post('create_user')){
			$this->user_model->create_user();
		}
		
		$data['users'] = $this->user_model->get_users();
		
		$data['rank_list'] = $this->user_model->get_ranks();
		
		$data['yield'] = "users/index";
		$this->load->view('layout/application',$data);
	}
	
	
		
	public function edit()
	{
		
		$id = $this->input->post('id');
		
		$data['title'] = 'Brugere';
		
		$this->load->model('user_model');
		
		if($this->input->post('edit_user')){
			$this->user_model->update();
		}
		
		$data['user'] = $this->user_model->get_user_by_id($id);
		
		$data['rank_list'] = $this->user_model->get_ranks();
		
		$this->load->view('users/_edit',$data);
	}
	
	
	
	public function permissions()
	{
		
		$data['title'] = 'Rettigheder';
		
		$this->load->model('user_model');
		
		if($this->input->post('create_permission')){
			$this->user_model->create_permission();
		}
				
		$data['rank_list'] = $this->user_model->get_ranks();
		
		$data['yield'] = "users/permissions";
		$this->load->view('layout/application',$data);
	}
	
	public function editPermission()
	{
		
		$id = $this->input->post('id');
		
		$data['title'] = 'Rettigheder';
		
		$this->load->model('user_model');
		
		if($this->input->post('edit_permission')){
			$this->user_model->update_permission();
		}
		
		$data['rank_list'] = $this->user_model->get_rank_by_id($id);
				
		$this->load->view('users/_edit_permissions',$data);
	}
	
	function cancel($id){
		
		$string = array(
			'active' => 0
		);
		$this->db->where('id',$id);
		$this->db->update('users_kasse',$string);
		
		redirect('users');
		
	}
	
	
	function cancel_permission($id){
		
		$string = array(
			'active' => 0
		);
		$this->db->where('id',$id);
		$this->db->update('ranks',$string);
		
		redirect('users/permissions');
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */