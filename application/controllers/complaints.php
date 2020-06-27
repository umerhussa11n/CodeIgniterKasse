<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complaints extends CI_Controller {

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
	public function index()
	{
	
		$this->db->order_by('id','desc');
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$data['complains'] = $this->db->get('complaints')->result();
	
		$data['yield'] = "complaints/index";
		$this->load->view('layout/application',$data);
	}
	
	function create(){
		
		if($this->input->post('create')){
			$this->load->model('complain_model');
			$this->complain_model->create();
		}
		
		$data['yield'] = "complaints/create";
		$this->load->view('layout/application',$data);
		
	}
	
	
	function show($id = false){
		
		$this->db->where('id',$id);
		$data['complain'] = $this->db->get('complaints')->result();
		
		if(!$data['complain']){
			redirect('complaints');
			exit;
		}
		
		// get created by info
		$this->db->where('id',$data['complain'][0]->uid);
		$data['by'] = $this->db->get('users_kasse')->result();
		
		if($data['by']){
			$data['by_name'] = $data['by'][0]->name;
		}else{
			$data['by_name'] = '?';
		}
		
		if($this->input->post('status_update')){
			$string = array(
				'status' => $this->input->post('status')
			);
			$this->db->where('id',$id);
			$this->db->update('complaints',$string);
			
			$this->global_model->log_action('complain_status_updated',$id,false,$this->input->post('status'),0);
			
			redirect('complaints/show/'.$id);
		}
		
		if($this->input->post('create_comment')){
			
			$string = array(
				'comment' => $this->input->post('comment'),
				'created_timestamp' => time(),
				'complain_id' => $id,
				'uid' => $this->session->userdata('uid')
			);
			$this->db->insert('complain_comments',$string);
			
			redirect('complaints/show/'.$id);
			
		}
		
		$this->db->where('id',$data['complain'][0]->boutique_id);
		$data['boutique_info'] = $this->db->get('boutiques')->result();
		
		if($data['boutique_info']){
			$data['boutique_name'] = $data['boutique_info'][0]->name;
		}else{
			$data['boutique_name'] = '';
		}
		
		$data['yield'] = "complaints/show";
		$this->load->view('layout/application',$data);
		
	}
	
	function export_a4($id = false){
		
		
		$this->db->where('id',$id);
		$data['complain'] = $this->db->get('complaints')->result();
		
		if(!$data['complain']){
			redirect('complaints');
			exit;
		}
		
		$this->db->where('id',$data['complain'][0]->boutique_id);
		$data['boutique_info'] = $this->db->get('boutiques')->result();
		
		if($data['boutique_info']){
			$data['initial'] = $data['boutique_info'][0]->initial;
			$data['address'] = $data['boutique_info'][0]->address;
			$data['tlfcvrinfo'] = $data['boutique_info'][0]->tlcvremail;
		}else{
			$data['initial'] = '';
			$data['tlfcvrinfo'] = '';
			$data['address'] = '';
		}
		
		$data['test'] = '';
		$this->load->view('complaints/a4',$data);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */