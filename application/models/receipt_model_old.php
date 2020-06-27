<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function create()
    {
        $name = $this->input->post('name');
		$phone = $this->input->post('phone');
		$pin = $this->input->post('pin');
		$phone_code = $this->input->post('phone_code');

		$description = $this->input->post('description');

		$pickup_time = $this->input->post('pickup_time');
		$paid = $this->input->post('paid');

    $product_id = $this->input->post('product_id');

    if($product_id == 'andet'){
      $product_id = 0;
      if($this->input->post('product_name')){
        $prod_order = $this->db->select("MAX(prod_order) as max_order")->get('products')->row()->max_order + 1;
        $prod_data = array();
        $prod_data['name'] = $this->input->post('product_name');
        $prod_data['created_timestamp'] = time();
        $prod_data['boutique_id'] = $this->session->userdata('active_boutique');
        $prod_data['prod_order'] = $prod_order;
        $prod_data['price'] = 0;
        $prod_data['category'] = 0;
        $prod_data['variant_gb'] = 0;
        $prod_data['variant_color'] = '';
        $this->db->insert('products',$prod_data);
        $product_id = $this->db->insert_id();
      }
    }
	
		if($product_id == ''){
			$product_id = 0;
		}
		
		$string = array(
			'name' => $name,
			'phone' => $phone,
			'pin' => $pin,
			'pickup_time' => $pickup_time,
			'phone_code' => $phone_code,
			'paid' => $paid,
			'description' => $description,
			'created_timestamp' => time(),
			'boutique_id' => $this->session->userdata('active_boutique'),
			'uid' => $this->session->userdata('uid'),
      'product_id' => $product_id
		);
		$this->db->insert('receipt',$string);

		$inserted_id = $this->db->insert_id();

		$repairs = $this->input->post('repair_name');

		$i = 0;
		foreach($repairs as $repair){

			$string = array(
				'name' => $repair,
				'price' => $_POST['repair_price'][$i],
				'receipt_id' => $inserted_id
			);
			$this->db->insert('repairs',$string);


			$i++;
		}

		redirect('receipt?open_receipt='.$inserted_id.'');

    }

}

// end of model file
