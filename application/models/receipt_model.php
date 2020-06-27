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
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$pin = $this->input->post('pin');
		$phone_code = $this->input->post('phone_code');
		$product_id = $this->input->post('product_id');

		$description = $this->input->post('description');

		$pickup_time = $this->input->post('pickup_time');
		$paid = $this->input->post('paid');

    $discount = $this->input->post('discount');
    $repair_price = $this->input->post('repair_price');
    $price_total = array_sum($repair_price);
    $total_after_discount = $price_total;
    $discount_amount = 0;
    if($discount){
      $discount_amount = ($price_total * $discount)/100;
      $total_after_discount = $price_total - $discount_amount;
    }

    if($product_id == 'diverse'){
      $product_data = array(
        'name'              => $this->input->post('product_name'),
        'boutique_id'       => $this->session->userdata('active_boutique'),
        'created_timestamp' => time()
      );
      $this->db->insert('products',$product_data);

      $product_id = $this->db->insert_id();

      $this->global_model->log_action('product_created',$product_id);
    }

    if($phone && !$this->db->from('customers')->where('phone',$this->input->post('phone'))->count_all_results()){
        $customer_data = array(
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'phone_code' => $phone_code,
          'pin' => $pin,
          'created_timestamp' => date("Y-m-d H:i:s")
        );

        $this->db->insert('customers',$customer_data);
    }

		$string = array(
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'pin' => $pin,
			'pickup_time' => $pickup_time,
			'phone_code' => $phone_code,
			'paid' => $paid,
			'description' => $description,
			'created_timestamp' => time(),
			'boutique_id' => $this->session->userdata('active_boutique'),
			'uid' => $this->session->userdata('uid'),
			'product_id' => $product_id,
      'discount' => $discount,
      'total' => $price_total,
      'total_after_discount' => $total_after_discount,
      'discount_amount' => $discount_amount
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

    return $inserted_id;
		//redirect('receipt?open_receipt='.$inserted_id.'');

    }


    function update()
    {
        $name = $this->input->post('name');
		    $email = $this->input->post('email');
    		$phone = $this->input->post('phone');
    		$pin = $this->input->post('pin');
    		$phone_code = $this->input->post('phone_code');
        $product_id = $this->input->post('product_id');

    		$description = $this->input->post('description');

    		$pickup_time = $this->input->post('pickup_time');
    		$paid = $this->input->post('paid');
        $delivered = $this->input->post('delivered');

        $discount = $this->input->post('discount');
        $repair_price = $this->input->post('repair_price_existing');
        $price_total = array_sum($repair_price);
        $total_after_discount = $price_total;
        $discount_amount = 0;
        if($discount){
          $discount_amount = ($price_total * $discount)/100;
          $total_after_discount = $price_total - $discount_amount;
        }

        $id = $this->input->post('id');

        if($product_id == 'diverse'){
          $product_data = array(
            'name'              => $this->input->post('product_name'),
            'boutique_id'       => $this->session->userdata('active_boutique'),
            'created_timestamp' => time()
          );
          $this->db->insert('products',$product_data);

          $product_id = $this->db->insert_id();

          $this->global_model->log_action('product_created',$product_id);
        }

        if(!$this->db->from('customers')->where('email',$this->input->post('email'))->count_all_results()){
            $customer_data = array(
              'name' => $name,
              'email' => $email,
              'phone' => $phone,
              'phone_code' => $phone_code,
              'pin' => $pin,
              'created_timestamp' => date("Y-m-d H:i:s")
            );

            $this->db->insert('customers',$customer_data);
        }


    		$string = array(
    			'name' => $name,
				  'email' => $email,
    			'phone' => $phone,
    			'pin' => $pin,
    			'pickup_time' => $pickup_time,
    			'phone_code' => $phone_code,
    			'paid' => $paid,
    			'description' => $description,
          'delivered' => $delivered,
          'product_id' => $product_id,
          'discount' => $discount,
          'total' => $price_total,
          'total_after_discount' => $total_after_discount,
          'discount_amount' => $discount_amount
    		);

    		$this->db->where('id',$id)->update('receipt',$string);


        $repair_existing = $this->input->post('repair_name_existing');

        if($repair_existing){
          foreach($repair_existing as $repid => $repairex){

      			$string = array(
      				'name' => $repairex,
      				'price' => $_POST['repair_price_existing'][$repid]
      			);

      			$this->db->where('id',$repid)->update('repairs',$string);

      		}
        }



    		$repairs = $this->input->post('repair_name');


    		$i = 0;

        if($repairs){
          foreach($repairs as $repair){

      			$string = array(
      				'name' => $repair,
      				'price' => $_POST['repair_price'][$i],
      				'receipt_id' => $id
      			);
      			$this->db->insert('repairs',$string);


      			$i++;
      		}
        }


    		//redirect('receipt?open_receipt='.$inserted_id.'');

    }

}

// end of model file
