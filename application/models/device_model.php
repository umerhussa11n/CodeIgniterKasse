<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function create_order($type = false)
    {

        $gb_name_bought_from = '';

    	$model                 = $this->input->post('model');
	    $gb                    = $this->input->post('gb');
	    $imei                  = $this->input->post('imei');
	    $color                 = $this->input->post('color');

	    $seller_name           = $this->input->post('seller_name');
	    $seller_id             = $this->input->post('seller_id');
	    $seller_email          = $this->input->post('seller_email');

	    $errors                = $this->input->post('errors');
	    $price                 = $this->input->post('price');

	    $regnr                 = $this->input->post('reg_nr');
	    $account_nr            = $this->input->post('account_nr');

	    $bought_order_id       = $this->input->post('bought_order_id');

	    $number                = $this->input->post('number');
	    $buyer_name            = $this->input->post('buyer_name');

      $email            = $this->input->post('email');

	    $cvr           		   = $this->input->post('cvr');

	    $webshop_id            = $this->input->post('order_id');

	    $condition             = $this->input->post('condition');

	    $payment_type          = $this->input->post('payment_type');

	    $access                = $this->input->post('access');

	    $partName              = $this->input->post('newAccessName');
	    $partPrice             = $this->input->post('newAccessPrice');

	    $exchange              = $this->input->post('exchange');

	    $extra_access          = $this->input->post('extra_access');
	    $extra_access_to_order_id = $this->input->post('extra_access_to_order_id');

	    $exchangeId            = $this->input->post('exchangeId');
	    $exchangePrice         = $this->input->post('exchangePrice');
	    $exchangeBoughtPrice   = $this->input->post('exchangeBoughtPrice');

	    $legimation_type       = $this->input->post('legimation_type');

	    $discount_amount       = $this->input->post('discount_amount');

	    $panserBox  		   = $this->input->post('panserBox');
	    $headsetBox  		   = $this->input->post('headsetBox');
	    $beskyttelseBox  	   = $this->input->post('beskyttelseBox');


	    $insuranceBox          = $this->input->post('insuranceBox');
	    $insurancePrice        = $this->input->post('insurancePrice');
	    $insuranceYears        = $this->input->post('insurance_years');

	    $split_cash      	   = $this->input->post('split_cash');
	    $split_card			   = $this->input->post('split_card');

	    if(!$payment_type){
		    $payment_type = 'cash';
	    }

	    if($type == 'sold'){
		    if($payment_type == 'webshop'){
			    $set_boutique_id = 4;
		    }else{
			    $set_boutique_id = $this->session->userdata('active_boutique');
		    }
	    }else{
		    $set_boutique_id = $this->session->userdata('active_boutique');
	    }

	    if($type == 'sold'){
		    $this->db->where('id',$bought_order_id);
		    $get_order_info = $this->db->get('orders')->result();

		    if($get_order_info){
			    $model = $get_order_info[0]->product_id;
			    $gb    = $get_order_info[0]->gb_id;
			    $gb_name_bought_from    = $get_order_info[0]->gb;
			    $imei  = $get_order_info[0]->imei;
			    $color = $get_order_info[0]->color;
		    }

		    $seller_name = $buyer_name;

	    }elseif($type == 'access'){
		    $seller_name = $buyer_name;
	    }


	    // get model
	    $this->db->where('id',$model);
	    $product_info = $this->db->get('products')->result();

	    if($product_info){
		    $model_name = $product_info[0]->name;
	    }else{
		    $model_name = '';
	    }


	    if($discount_amount){
		    // calculate new price
		    $discount = $discount_amount/100;
		    $newprice = $price*$discount;
		    $price = $price-$newprice;
	    }


	    // get access
	    if($access == 'new_access'){
		    // create new part
		    $string = array(
		    	'name' => $partName,
		    	'price' => $partPrice,
		    	'product_id' => $model,
		    	'inventory' => 1,
		    	'hide' => 1,
		    	'unqiue_string' => '',
		    	'part_of_inventory' => 0,
		    	'part_order' => 0,
		    	'created_timestamp' => time(),
		    	'boutique_id' => $this->session->userdata('active_boutique')
		    );
		    $this->db->insert('parts',$string);

		    $access_name = $partName;
		    $access_id      = $this->db->insert_id();

	    }else{

		    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$model);
			$this->db->where('unqiue_string',$access);
		    $access_info = $this->db->get('parts')->result();

		    if($access_info){
			    $access_name = $access_info[0]->name;
			    $access_id = $access_info[0]->id;
		    }else{
			    $access_name = '';
			    $access_id = 0;
		    }

	    }


	    // get gb
	    $this->db->where('id',$gb);
	    $gb_info = $this->db->get('gbs')->result();

	    if($gb_info){
		    $gb_name = $gb_info[0]->name;
	    }else{
		    $gb_name = '';
	    }

	    if($type == 'sold'){
		    $gb_name = $gb_name_bought_from;
	    }

	    if($insuranceBox){
		    $price += $insurancePrice;
	    }

	    $company_name = $this->input->post('company_name');


	    if($payment_type == 'invoice'){
        /*
	    	$company_name = $this->input->post('invoice_company_name');
	    	$seller_name = $this->input->post('invoice_buyer_name');
	    	$number = $this->input->post('invoice_number');
	    	$cvr = $this->input->post('invoice_cvr');
        */
	    }

	    if($this->input->post('hidden') == 1){
		    $hidden = 1;
	    }else{
		    $hidden = 0;
	    }


	    if($bought_order_id == ''){
		    $bought_order_id = 0;
	    }

	    if($extra_access_to_order_id == ''){
		    $extra_access_to_order_id = 0;
	    }

	    if($discount_amount == ''){
		    $discount_amount = 0;
	    }

	    if($exchangeId == ''){
		    $exchangeId = 0;
	    }

	    if($split_cash == ''){
		    $split_cash = '0.00';
	    }

	    if($split_card == ''){
		    $split_card = '0.00';
	    }

	    if($exchangePrice == ''){
		    $exchangePrice = '0.00';
	    }

	    if($insurancePrice == ''){
		    $insurancePrice = '0.00';
	    }

	    if($exchangeBoughtPrice == ''){
		    $exchangeBoughtPrice = '0.00';
	    }

	    if($insuranceYears == ''){
		    $insuranceYears = 0;
	    }

	    if($panserBox == ''){
		    $panserBox = 0;
	    }

	    if($headsetBox == ''){
		    $headsetBox = 0;
	    }

	    if($beskyttelseBox == ''){
		    $beskyttelseBox = 0;
	    }

	    if($account_nr == ''){
		    $account_nr = 0;
	    }

	    $string = array(
			'name' => $seller_name,
      'email' => $email,
			'address' => '',
			'phone_id' => 0,
			'imei' => $imei,
			'company' => $company_name,
			'color' => $color,
			'seller_id' => $seller_id,
			'number' => $number,
			'seller_email'   => $seller_email,
			'errors' => $errors,
			'payment_type' => $payment_type,
			'type' => $type,
			'condition' => $condition,
			'price' => $price,
			'seller_name' => '',
			'reg_nr' => $regnr,
			'part' => $access_name,
			'seller_number' => '',
			'hidden' => $hidden,
			'part_id' => $access_id,
			'webshop_id' => $webshop_id,
			'account_nr' => $account_nr,
			'created_timestamp' => time(),
			'uid' => $this->session->userdata('uid'),
			'gb_id' => $gb,
			'discount' => $discount_amount,
			'exchange' => $exchange,
			'wrong' => 0,
			'bought_from_order_id' => $bought_order_id,
			'sold' => 0,
			'product_id' => $model,
			'legimation_type' => $legimation_type,
			'product' => $model_name,
			'gb' => $gb_name,
			'cvr' => $cvr,
			'transfered' => 0,
			'withPanser' => $panserBox,
			'withHeadset' => $headsetBox,
			'withFilter' => $beskyttelseBox,
			'exchangeId' => $exchangeId,
			'split_cash' => $split_cash,
			'split_card' => $split_card,
			'exchangePrice' => $exchangePrice,
			'exchangeBoughtPrice' => $exchangeBoughtPrice,
			'extra_access_to_order_id' => $extra_access_to_order_id,
			'already_tested' => 0,
			'fraud' => 0,
			'defect' => 0,
			'subtotal' => '0.00',
			'creditlineConnectedID' => 0,
			'cancelled' => 0,
			'boutique_id' => $set_boutique_id,
			'credit_reason' => '',
			'garanti' => 0,
			'insurance' => $insuranceBox,
			'insurance_price' => $insurancePrice,
			'insurance_years' => $insuranceYears,
      'show_name' => $this->input->post('show_name')?$this->input->post('show_name'):0
		);
		$this->db->insert('orders',$string);


		$inserted_order_id = $this->db->insert_id();

    if($this->input->post('send_email')){
      $this->load->library("Email_manager");
      $this->email_manager->order_notification($inserted_order_id);
    }
		// update sold order line
		if($type == 'sold'){
			if($this->input->post('hidden') == 1){
				$string = array(
					'sold' => 1,
					'defect' => 1
				);
			}else{
				$string = array(
					'sold' => 1
				);
			}
			$this->db->where('id',$bought_order_id);
			$this->db->update('orders',$string);

		}

		if($type == 'sold'){

			// update inventory
			$this->db->where('boutique_id',$set_boutique_id);
			$this->db->where('product_id',$model);
			$this->db->where('gb',$gb_name);
			$inventory = $this->db->get('inventory')->result();

			if($inventory){
				$new_inventory = $inventory[0]->inventory-1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('inventory',$string);
			}

			redirect('sold?open_receipt='.$inserted_order_id.'');

		}elseif($type == 'access'){


			// update inventory

			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$model);
			$this->db->where('unqiue_string',$access);
			$inventory = $this->db->get('parts')->result();

			if($inventory){
				if($inventory[0]->part_of_inventory){
					$new_inventory = $inventory[0]->inventory-1;
					$string = array(
						'inventory' => $new_inventory
					);
					$this->db->where('id',$inventory[0]->id);
					$this->db->update('parts',$string);
				}
			}

			if($extra_access == 1 && $extra_access_to_order_id){
				redirect('access/extra/'.$extra_access_to_order_id.'');
			}elseif($extra_access == 1){
				redirect('access/extra/'.$inserted_order_id.'');
			}else{
				redirect('access?open_receipt='.$inserted_order_id.'');
			}

		}else{

			// update inventory
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$model);
			$this->db->where('gb',$gb_name);
			$inventory = $this->db->get('inventory')->result();


			if($inventory){
				$new_inventory = $inventory[0]->inventory+1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('inventory',$string);
			}else{
				$string = array(
					'color' => 0,
					'condition' => 0,
					'inventory' => 1,
					'product_id' => $model,
					'gb' => $gb_name,
					'boutique_id' => $this->session->userdata('active_boutique'),
					'created_timestamp' => time()
				);
				$this->db->insert('inventory',$string);
			}
			if($this->input->post('unitsFromSamePerson')){
				redirect('bought?open_receipt='.$inserted_order_id.'&cid='.$inserted_order_id.'');
			}else{
				redirect('bought?open_receipt='.$inserted_order_id.'');
			}
		}

    }



    function edit_order($type = false, $id = false)
    {

		$webshop_order_id_info = false;

    	$model                 = $this->input->post('model');
	    $gb                    = $this->input->post('gb');
	    $imei                  = $this->input->post('imei');
	    $color                 = $this->input->post('color');

	    $seller_name           = $this->input->post('seller_name');
	    $seller_id             = $this->input->post('seller_id');
	    $seller_email          = $this->input->post('seller_email');

	    $errors                = $this->input->post('errors');
	    $price                 = $this->input->post('price');

	    $regnr                 = $this->input->post('reg_nr');
	    $account_nr            = $this->input->post('account_nr');

	    $bought_order_id       = $this->input->post('bought_order_id');

	    $number                = $this->input->post('number');
	    $buyer_name            = $this->input->post('buyer_name');

	    $webshop_id            = $this->input->post('order_id');

	    $condition             = $this->input->post('condition');

	    $payment_type          = $this->input->post('payment_type');

		$access                = $this->input->post('access');

		$exchange              = $this->input->post('exchange');

		$company              = $this->input->post('company_name');
    $cvr = $this->input->post('cvr');
    $email = $this->input->post('email');

	    if($type == 'sold'){
		    $this->db->where('id',$id);
		    $get_order_info = $this->db->get('orders')->result();

		    if($get_order_info){
			    $model = $get_order_info[0]->product_id;
			    $gb    = $get_order_info[0]->gb_id;
			    $webshop_order_id_info = $get_order_info[0]->webshop_id;
		    }

		    $seller_name = $buyer_name;

	    }elseif($type == 'access'){
		    $seller_name = $buyer_name;
	    }

		if($payment_type == 'webshop'){
			$number = '';
			$seller_name = '';
		}else{
			$webshop_id = '';
		}

		if($webshop_order_id_info){
			$webshop_id = $webshop_order_id_info;
		}

	    // get model
	    $this->db->where('id',$model);
	    $product_info = $this->db->get('products')->result();

	    if($product_info){
		    $model_name = $product_info[0]->name;
	    }else{
		    $model_name = '';
	    }


	    // get access
	    $this->db->where('id',$access);
	    $access_info = $this->db->get('parts')->result();

	    if($access_info){
		    $access_name = $access_info[0]->name;
	    }else{
		    $access_name = '';
	    }

	    // get gb
	    $this->db->where('id',$gb);
	    $gb_info = $this->db->get('gbs')->result();

	    if($gb_info){
		    $gb_name = $gb_info[0]->name;
	    }else{
		    $gb_name = '';
	    }

	    $string = array(
			'name' => $buyer_name,
      'email' => $email,
      'cvr' => $cvr,
			'imei' => $imei,
			'company' => $company,
			'color' => $color,
			'seller_id' => $seller_id,
			'number' => $number,
			'condition' => $condition,
			'seller_email'   => $seller_email,
			'errors' => $errors,
			'type' => $type,
			'price' => $price,
			'payment_type' => $payment_type,
			'reg_nr' => $regnr,
			'webshop_id' => $webshop_id,
			'account_nr' => $account_nr,
			'exchange' => $exchange,
			'part' => $access_name,
			'part_id' => $access,
			'bought_from_order_id' => $bought_order_id,
			'product_id' => $model,
			'product' => $model_name,
			'boutique_id' => $this->session->userdata('active_boutique')
		);
		$this->db->where('id',$id);
		$this->db->update('orders',$string);

    if($this->input->post('send_email')){
      $this->load->library("Email_manager");
      $this->email_manager->order_notification($id);
    }

		if($type == 'sold'){
			redirect('sold');
		}elseif($type == 'access'){
			redirect('access');
		}else{
			redirect('orders/show/'.$id);
		}

    }


    function transfer($order_id = false,$move_page = false){

	    $this->db->where('id',$order_id);
	    $get_order_info = $this->db->get('orders')->result();

	    if($get_order_info){

	    	$current_gb_id = $get_order_info[0]->gb;
	    	$old_gb_id     = $get_order_info[0]->gb_id;
	    	$product_id    = $get_order_info[0]->product_id;

		    $boutique = $this->input->post('boutique');

		    if($boutique == 'fraud'){
			    // move to fraud inventory
			     $string = array(
			    	'fraud' => 1,
			    	'defect' => 0
			    );
			    $this->db->where('id',$order_id);
			    $this->db->update('orders',$string);

			    $this->global_model->log_action('transfer',$order_id,false,'fraud',0);
		    }elseif($boutique == 'defect'){
			    // move to defect inventory

			    $string = array(
			    	'defect' => 1,
			    	'fraud' => 0
			    );
			    $this->db->where('id',$order_id);
			    $this->db->update('orders',$string);

			    $this->global_model->log_action('transfer',$order_id,false,'defect',0);

		    }else{

			    // get gb id to move
			    $this->db->where('boutique_id',$boutique);
			    $this->db->where('product_id',$product_id);
			    $this->db->where('name',$current_gb_id);
			    $gb_info = $this->db->get('gbs')->result();

			    if($gb_info){
				    $new_gb_id = $gb_info[0]->id;
			    }else{
				 	$new_gb_id = $old_gb_id;
			    }

			    $string = array(
			    	'boutique_id' => $boutique,
			    	'gb_id' => $new_gb_id,
			    	'fraud' => 0,
			    	'defect' => 0
			    );
			    $this->db->where('id',$order_id);
			    $this->db->update('orders',$string);

			    $this->global_model->log_action('transfer',$order_id,$get_order_info[0]->boutique_id,false,$boutique);


			    $string = array(
			    	'boutique_id' => $boutique
			    );
			    $this->db->where('order_id',$order_id);
			    $this->db->update('parts_used',$string);

		    }

	    }



	    $this->db->where('bought_from_order_id',$order_id);
	    $get_order_info = $this->db->get('orders')->result();

	    if($get_order_info){

	    	$current_gb_id = $get_order_info[0]->gb;
	    	$old_gb_id     = $get_order_info[0]->gb_id;
	    	$product_id    = $get_order_info[0]->product_id;

		    $boutique = $this->input->post('boutique');


		    // get gb id to move
		    $this->db->where('boutique_id',$boutique);
		    $this->db->where('product_id',$product_id);
		    $this->db->where('name',$current_gb_id);
		    $gb_info = $this->db->get('gbs')->result();

		    if($gb_info){
			    $new_gb_id = $gb_info[0]->id;
		    }else{
			 	$new_gb_id = $old_gb_id;
		    }

		    $string = array(
		    	'boutique_id' => $boutique,
		    	'gb_id' => $new_gb_id
		    );
		    $this->db->where('id',$order_id);
		    $this->db->update('orders',$string);


		    $string = array(
		    	'boutique_id' => $boutique
		    );
		    $this->db->where('order_id',$order_id);
		    $this->db->update('parts_used',$string);

	    }

	    if($move_page){
	    	$this->session->set_flashdata('success','Enheden er overført');
		    redirect('move');
	    }else{
		    redirect('orders/show/'.$order_id);
	    }


    }


    function create_fraud($redirect_url = false){

	    $id = $this->input->post('id');

	    $id = explode(",",$id);

	    foreach($id as $id){

		    $this->db->where('id',$id);
		    $this->db->where('sold',0);
		    $orders = $this->db->get('orders')->result();

		    if($orders){

			    $string = array(
			    	'fraud' => 1
			    );
			    $this->db->where('id',$id);
			    $this->db->update('orders',$string);

			    $this->global_model->log_action('fraud_order',$id);

		    }

	    }

	    if($redirect_url){
		    redirect($redirect_url);
	    }else{
	   		redirect('bought');
	    }

    }


    function create_defect($redirect_url = false){

	    $id = $this->input->post('id');

	    $id = explode(",",$id);

	    foreach($id as $id){

		    $this->db->where('id',$id);
		    $this->db->where('sold',0);
		    $orders = $this->db->get('orders')->result();

		    if($orders){

			    $string = array(
			    	'defect' => 1
			    );
			    $this->db->where('id',$id);
			    $this->db->update('orders',$string);

			    $this->global_model->log_action('defect_order',$id);

		    }


		}

	    if($redirect_url){
		    redirect($redirect_url);
	    }else{
	   		redirect('bought');
	    }

    }


    function calculatePriceSold($order_id,$condition){

	    $this->db->where('id',$order_id);
	    $order_info = $this->db->get('orders')->result();

	    if($order_info){

		   	$this->db->where('id',$order_info[0]->product_id);
		   	$product_info = $this->db->get('products')->result();

		   	if($product_info){
			   	$this->db->where('name',$order_info[0]->gb);
			   	$this->db->where('product_id',$order_info[0]->product_id);
			   	$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			   	$gb = $this->db->get('gbs')->result();

			   	if($gb){

			   		$price = $gb[0]->new_price;

			   		if($condition == 1){
				   		// helt ny
				   		$price += $product_info[0]->buy_new;
			   		}

			   		return number_format($price,0,'','');
			   	}else{
				   	return 0;
			   	}

		   	}else{
			   	return 0;
		   	}

	    }else{

	    	return 0;

	    }

    }


    function calculatePrice($phone,$gb,$condition,$type){

	    $this->db->where('id',$phone);
	    $phone_info = $this->db->get('products')->result();

	    if($phone_info){

		   	$this->db->where('id',$gb);
		   	$gb = $this->db->get('gbs')->result();

		   	if($gb){

		   		$price = $gb[0]->used_price;

		   		if($condition == 1){
			   		// helt ny
			   		$price += $phone_info[0]->sell_new;
		   		}elseif($condition == 2){
			   		// god stand
			   		$price += $phone_info[0]->sell_good_condition;
		   		}elseif($condition == 3){
			   		// slidt
			   		$price += $phone_info[0]->sell_worn;
		   		}elseif($condition == 4){
			   		// defekt
			   		$price += $phone_info[0]->sell_defect;
		   		}

		   		return number_format($price,0,'','');
		   	}else{
			   	return 0;
		   	}

	    }else{

	    	return 0;

	    }

    }

    function create_order_new($type = false){

        $gb_name_bought_from = '';

    	$model                 = $this->input->post('model');
	    $gb                    = $this->input->post('gb');
	    $imei                  = $this->input->post('imei');
	    $color                 = $this->input->post('color');

	    $seller_name           = $this->input->post('seller_name');
	    $seller_id             = $this->input->post('seller_id');
	    $seller_email          = $this->input->post('seller_email');

	    $errors                = $this->input->post('errors');
	    $price                 = $this->input->post('price');

	    $regnr                 = $this->input->post('reg_nr');
	    $account_nr            = $this->input->post('account_nr');

	    $bought_order_id       = $this->input->post('bought_order_id');

	    $number                = $this->input->post('number');
	    $buyer_name            = $this->input->post('buyer_name');
      $company_name          = $this->input->post('company_name');
	    $cvr           		     = $this->input->post('cvr');
      $email           		   = $this->input->post('email');
      $show_name             = $this->input->post('show_name')?$this->input->post('show_name'):0;

	    $webshop_id            = $this->input->post('order_id');

	    $condition             = $this->input->post('condition');

	    $payment_type          = $this->input->post('payment_type');
      $payment_amount        = $this->input->post('payment_amount');

	    $access                = $this->input->post('access');

	    $partName              = $this->input->post('newAccessName');
	    $partPrice             = $this->input->post('item_pris');
      $item_discount         = $this->input->post('item_discount');
      $partQty               = $this->input->post('qty');

	    $exchange              = $this->input->post('exchange');

	    $extra_access          = $this->input->post('extra_access');
	    $extra_access_to_order_id = $this->input->post('extra_access_to_order_id');

	    $exchangeId            = $this->input->post('exchangeId');
	    $exchangePrice         = $this->input->post('exchangePrice');
	    $exchangeBoughtPrice   = $this->input->post('exchangeBoughtPrice');

	    $legimation_type       = $this->input->post('legimation_type');

	    $discount_amount       = $this->input->post('discount_amount');

	    $panserBox  		   = $this->input->post('panserBox');
	    $headsetBox  		   = $this->input->post('headsetBox');
	    $beskyttelseBox  	   = $this->input->post('beskyttelseBox');


	    $insuranceBox          = $this->input->post('insuranceBox');
	    $insurancePrice        = $this->input->post('insurancePrice');
	    $insuranceYears        = $this->input->post('insurance_years');

	    $split_cash      	   = $this->input->post('split_cash');
	    $split_card			   = $this->input->post('split_card');

      $garanti = $this->input->post('garanti');

      $subtotal = $this->input->post('subtotal');

      if($this->input->post('discount')){
        $discount_amount = $this->input->post('discount');
      }
	    if(!$payment_type){
		    $payment_type = 'cash';
	    }

	    if($type == 'sold'){
		    if($payment_type == 'webshop'){
			    $set_boutique_id = 4;
		    }else{
			    $set_boutique_id = $this->session->userdata('active_boutique');
		    }
	    }else{
		    $set_boutique_id = $this->session->userdata('active_boutique');
	    }

	    if($type == 'sold'){
		    $this->db->where('id',$bought_order_id);
		    $get_order_info = $this->db->get('orders')->result();

		    if($get_order_info){
			    $model = $get_order_info[0]->product_id;
			    $gb    = $get_order_info[0]->gb_id;
			    $gb_name_bought_from    = $get_order_info[0]->gb;
			    $imei  = $get_order_info[0]->imei;
			    $color = $get_order_info[0]->color;
		    }

		    $seller_name = $buyer_name;

	    }elseif($type == 'access'){
		    $seller_name = $buyer_name;
	    }


      if(!$price){
        $price = 0;
        for($i=0;$i<count($partPrice);$i++){
          $qty = $partQty[$i]?$partQty[$i]:1;
          $unit_price = $partPrice[$i]?$partPrice[$i]:0;
          $item_discount_this = isset($item_discount[$i])?$item_discount[$i]:0;
          $item_discount_amount = ($item_discount_this * $qty * $unit_price)/100;
          $price += (($qty * $unit_price) - $item_discount_amount);
        }
      }


	    if($discount_amount){
		    // calculate new price
		    $discount = $discount_amount/100;
		    $newprice = $price*$discount;
		    $price = $price-$newprice;
	    }



      if(!$subtotal){
        $subtotal = $price;
      }

	    // get gb
	    $this->db->where('id',$gb);
	    $gb_info = $this->db->get('gbs')->result();

	    if($gb_info){
		    $gb_name = $gb_info[0]->name;
	    }else{
		    $gb_name = '';
	    }

	    if($type == 'sold'){
		    $gb_name = $gb_name_bought_from;
	    }

	    if($insuranceBox){
		    $price += $insurancePrice;
	    }

	    $company_name = $this->input->post('company_name');


	    if($payment_type == 'invoice'){
	    	//$company_name = $this->input->post('invoice_company_name');
	    	//$seller_name = $this->input->post('invoice_buyer_name');
	    	$number = $this->input->post('invoice_number');
	    	//$cvr = $this->input->post('invoice_cvr');
	    }
/*
	    if($this->input->post('hidden') == 1){
		    $hidden = 1;
	    }else{
		    $hidden = 0;
	    }
*/
      $hidden = $this->input->post('hidden')?$this->input->post('hidden'):0;

	    if($bought_order_id == ''){
		    $bought_order_id = 0;
	    }

	    if($extra_access_to_order_id == ''){
		    $extra_access_to_order_id = 0;
	    }

		if($discount_amount == ''){
		    $discount_amount = 0;
	    }

	   if($exchangeId == ''){
		    $exchangeId = 0;
	    }

	    if($split_cash == ''){
		    $split_cash = '0.00';
	    }

	    if($split_card == ''){
		    $split_card = '0.00';
	    }

	    if($exchangePrice == ''){
		    $exchangePrice = '0.00';
	    }

	    if($insurancePrice == ''){
		    $insurancePrice = '0.00';
	    }

	    if($exchangeBoughtPrice == ''){
		    $exchangeBoughtPrice = '0.00';
	    }

	    if($insuranceYears == ''){
		    $insuranceYears = 0;
	    }

	    if($panserBox == ''){
		    $panserBox = 0;
	    }

	    if($headsetBox == ''){
		    $headsetBox = 0;
	    }

	    if($beskyttelseBox == ''){
		    $beskyttelseBox = 0;
	    }

	    $string = array(
			'name' => $seller_name,
			'imei' => $imei,
      'email' => $email,
			'address' => '',
			'phone_id' => 0,
			'company' => $company_name,
			'color' => $color,
			'seller_id' => $seller_id,
			'number' => $number,
			'seller_email'   => $seller_email,
			'seller_name' => '',
			'seller_number' => '',
			'errors' => $errors,
			//'payment_type' => $payment_type,
			'type' => $type,
			'transfered' => 0,
			'condition' => $condition,
			'price' => $price,
			'reg_nr' => $regnr,
			//'part' => $access_name,
			'part' => '',
			'hidden' => is_array($hidden)?0:$hidden,
			//'part_id' => $access_id,
			'part_id' => 0,
			'webshop_id' => $webshop_id,
			'account_nr' => $account_nr,
			'created_timestamp' => time(),
			'uid' => $this->session->userdata('uid'),
			'gb_id' => $gb,
			'discount' => $discount_amount,
			'subtotal' => $subtotal,
			'exchange' => $exchange,
			'wrong' => 0,
			'bought_from_order_id' => $bought_order_id,
			'sold' => 0,
			'product_id' => 0,
			//'product_id' => $model,
			'legimation_type' => $legimation_type,
			'product' => '',
			//'product' => $model_name,
			'gb' => $gb_name,
			'cvr' => $cvr,
			'withPanser' => $panserBox,
			'withHeadset' => $headsetBox,
			'withFilter' => $beskyttelseBox,
			'exchangeId' => $exchangeId,
			'split_cash' => $split_cash,
			'split_card' => $split_card,
			'exchangePrice' => $exchangePrice,
			'exchangeBoughtPrice' => $exchangeBoughtPrice,
			'extra_access_to_order_id' => $extra_access_to_order_id,
			'already_tested' => 0,
			'fraud' => 0,
			'defect' => 0,
			'creditlineConnectedID' => 0,
			'cancelled' => 0,
			'credit_reason' => '',
			'boutique_id' => $set_boutique_id,
			'insurance' => $insuranceBox,
			'insurance_price' => $insurancePrice,
			'insurance_years' => $insuranceYears,
			'garanti' => $garanti,
      'show_name' => $show_name
		);

    if(is_array($payment_type)){
      if(count($payment_type) == 1){
        $string['payment_type'] = $payment_type[0];
      }
    }else{
      $string['payment_type'] = $payment_type;
    }

		$this->db->insert('orders',$string);

		$inserted_order_id = $this->db->insert_id();

    if($this->input->post('comment') || $this->input->post('image')){
	  if($this->input->post('show_pumping_in_receipt')){
		  if($this->input->post('comment')){
			  $this->db->where('id',$inserted_order_id)->update('orders',array('comment'=>$this->input->post('comment')));
		  }
	  }else{
		   if($this->input->post('image')){
			$image = $this->input->post('image');
			copy("./uploads/_temp/$image","./uploads/comments/$image");
			copy("./uploads/_temp/$image","./uploads/comments/thumbs/$image");
			unlink("./uploads/_temp/$image");
			$this->saveCroppedImage('','','','',"./uploads/comments/thumbs/$image","./uploads/comments/thumbs/$image",200,200);
			$this->resizeImage("./uploads/comments/thumbs/$image",200,200);
		  }
		  $data_comment = array(
			'order_id' => $inserted_order_id,
			'comment' => $this->input->post('comment'),
			'image' => $this->input->post('image'),
			'user_id' => $this->session->userdata('uid'),
			'create_date' => date("Y-m-d H:i:s")
		  );

		  $this->db->insert('order_comment',$data_comment);
	  }

    }

    if($number && $seller_name){
      if(!$this->db->from('customers')->where('phone',$number)->count_all_results()){
          $customer_data = array(
            'name' => $seller_name,
            'email' => $email,
            'phone' => $number,
            'created_timestamp' => date("Y-m-d H:i:s")
          );

          $this->db->insert('customers',$customer_data);
      }
    }
		// update sold order line
		if($type == 'sold'){
			if($this->input->post('hidden') == 1){
				$string = array(
					'sold' => 1,
					'defect' => 1
				);
			}else{
				$string = array(
					'sold' => 1
				);
			}
			$this->db->where('id',$bought_order_id);
			$this->db->update('orders',$string);

		}

		if($type == 'sold'){

			// update inventory
			$this->db->where('boutique_id',$set_boutique_id);
			$this->db->where('product_id',$model);
			$this->db->where('gb',$gb_name);
			$inventory = $this->db->get('inventory')->result();

			if($inventory){
				$new_inventory = $inventory[0]->inventory-1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('inventory',$string);
			}

			redirect('sold?open_receipt='.$inserted_order_id.'');

		}elseif($type == 'access'){


      for($i=0;$i<count($model);$i++){
        // get access
  	    if($access[$i] == 'new_access'){
  		    // create new part
          $stringunique = random_string('alnum', 25);
  		    $string = array(
  		    	'name' => $partName[$i],
  		    	'price' => $partPrice[$i],
            //'discount' => isset($item_discount[$i])?$item_discount[$i]:0,
            //"total_price"=>$partPrice[$i]*$partQty[$i],
  		    	'product_id' => $model[$i],
  		    	'inventory' => $partQty[$i]?$partQty[$i]:1,
  		    	'hide' => 1,
  		    	'part_of_inventory' => 0,
  		    	'part_order' => 0,
  		    	'created_timestamp' => time(),
  		    	'boutique_id' => $this->session->userdata('active_boutique'),
  				'unqiue_string' => $stringunique
  		    );
  		    $this->db->insert('parts',$string);

  		    $access_name = $partName[$i];
  		    $access_id      = $this->db->insert_id();

  	    }elseif($model[$i] == 'TELEFON'){
          $b_order = $this->db->where('id',$access[$i])->where('type','bought')->get('orders')->row();
          $order_item = array(
            "order_id"=>$inserted_order_id,
            "color" => $b_order->color,
            "gb_id" => $b_order->gb_id,
            "gb" => $b_order->gb,
            "product_id"=> $b_order->product_id,
            "product"=>$b_order->product,
            "part_id"=>0,
            "part"=>'',
            "price"=>$partPrice[$i],
            'discount' => isset($item_discount[$i])?$item_discount[$i]:0,
            "qty"=>1,
            "total_price"=>$partPrice[$i],
            "bought_from_order_id" => $b_order->id,
            "imei" => $b_order->imei,
            'total_price_with_discount' => isset($item_discount[$i])?($partPrice[$i] - $partPrice[$i] * $item_discount[$i] /100):$partPrice[$i]
          );

          $this->db->insert("order_item",$order_item);


          // update inventory
    			$this->db->where('boutique_id',$set_boutique_id);
    			$this->db->where('product_id',$b_order->product_id);
    			$this->db->where('gb',$b_order->gb);
    			$inventory = $this->db->get('inventory')->result();

    			if($inventory){
    				$new_inventory = $inventory[0]->inventory-1;
    				$string = array(
    					'inventory' => $new_inventory
    				);
    				$this->db->where('id',$inventory[0]->id);
    				$this->db->update('inventory',$string);
    			}

          $this->db->where('id',$access[$i])->where('type','bought')->update('orders',array('sold'=>1));

        }else{

  		    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
  			$this->db->where('product_id',$model[$i]);
  			$this->db->where('unqiue_string',$access[$i]);
  		    $access_info = $this->db->get('parts')->result();

  		    if($access_info){
  			    $access_name = $access_info[0]->name;
  			    $access_id = $access_info[0]->id;
  		    }else{
  			    $access_name = '';
  			    $access_id = 0;
  		    }

  	    }

        if($model[$i] != 'TELEFON'){
          // get model
    	    $this->db->where('id',$model[$i]);
    	    $product_info = $this->db->get('products')->result();

    	    if($product_info){
    		    $model_name = $product_info[0]->name;
    	    }else{
    		    $model_name = '';
    	    }

          $order_item = array(
            "order_id"=>$inserted_order_id,
            "color" => '',
            "gb_id" => 0,
            "gb" => '',
            "product_id"=>$model[$i],
            "product"=>$model_name,
            "part_id"=>$access_id,
            "part"=>$access_name,
            "price"=>$partPrice[$i],
            'discount' => isset($item_discount[$i])?$item_discount[$i]:0,
            "qty"=>$partQty[$i]?$partQty[$i]:1,
            "total_price"=>$partPrice[$i]*$partQty[$i],
            'total_price_with_discount' => isset($item_discount[$i])?($partPrice[$i]*$partQty[$i] - $partPrice[$i]*$partQty[$i] * $item_discount[$i] /100):($partPrice[$i]*$partQty[$i])
          );

          $this->db->insert("order_item",$order_item);

          // update inventory

    			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
    			$this->db->where('product_id',$model[$i]);
    			$this->db->where('unqiue_string',$access[$i]);
    			$inventory = $this->db->get('parts')->result();

    			if($inventory){
    				if($inventory[0]->part_of_inventory){
    					$new_inventory = $inventory[0]->inventory-($partQty[$i]?$partQty[$i]:1);
    					$string = array(
    						'inventory' => $new_inventory
    					);
    					$this->db->where('id',$inventory[0]->id);
    					$this->db->update('parts',$string);
    				}
    			}
        }


      } //END OF FOR LOOP


      foreach($payment_type as $pk=>$method){
        if(!$payment_amount[$pk]) continue;
        $order_payments = array();
        $order_payments['order_id'] = $inserted_order_id;
        $order_payments['payment_method'] = $method;
        $order_payments['amount'] = $payment_amount[$pk];

        if(is_array($hidden) && in_array($pk,$hidden)){
          $order_payments['hidden'] = 1;
        }else{
          $order_payments['hidden'] = 0;
        }

        $this->db->insert('order_payments',$order_payments);
      }


			if($extra_access == 1 && $extra_access_to_order_id){
				redirect('access/extra/'.$extra_access_to_order_id.'');
			}elseif($extra_access == 1){
				redirect('access/extra/'.$inserted_order_id.'');
			}else{
        return $inserted_order_id;
				//redirect('access?open_receipt='.$inserted_order_id.'');
			}

		}else{

			// update inventory
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$model);
			$this->db->where('gb',$gb_name);
			$inventory = $this->db->get('inventory')->result();


			if($inventory){
				$new_inventory = $inventory[0]->inventory+1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('inventory',$string);
			}else{
				$string = array(
					'color' => 0,
					'condition' => 0,
					'inventory' => 1,
					'product_id' => $model,
					'gb' => $gb_name,
					'boutique_id' => $this->session->userdata('active_boutique'),
					'created_timestamp' => time()
				);
				$this->db->insert('inventory',$string);
			}
			if($this->input->post('unitsFromSamePerson')){
				redirect('bought?open_receipt='.$inserted_order_id.'&cid='.$inserted_order_id.'');
			}else{
				redirect('bought?open_receipt='.$inserted_order_id.'');
			}
		}

    }


    function edit_order_new($type = false, $id = false)
    {

		$webshop_order_id_info = false;

    	$model                 = $this->input->post('model');
	    $gb                    = $this->input->post('gb');
	    $imei                  = $this->input->post('imei');
	    $color                 = $this->input->post('color');

	    $seller_name           = $this->input->post('seller_name');
	    $seller_id             = $this->input->post('seller_id');
	    $seller_email          = $this->input->post('seller_email');

	    $errors                = $this->input->post('errors');
	    $price                 = $this->input->post('price');

	    $regnr                 = $this->input->post('reg_nr');
	    $account_nr            = $this->input->post('account_nr');

	    $bought_order_id       = $this->input->post('bought_order_id');

	    $number                = $this->input->post('number');
	    $buyer_name            = $this->input->post('buyer_name');
      $email            = $this->input->post('email');

	    $webshop_id            = $this->input->post('order_id');

	    $condition             = $this->input->post('condition');

	    $payment_type          = $this->input->post('payment_type');
      $payment_amount          = $this->input->post('payment_amount');

      $discount_amount       = $this->input->post('discount_amount');

		$access                = $this->input->post('access');

		$exchange              = $this->input->post('exchange');

		$company              = $this->input->post('company_name');
    $cvr              = $this->input->post('cvr');

    $access                = $this->input->post('access');
    $partName              = $this->input->post('newAccessName');
	  $partPrice             = $this->input->post('item_pris');
    $item_discount         = $this->input->post('item_discount');
    $partQty = $this->input->post('qty');
    $item_id = $this->input->post('item_id');
    $garanti = $this->input->post('garanti');
    $subtotal = $this->input->post('subtotal');

    if($this->input->post('discount')){
      $discount_amount = $this->input->post('discount');
    }

	    if($type == 'sold'){
		    $this->db->where('id',$id);
		    $get_order_info = $this->db->get('orders')->result();

		    if($get_order_info){
			    $model = $get_order_info[0]->product_id;
			    $gb    = $get_order_info[0]->gb_id;
			    $webshop_order_id_info = $get_order_info[0]->webshop_id;
		    }

		    $seller_name = $buyer_name;

	    }elseif($type == 'access'){
		    $seller_name = $buyer_name;
	    }

		if($payment_type == 'webshop'){
			$number = '';
			$seller_name = '';
		}else{
			$webshop_id = '';
		}

		if($webshop_order_id_info){
			$webshop_id = $webshop_order_id_info;
		}


    if(!$price){
      //$price = array_sum($partPrice);
      $price = 0;
      for($i=0;$i<count($partPrice);$i++){
        $qty = $partQty[$i]?$partQty[$i]:1;
        $unit_price = $partPrice[$i]?$partPrice[$i]:0;
        $item_discount_this = isset($item_discount[$i])?$item_discount[$i]:0;
        $item_discount_amount = ($item_discount_this * $qty * $unit_price)/100;
        $price += (($qty * $unit_price) - $item_discount_amount);
      }
    }

    if($discount_amount){
      // calculate new price
      $discount = $discount_amount/100;
      $newprice = $price*$discount;
      $price = $price-$newprice;
    }



    if(!$subtotal){
      $subtotal = $price;
    }



	    // get gb
	    $this->db->where('id',$gb);
	    $gb_info = $this->db->get('gbs')->result();

	    if($gb_info){
		    $gb_name = $gb_info[0]->name;
	    }else{
		    $gb_name = '';
	    }

	    $string = array(
			'name' => $seller_name,
      'email' => $email,
			'imei' => $imei,
			'company' => $company,
			'color' => $color,
			'seller_id' => $seller_id,
			'number' => $number,
			'condition' => $condition,
			'seller_email'   => $seller_email,
			'errors' => $errors,
			'type' => $type,
			'price' => $price,
			//'payment_type' => $payment_type,
			'reg_nr' => $regnr,
			'webshop_id' => $webshop_id,
			'account_nr' => $account_nr,
			'exchange' => $exchange,
      'discount' => $discount_amount,
      'subtotal' => $subtotal,
			//'part' => $access_name,
			//'part_id' => $access,
			'bought_from_order_id' => $bought_order_id,
			//'product_id' => $model,
			//'product' => $model_name,
			'boutique_id' => $this->session->userdata('active_boutique'),
      'garanti' => $garanti,
      'cvr' => $cvr,
	  'comment' => $this->input->post('comment')
		);

    if(is_array($payment_type)){
      if(count($payment_type) == 1){
        $string['payment_type'] = $payment_type[0];
      }
    }else{
      $string['payment_type'] = $payment_type;
    }

		$this->db->where('id',$id);
		$this->db->update('orders',$string);


		if($type == 'sold'){
			redirect('sold');
		}elseif($type == 'access'){


      for($i=0;$i<count($model);$i++){
        // get access
  	    if($access[$i] == 'new_access'){
  		    // create new part
          $stringunique = random_string('alnum', 25);
  		    $string = array(
  		    	'name' => $partName[$i],
  		    	'price' => $partPrice[$i],
  		    	'product_id' => $model[$i],
  		    	'inventory' => $partQty[$i]?$partQty[$i]:1,
  		    	'hide' => 1,
  		    	'created_timestamp' => time(),
  		    	'boutique_id' => $this->session->userdata('active_boutique'),
            'unqiue_string' => $stringunique,
            'discount' => $item_discount[$i],
            "total_price"=>$partPrice[$i]*$partQty[$i]
  		    );
  		    $this->db->insert('parts',$string);

  		    $access_name = $partName[$i];
  		    $access_id      = $this->db->insert_id();

  	    }else{

  		    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
  			$this->db->where('product_id',$model[$i]);
  			$this->db->where('unqiue_string',$access[$i]);
  		    $access_info = $this->db->get('parts')->result();

  		    if($access_info){
  			    $access_name = $access_info[0]->name;
  			    $access_id = $access_info[0]->id;
  		    }else{
  			    $access_name = '';
  			    $access_id = 0;
  		    }

  	    }

        // get model
  	    $this->db->where('id',$model[$i]);
  	    $product_info = $this->db->get('products')->result();

  	    if($product_info){
  		    $model_name = $product_info[0]->name;
  	    }else{
  		    $model_name = '';
  	    }

        $old_qty = 0;
        $old_qty = $this->db->where('item_id',$item_id[$i])->get("order_item")->row()->qty;
        $bought_from_order_id = $this->db->where('item_id',$item_id[$i])->get("order_item")->row()->bought_from_order_id;

        if($bought_from_order_id){
          $order_item = array(
            "price"=>$partPrice[$i],
            'discount' => $item_discount[$i],
            "total_price"=> $partPrice[$i],
            'total_price_with_discount' => $item_discount[$i]?($partPrice[$i] - $partPrice[$i] * $item_discount[$i] /100):($partPrice[$i])
          );

          $this->db->where('item_id',$item_id[$i])->update("order_item",$order_item);
        }else{
          $order_item = array(
            "product_id"=>$model[$i],
            "product"=>$model_name,
            "part_id"=>$access_id,
            "part"=>$access_name,
            "price"=>$partPrice[$i],
            "qty"=> $partQty[$i]?$partQty[$i]:1,
            'discount' => $item_discount[$i],
            "total_price"=> $partPrice[$i] * $partQty[$i],
            'total_price_with_discount' => $item_discount[$i]?($partPrice[$i]*$partQty[$i] - $partPrice[$i]*$partQty[$i] * $item_discount[$i] /100):($partPrice[$i]*$partQty[$i])
          );

          $this->db->where('item_id',$item_id[$i])->update("order_item",$order_item);

          //New product add or delete can be done later

          // update inventory: Need to change codes as in edit mode, some products can be deleted/changed
          if($old_qty != $partQty[$i]){
            $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
      			$this->db->where('product_id',$model[$i]);
      			$this->db->where('unqiue_string',$access[$i]);
      			$inventory = $this->db->get('parts')->result();

      			if($inventory){
      				if($inventory[0]->part_of_inventory){
      					$new_inventory = $inventory[0]->inventory + $old_qty - ($partQty[$i]?$partQty[$i]:1);
      					$string = array(
      						'inventory' => $new_inventory
      					);
      					$this->db->where('id',$inventory[0]->id);
      					$this->db->update('parts',$string);
      				}
      			}
          }
        }



      } //END OF FOR LOOP

      $this->db->where('order_id',$id)->delete('order_payments');
      foreach($payment_type as $pk=>$method){
        if(!$payment_amount[$pk]) continue;
        $order_payments = array();
        $order_payments['order_id'] = $id;
        $order_payments['payment_method'] = $method;
        $order_payments['amount'] = $payment_amount[$pk];

        $this->db->insert('order_payments',$order_payments);
      }

      return true;
			redirect('access');
		}else{
			redirect('orders/show/'.$id);
		}

    }


    function get_item_names_concat($id){
      $q = $this->db->query("SELECT order_id, GROUP_CONCAT(CONCAT(product, '-', part) SEPARATOR ', ') AS part_name FROM order_item WHERE order_id='$id' GROUP BY order_id");
      if($q->num_rows()){
        return $q->row()->part_name;
      }else{
        return '';
      }
    }

	function check_new_comment($id){
		$this->db->where('order_id',$id);
    $this->db->order_by('create_date','desc');
    $q = $this->db->get('order_comment');
    if($q->num_rows()){
      $row = $q->row_array();
      if(($row['user_id'] != $this->session->userdata('uid')) && ($row['read_date'] == null)){
        return 'new';
      }
    }

    return false;
	}

  function read_unread_comment($id){
		$this->db->where('order_id',$id);
    $this->db->where('read_date IS NULL');
    $this->db->where('user_id !=',$this->session->userdata('uid'));
    $this->db->update('order_comment',array('read_date'=>date("Y-m-d H:i:s")));

    return true;
	}

  function resizeImage($imgsrc, $width, $height, $newimgsrc = "", $maintainratio = TRUE) {

      $this->load->library('image_lib');
      $config['image_library'] = 'gd2';
      $config['source_image'] = $imgsrc;
      if ($newimgsrc) {
          $config['new_image'] = $newimgsrc;
      }
      $config['maintain_ratio'] = $maintainratio;
      $config['width'] = $width;
      $config['height'] = $height;
      $this->image_lib->initialize($config);

      $this->image_lib->resize();
      $this->image_lib->clear();
  }

  function saveCroppedImage($x1, $x2, $y1, $y2, $imgsrc, $newimgsrc, $cropwidth = 100, $cropheight = 100) {
      $CI = & get_instance();
      $data = array();
      $filename = $imgsrc;
      $image_info = getimagesize($filename);
      if ($image_info['mime'] == 'image/png') {
          $image = imagecreatefrompng($filename);
      } else if ($image_info['mime'] == 'image/gif') {
          $image = imagecreatefromgif($filename);
      } else {
          $image = imagecreatefromjpeg($filename);
      }

      $width = imagesx($image);
      $height = imagesy($image);
      if (($x1 == "") && ($y1 == "") && ($x2 == "") && ($y2 == "")) {
          $realrat = $width / $height;
          $croprat = $cropwidth / $cropheight;

          if ($realrat < $croprat) {
              $factor = $width / $cropwidth;
              $cropwidth_new = $width;
              $cropheight_new = $cropheight * $factor;
          } else {
              $factor = $height / $cropheight;
              $cropwidth_new = $cropwidth * $factor;
              $cropheight_new = $height;
          }

          $x1 = $width / 2 - $cropwidth_new / 2;
          $x2 = $width / 2 + $cropwidth_new / 2;
          $y1 = $height / 2 - $cropheight_new / 2;
          $y2 = $height / 2 + $cropheight_new / 2;
      }

      $resized_width = ((int) $x2) - ((int) $x1);
      $resized_height = ((int) $y2) - ((int) $y1);
      //$resized_width = 340;  //We are maintaining the ratio in clientside. Now lets resize to our required size
      // $resized_height = 230;
      $resized_image = imagecreatetruecolor($resized_width, $resized_height);

      //$resized_image = imagecreatetruecolor(340, 230);
      imagecopyresampled($resized_image, $image, 0, 0, (int) $x1, (int) $y1, $width, $height, $width, $height);
      $new_file_name = $newimgsrc;
      imagejpeg($resized_image, $new_file_name);
      //$data['cropped_image'] = $img_name;
      //$data['cropped_image_axis'] = (int)$x1.",".(int)$y1.",".(int)$x2.",".(int)$y2;
      imagedestroy($resized_image);
      return true;
  }

}

// end of model file
