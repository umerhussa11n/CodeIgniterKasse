<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_products()
    {
    	$this->db->where('active',1);
        $this->db->order_by('prod_order','asc');
        $query = $this->db->get('products');
        return $query->result();
    }

    function get_product_by_id($id = false)
    {
    	$this->db->where('id',$id);
    	$this->db->where('active',1);
        $query = $this->db->get('products')->result();
        if($query){
	        return $query[0];
        }else{
	        return false;
        }
    }

	function get_colors_to_product($id){

		$this->db->order_by('name','asc');
		$this->db->where('product_id',$id);
        $query = $this->db->get('colors')->result();
        return $query;

	}

	function get_gbs_to_product($id){

		$this->db->order_by('name','asc');
		$this->db->where('product_id',$id);
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
        $query = $this->db->get('gbs')->result();
        return $query;

	}

	function get_gbs(){

		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
        $query = $this->db->get('gbs')->result();
        return $query;

	}


	function get_access($product_id = false,$show_all = false,$access_id=false){
    if($show_all !== true){   
	  if($access_id){
		  $this->db->where("(id = '$access_id' OR hide = 0)");
	  }else{
		  $this->db->where('hide',0);
	  }
    }else{
      $this->db->where('unqiue_string !=','');
    }

		if($product_id){
			$this->db->where('product_id',$product_id);
		}
		if($this->session->userdata('active_boutique')){
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}
		//$this->db->where('part_of_inventory',1);
		$this->db->group_by('unqiue_string');
                $this->db->order_by('part_order','asc');
        $query = $this->db->get('parts')->result();
        return $query;

	}

	function get_categories(){

        $query = $this->db->get('categories')->result();
        return $query;

	}

	function get_conditions_to_product($id){
		$this->db->where('product_id',$id);
		$this->db->order_by('name','asc');
        $query = $this->db->get('conditions')->result();
        return $query;
	}


	function add_color($id){
		$string = array(
			'color_code'        => $this->input->post('color_color_code'),
			'name'              => $this->input->post('color_name'),
			'price'             => $this->input->post('color_price'),
			'product_id'        => $id,
			'created_timestamp' => time()
		);
		$this->db->insert('colors',$string);

		redirect('products/show/'.$id);

	}


	function add_gb($id){

		$string = array(
			'name'              => $this->input->post('gb_name'),
			'price'             => $this->input->post('gb_price'),
			'product_id'        => $id,
			'created_timestamp' => time()
		);
		$this->db->insert('gbs',$string);

		redirect('products/show/'.$id);

	}


	function add_condition($id){
		$string = array(
			'name'              => $this->input->post('condition_type'),
			'price'             => $this->input->post('condition_price'),
			'product_id'        => $id,
			'created_timestamp' => time()
		);
		$this->db->insert('conditions',$string);

		redirect('products/show/'.$id);

	}

	function update_product($id){

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		// get info
		$this->db->where('id',$id);
		$sql = $this->db->get('products')->result();

		if($sql){
			$current_url = $sql[0]->url;
		}else{
			$current_url = '';
		}

		$this->form_validation->set_rules('name', 'Navn', 'required');
		if($current_url == $this->input->post('url')){
			$this->form_validation->set_rules('url', 'URL', 'required');
		}else{
			$this->form_validation->set_rules('url', 'URL', 'required|is_unique[products.url]');
		}
		$this->form_validation->set_rules('price', 'Pris', 'required|numeric');
		$this->form_validation->set_rules('description', 'Beskrivelse', 'required');
		$this->form_validation->set_rules('category', 'Kategori', 'required');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{

	        $name = $this->input->post('name');
	        $price = $this->input->post('price');
	        $before_price = $this->input->post('before_price');
	        $description = $this->input->post('description');
	        $category = $this->input->post('category');
	        $url = $this->input->post('url');
	        $seo = $this->input->post('seo');
	        $hide_from_overview = $this->input->post('hide_from_overview');
	        $extra_product = $this->input->post('extra_product');

	        $seo_title = $this->input->post('seo_title');
	        $seo_description = $this->input->post('seo_description');

	        $string = array(
	        	'name'           => $name,
	        	'url'            => $url,
	        	'before_price'   => $before_price,
	        	'seo'            => $seo,
	        	'extra_product'  => $extra_product,
	        	'hide_from_overview' => $hide_from_overview,
	        	'seo_title'      => $seo_title,
	        	'seo_description' => $seo_description,
	        	'description'    => $description,
	        	'price'          => $price,
	        	'category'       => $category
	        );
	        $this->db->where('id',$id);
	        $this->db->update('products',$string);

			$this->global_model->log_action('product_updated',$id);

	        redirect('products/show/'.$id);

	    }

	}


	function create_product(){

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Navn', 'required');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{

	        $name = $this->input->post('name');
			$gbs  = $this->input->post('gbs');

	        $string = array(
	        	'name'              => $name,
	        	'boutique_id'       => $this->session->userdata('active_boutique'),
	        	'created_timestamp' => time()
	        );
	        $this->db->insert('products',$string);

	       	$id = $this->db->insert_id();

	       	foreach($gbs as $gb){

	       		$this->load->model('boutique_model');
				$boutiques = $this->boutique_model->get();

				foreach($boutiques as $boutique){

					$string = array(
			        	'name'              => $gb,
			        	'product_id'        => $id,
			        	'boutique_id'       => $boutique->id,
			        	'created_timestamp' => time()
			        );
			        $this->db->insert('gbs',$string);

				}
			}

	       	$this->global_model->log_action('product_created',$id);

	        redirect('products');

	    }

	}



	function edit($id){

		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Navn', 'required');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

		if ($this->form_validation->run() == TRUE)
		{

        	$i = 0;

	        $name = $this->input->post('name');
	        $control_prices = $this->input->post('control_prices');
			$gbs  = $this->input->post('gbs');

			$sell_defect = $this->input->post('sell_defect');
			$sell_worn = $this->input->post('sell_worn');
			$sell_good_condition = $this->input->post('sell_good_condition');
			$sell_new = $this->input->post('sell_new');

			$buy_new = $this->input->post('buy_new');

	        $string = array(
	        	'name'              	=> $name,
	        	'sell_defect' 			=> $sell_defect?$sell_defect:0,
	        	'sell_worn' 			=> $sell_worn?$sell_worn:0,
	        	'control_prices'		=> $control_prices?$control_prices:0,
	        	'sell_good_condition' 	=> $sell_good_condition?$sell_good_condition:0,
	        	'sell_new'				=> $sell_new?$sell_new:0,
	        	'buy_new'		    	=> $buy_new?$buy_new:0
	        );
	        $this->db->where('id',$id);
	        $this->db->update('products',$string);

	       	foreach($gbs as $gb){

	       		$this->load->model('boutique_model');
				$boutiques = $this->boutique_model->get();

				$used_price 	= $_POST['used_price'][$i];
				$new_price 		= $_POST['new_price'][$i];

				foreach($boutiques as $boutique){

					$this->db->where('name',$gb);
					$this->db->where('product_id',$id);
					$this->db->where('boutique_id',$boutique->id);
					$gbs_info = $this->db->get('gbs')->result();

					$string = array(
			        	'name'              => $gb,
			        	'product_id'        => $id,
			        	'used_price'  		=> $used_price?$used_price:0,
			        	'new_price'		 	=> $new_price?$new_price:0,
			        	'boutique_id'       => $boutique->id,
			        	'created_timestamp' => time()
			        );

			        if(!$gbs_info){
			        	$this->db->insert('gbs',$string);
			        }else{
				        $this->db->where('id',$gbs_info[0]->id);
				        $this->db->update('gbs',$string);
			        }

				}

				$i++;

			}

	        $this->global_model->log_action('product_updated',$id);

	        redirect('products');

	    }

	}


	function upload_image_to_color($id){

		$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '15034340';
		$config['max_width']  = '2500';
		$config['max_height']  = '2800';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			print_r($this->upload->display_errors());
		}
		else
		{
			$data = $this->upload->data();

			$file_name = $data['file_name'];

			$string = array(
				'image' => $file_name
			);
			$this->db->where('id',$this->input->post('color_id'));
			$this->db->update('colors',$string);

			$this->global_model->log_action('uploaded_image_to_color',$this->input->post('color_id'));

			redirect('products/show/'.$id);


		}

	}

	function get_sales($status = false){

		$this->db->where('order_type','bought');
		if($status){
			$this->db->where('status',$status);
		}else{
			$this->db->where('status !=','waiting');
		}
		$this->db->order_by('id','desc');
		$sql = $this->db->get('orders')->result();

		return $sql;

	}

	function get_sales_in_period($start = false,$end = false){

		$this->db->where('order_type','bought');
		$this->db->where('status','completed');
		$this->db->where('sent_timestamp >=',$start);
		$this->db->where('sent_timestamp <=',$end);
		$this->db->order_by('id','desc');
		$sql = $this->db->get('orders')->result();

		return $sql;

	}

	function get_purchased($payment_type){

		if($payment_type == 'label_sent'){

			$days_diff = strtotime("-16 days");

			$this->db->where('label_timestamp >',$days_diff);
			$this->db->where('label_timestamp !=',0);
			$this->db->where('status','waiting');
		}elseif($payment_type == 'label_over'){

			$days_diff = strtotime("-16 days");

			$this->db->where('label_timestamp <',$days_diff);
			$this->db->where('label_timestamp !=',0);
			$this->db->where('status','waiting');
		}elseif($payment_type == 'transfered'){
			$this->db->where('transfered_timestamp >',0);
			$this->db->where('status','completed');
		}elseif($payment_type == 'sold'){
			$this->db->where('transfered_timestamp >',0);
		}elseif($payment_type){
			$this->db->where('status',$payment_type);
		}else{
			$this->db->where('status','waiting');
			$this->db->where('label_timestamp',0);
		}

		$this->db->where('order_type','sell');
		$this->db->order_by('id','desc');
		$sql = $this->db->get('orders')->result();

		return $sql;

	}

}

// end of model file
