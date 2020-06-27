<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bought extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('bought_devices_overview');
    }

	public function index()
	{

		$per_page = 50;

		if($this->uri->segment(3)){
			$offset = $this->uri->segment(3);
		}else{
			$offset = 0;
		}

		$this->load->model('device_model');

		$data['title'] = 'Købte enheder';

		if($this->input->post('buy_device')){
			$this->device_model->create_order('bought');
		}

		if($this->input->post('create_fraud')){
			$this->device_model->create_fraud();
		}

		if($this->input->post('create_defect')){
			$this->device_model->create_defect();
		}

		$this->load->model('product_model');
		$data['products'] = $this->product_model->get_products();
		$data['gbs_list'] = $this->product_model->get_gbs();


		// COUNT TOTAL
		$this->db->order_by('id','desc');
	  	$this->db->where('type','bought');
	  	if($this->input->get('sold') == 1){
	    	$this->db->where('sold',1);
		}elseif($this->input->get('sold') == 2){
	    	$this->db->where('sold',0);
		}
		if($this->input->get('model')){
			$this->db->where('product_id',$this->input->get('model'));
		}
		if($this->input->get('gb') == TRUE){
			$this->db->where('gb',$this->input->get('gb'));
		}

		$this->db->where('fraud',0);
		$this->db->where('defect',0);
	  	$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	  	$totalorderscount = $this->db->get('orders')->num_rows();

	  	/////

		$this->load->library('pagination');

		$config['base_url'] = site_url('bought/index');
		$config['total_rows'] = $totalorderscount;
		$config['per_page'] = $per_page;
		$config['full_tag_open'] = '<div class="pagi">';
		$config['full_tag_close'] = '</div>';

		$this->pagination->initialize($config);

		$this->db->order_by('id','desc');
	  	$this->db->where('type','bought');
	  	if($this->input->get('sold') == 1){
	    	$this->db->where('sold',1);
		}elseif($this->input->get('sold') == 2){
	    	$this->db->where('sold',0);
		}
		if($this->input->get('model')){
			$this->db->where('product_id',$this->input->get('model'));
		}
		if($this->input->get('gb') == TRUE){
			$this->db->where('gb',$this->input->get('gb'));
		}
		$this->db->limit($per_page,$offset);
		$this->db->where('fraud',0);
		$this->db->where('defect',0);
	  	$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	  	$data['orders'] = $this->db->get('orders')->result();

		if($this->input->get('cid')){
			// get customer info
			$this->db->where('id',$this->input->get('cid'));
			$data['customer_info'] = $this->db->get('orders')->result();
		}else{
			$data['customer_info'] = false;
		}

		$data['yield'] = "bought/index";
		$this->load->view('layout/application',$data);
	}


	function tested(){

		$id = $this->input->post('id');

		$this->db->where('order_id',$id);
		$orderid = $this->db->get('tests')->result();

		if($orderid){
			$this->db->where('id',$orderid[0]->uid);
			$userinfo = $this->db->get('users_kasse')->result();
		}else{
			$userinfo = false;
		}

		if($userinfo){
			$data['user'] = $userinfo;
		}else{
			$data['user'] = false;
		}

		$this->load->view('bought/tested_view',$data);

	}

	function already_tested($orderid = false){

		$string = array(
			'already_tested' => 1
		);
		$this->db->where('id',$orderid);
		$this->db->update('orders',$string);

		redirect('bought');

	}


	public function test($orderid = false)
	{

		if($this->input->post('ready')){
			$this->load->model('order_model');
			$this->order_model->test_complete($orderid);
		}

		/*$this->db->where('order_id',$orderid);
		$testinfo = $this->db->get('tests')->result();

		if($testinfo){
			$this->session->set_flashdata('error','Allerede testet');
			redirect('bought');
			exit;
		}*/

		$data['me'] = $this->global_model->me();

		$data['title'] = 'Test enhed';

		$this->load->model('order_model');
		$data['order'] = $this->order_model->get_order_by_id($orderid,false,false);

		$data['yield'] = "bought/test";
		$this->load->view('layout/application',$data);
	}

	function edit(){

		$id = $this->input->post('id');

		if($this->input->post('edit_device')){
			$this->load->model('device_model');
			$this->device_model->edit_order('bought',$id);
		}

		$this->load->model('product_model');
		$this->load->model('order_model');

		$data['order_info'] = $this->order_model->get_order_by_id($id);

		$data['products'] = $this->product_model->get_products();
		$data['gbs_list'] = $this->product_model->get_gbs();


		$this->load->view('bought/_edit',$data);

	}


	function transfer(){

		$id = $this->input->post('id');

		if($this->input->post('transfer')){
			$this->load->model('device_model');
			$this->device_model->transfer($id);
		}

		$this->load->model('boutique_model');
		$this->load->model('order_model');

		$data['order_info'] = $this->order_model->get_order_by_id($id,false,false);

		$data['boutiques'] = $this->boutique_model->get();
		$this->load->view('bought/_transfer',$data);

	}

	function parts_used(){

		$id = $this->input->post('id');

		if($this->input->post('parts_used')){
			$this->load->model('order_model');
			$this->order_model->update_parts_used($id);
		}

		$this->load->model('product_model');
		$this->load->model('order_model');

		$data['order_info'] = $this->order_model->get_order_by_id($id);

		$this->db->where('hide',0);
		$this->db->group_by('unqiue_string');
		$this->db->order_by('name','asc');
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$this->db->where('product_id',$data['order_info']->product_id);
		$data['parts'] = $this->db->get('parts')->result();

		$this->load->view('bought/_parts_used',$data);

	}


	function cancel($id){

		$this->load->model('order_model');
		$data['order_info'] = $this->order_model->get_order_by_id($id);

		if($data['order_info']){
			$string = array(
				'type' => 'cancelled'
			);
			$this->db->where('id',$id);
			$this->db->update('orders',$string);


			// update inventory

			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$data['order_info']->product_id);
			$this->db->where('gb',$data['order_info']->gb);
			$inventory = $this->db->get('inventory')->result();

			if($inventory){
				$new_inventory = $inventory[0]->inventory-1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('inventory',$string);
			}

		}

		redirect('bought');

	}


	function calculatePrice(){

		$phone = $this->input->post('phone');
		$gb	   = $this->input->post('gb');
		$condition = $this->input->post('condition');

		$this->load->model('device_model');
		$price = $this->device_model->calculatePrice($phone,$gb,$condition,'bought');
		echo $price;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
