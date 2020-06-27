<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }

	public function index()
	{


		//$this->db->where('boutique_id',$this->session->userdata('active_boutique'));

		$this->db->order_by('TRIM(name)','asc');
		$data['customers'] = $this->db->get('customers')->result_array();

		$data['yield'] = "customer/index";
		$this->load->view('layout/application',$data);
	}

	public function update(){
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$pin = $this->input->post('pin');
		$phone_code = $this->input->post('phone_code');
		$discount = $this->input->post('discount');
		$id = $this->input->post('id');
		$type = $this->input->post('type');

		$customer_data = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'phone_code' => $phone_code,
			'pin' => $pin,
			'discount' => $discount,
			'type' => $type,
			'cvr' => ''
		);

		if($type == 'company'){
			$customer_data['cvr'] = $this->input->post('cvr')?$this->input->post('cvr'):'';
			$customer_data['ean'] = $this->input->post('ean')?$this->input->post('ean'):'';
			$customer_data['reference'] = $this->input->post('reference')?$this->input->post('reference'):'';
			$customer_data['contact_person'] = $this->input->post('contact_person')?$this->input->post('contact_person'):'';
			$customer_data['address'] = $this->input->post('address')?$this->input->post('address'):'';
		}else{
			$customer_data['cvr'] = '';
			$customer_data['ean'] = '';
			$customer_data['reference'] = '';
			$customer_data['contact_person'] = '';
			$customer_data['address'] = '';
		}

		$this->db->where('id',$id)->update('customers',$customer_data);
		redirect('customer');
	}

	public function save(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Navn', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|is_unique[customers.email]');

		if ($this->form_validation->run() == TRUE)
		{
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$phone = $this->input->post('phone');
			$pin = $this->input->post('pin');
			$phone_code = $this->input->post('phone_code');
			$discount = $this->input->post('discount');
			$type = $this->input->post('type');
			$id = $this->input->post('id');

			$customer_data = array(
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'phone_code' => $phone_code,
				'pin' => $pin,
				'discount' => $discount,
				'created_timestamp' => date("Y-m-d H:i:s"),
				'type' => $type
			);

			if($type == 'company'){
				$customer_data['cvr'] = $this->input->post('cvr')?$this->input->post('cvr'):'';
				$customer_data['ean'] = $this->input->post('ean')?$this->input->post('ean'):'';
				$customer_data['reference'] = $this->input->post('reference')?$this->input->post('reference'):'';
				$customer_data['contact_person'] = $this->input->post('contact_person')?$this->input->post('contact_person'):'';
				$customer_data['address'] = $this->input->post('address')?$this->input->post('address'):'';
			}else{
				$customer_data['cvr'] = '';
				$customer_data['ean'] = '';
				$customer_data['reference'] = '';
				$customer_data['contact_person'] = '';
				$customer_data['address'] = '';
			}

			$this->db->insert('customers',$customer_data);
			redirect('customer');
		}else{
			$this->index();
		}
	}

	public function get_json_by_email(){
		$data = array();
		$email = $this->input->get('email');
		if($email){
			$q = $this->db->where('email',$email)->get('customers');
			if($q->num_rows()){
				$data = $q->row_array();
			}
		}

		echo json_encode($data);
	}

	public function get_json_by_phone(){
		$data = array();
		$phone = $this->input->get('phone');
		if($phone){
			$q = $this->db->where('phone',$phone)->get('customers');
			if($q->num_rows()){
				$data = $q->row_array();
			}
		}

		echo json_encode($data);
	}

	public function export_csv(){
		$this->load->dbutil();

		$this->db->select("name,type,email,phone_code,phone,pin,cvr,ean,reference,contact_person,address,discount,created_timestamp");
		$this->db->order_by('TRIM(name)','asc');
		$query = $this->db->get('customers');

		$csv = $this->dbutil->csv_from_result($query);

		$this->load->helper('download');

		force_download("customer_".time().".csv", $csv);
	}

	public function delete_customer($id){
		if($id){
			$this->db->where('id',$id)->delete('customers');
		}

		redirect('customer');
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
