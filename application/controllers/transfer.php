<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('tranfer_overview');
    }

	public function index($status = 0)
	{
		ini_set('memory_limit', '-1');
		$data['title'] = 'Overførsler';
		/*
		$this->db->order_by('transfered, id');
		$this->db->where('type','bought');
		if($status == 1){
			$this->db->where('transfered',1);
		}elseif($status == 2){
			$this->db->where('wrong',0);
			$this->db->where('transfered',0);
		}elseif($status == 3){
			$this->db->where('wrong',1);
		}
		$this->db->where('exchange',0);
		$data['tranfers'] = $this->db->get('orders')->result();
		*/


		if($this->input->post('bulk_transfer')){
			$this->bulk_complete();
		}
		$data['status']  = $status;
		$data['yield'] = "transfer/index";
		$this->load->view('layout/application',$data);
	}

	function bulk_complete(){
		$bulk = $this->input->post('bulk');

		foreach($bulk as $b):

			$this->db->where('id',$b);
			$tranfers = $this->db->get('orders')->result();

			if($tranfers){
				$string = array(
					'transfered' => 1,
					'wrong' => 0
				);
				$this->db->where('id',$b);
				$this->db->update('orders',$string);
			}

		endforeach;

		redirect(current_url());

	}

	function complete($id){

		$this->db->where('id',$id);
		$tranfers = $this->db->get('orders')->result();

		if($tranfers){
			$string = array(
				'transfered' => 1,
				'wrong' => 0
			);
			$this->db->where('id',$id);
			$this->db->update('orders',$string);
		}

		redirect($this->input->get('r'));

	}


	function mark_as_wrong($id){

		$this->db->where('id',$id);
		$tranfers = $this->db->get('orders')->result();

		if($tranfers){
			$string = array(
				'wrong' => 1
			);
			$this->db->where('id',$id);
			$this->db->update('orders',$string);
		}

		redirect($this->input->get('r'));

	}


	function edit($type = false){

		$type = $this->input->post('name');
		$id   = $this->input->post('pk');
		$value = $this->input->post('value');

		$this->db->where('id',$id);
		$tranfers = $this->db->get('orders')->result();

		if($tranfers){

			if($tranfers[0]->wrong == 1){
				$wrong = 0;
			}else{
				$wrong = $tranfers[0]->wrong;
			}

			if($type == 'regnr'){

				$string = array(
					'reg_nr' => $value,
					'wrong' => $wrong
				);
				$this->db->where('id',$id);
				$this->db->update('orders',$string);

			}elseif($type == 'kontonr'){

				$string = array(
					'account_nr' => $value,
					'wrong' => $wrong
				);
				$this->db->where('id',$id);
				$this->db->update('orders',$string);

			}

		}

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
