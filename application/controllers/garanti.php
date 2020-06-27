<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Garanti extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }

	public function index()
	{


		$data['title'] = 'Garanti';

		$this->load->model('settings_model');

		if($this->input->post('create_garanti')){
			$this->settings_model->create_garanti();
		}

		$data['garanti'] = $this->settings_model->get_garanti();

		$data['yield'] = "garanti/index";
		$this->load->view('layout/application',$data);
	}


	public function edit()
	{

		$id = $this->input->post('id');


		$data['title'] = 'Garanti';

		$this->load->model('settings_model');

		if($this->input->post('edit_garanti')){
			$this->settings_model->edit_garanti($id);
			redirect('garanti');
		}


		$data['garanti'] = $this->settings_model->get_garanti_by_id($id);
		if(!$data['garanti']) return false;
		$this->load->view('garanti/_edit',$data);
	}


	function cancel($id){

		$this->db->where('id',$id)->delete('garanti');

		redirect('garanti');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
