<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('sell_access_overview');
    }
	public function index($extra_id = false)
	{
		ini_set('memory_limit', '-1');

		$data['title'] = 'Tilbehør';
		$this->load->model('device_model');
		if($this->input->post('sold_access')){


			$order_id = $this->device_model->create_order_new('access');

			if($this->input->post('send_email')){
				$this->load->library("Email_manager");
				$this->email_manager->order_notification($order_id);
			}

			redirect('access?open_receipt='.$order_id.'');
		}

		if($extra_id){
			$this->db->where('id',$extra_id);
			$data['access_extra'] = $this->db->get('orders')->result();

			if(!$data['access_extra']){
				redirect('access');
			}

		}else{
			$data['access_extra'] = false;
		}

		$this->load->model('product_model');
		$data['products'] = $this->product_model->get_products();
		$data['access'] = $this->product_model->get_access();

		$data['rank_permissions'] = $this->global_model->get_rank_permissions();

		$data['yield'] = "access/index";
		$this->load->view('layout/application',$data);
	}


	public function edit()
	{

		$id = $this->input->post('id');

		if($this->input->post('edit_access')){

			$this->load->model('device_model');

			$this->device_model->edit_order_new('access',$this->input->post('id'));

			if($this->input->post('send_email')){
				$this->load->library("Email_manager");

				$this->email_manager->order_notification($id);
			}

			redirect('access');
		}

		$this->load->model('product_model');
		$this->load->model('order_model');
		$data['products'] = $this->product_model->get_products();
		$data['access'] = $this->product_model->get_access();

		$data['order'] = $this->order_model->get_order_by_id($id,'access');
		$data['items'] = $this->order_model->get_order_items($id);
		$data['payment_types'] = $this->order_model->get_order_payments($id);
		$this->load->view('access/_edit_new',$data);

	}

	function getInfo(){
		$this->load->model('order_model');

		$id = $this->input->post('order_id');
		$info = $this->order_model->get_order_by_id($id,'access');

		$this->output->set_content_type('application/json')->set_output(json_encode($info));

	}


	function cancel(){

		$id = $this->input->post('line_id');
		$reason = $this->input->post('reason');

		$this->load->model('order_model');
		$data['order_info'] = $this->order_model->get_order_by_id($id);

		if($data['order_info']){

			// get sold phone and remove sold id
			$this->db->where('id',$data['order_info']->bought_from_order_id);
			$bought_order = $this->db->get('orders')->result();

			if($bought_order){
				$string = array(
					'sold' => 0
				);
				$this->db->where('id',$bought_order[0]->id);
				$this->db->update('orders',$string);
			}

			$string = array(
				'cancelled' => 1,
				'bought_from_order_id' => 0
			);
			$this->db->where('id',$id);
			$this->db->update('orders',$string);

			$prevOrderID = $data['order_info']->id;
			// create creditnote
			unset($data['order_info']->id);

			$string = $data['order_info'];
			$this->db->insert('orders',$string);

			$credit_id = $this->db->insert_id();

			$string = array(
				'created_timestamp' => time(),
				'type' => 'credit',
				'imei' => '',
				'cancelled' => 0,
				'bought_from_order_id' => 0,
				'credit_reason' => $reason,
				'uid' => $this->session->userdata('uid'),
				'creditlineConnectedID' => $prevOrderID
			);
			$this->db->where('id',$credit_id);
			$this->db->update('orders',$string);

			// update inventory

			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$this->db->where('product_id',$data['order_info']->product_id);
			$this->db->where('id',$data['order_info']->part_id);
			$inventory = $this->db->get('parts')->result();

			if($inventory){
				$new_inventory = $inventory[0]->inventory+1;
				$string = array(
					'inventory' => $new_inventory
				);
				$this->db->where('id',$inventory[0]->id);
				$this->db->update('parts',$string);
			}

		}

		redirect('access');

	}

	function select_gbs(){
		  $product_id = $this->input->post('product_id');
			$access_id = $this->input->post('access_id');
			$this->load->model('product_model');
		  $accessories = $this->product_model->get_access($product_id,false,$access_id);
				echo '<option value="">-</option>';
			foreach ($accessories as $access):
					if($access_id && ($access_id == $access->id)){
						$selected = "selected='selected'";
					}else{
						$selected = "";
					}
					echo '<option value="'.$access->unqiue_string.'" class="'.$access->product_id.'" '.$selected.'>'.$access->name.'</option>';

			endforeach;
				echo '<option value="new_access" class="'.$product_id.'">--- Opret nyt tilbehør ---</option>';
	}

	function select_bought_phone_ids(){
		  	$orq = $this->db->select('id')->where('type','bought')->where("sold",0)->where('fraud',0)->where('defect',0)->where('boutique_id',$this->session->userdata('active_boutique'))->get('orders');

				echo '<option value="">-</option>';
				if($orq->num_rows()):
			foreach ($orq->result() as $row):
					echo '<option value="'.$row->id.'">'.$row->id.'</option>';

			endforeach;
		endif;

	}

	function migrate_order_items_old(){
		set_time_limit(600);
			$this->db->where("(SELECT COUNT(*) FROM order_item WHERE order_item.order_id=orders.id) = 0");
			$q = $this->db->get('orders');
			if($q->num_rows()){
				foreach($q->result() as $order){
					 $this->db->from('order_item');
					 $this->db->where('order_id',$order->id);
					 if(!$this->db->count_all_results()){
						 $order_item = array();
						 $order_item = array(
							 "order_id"=> $order->id,
							 "product_id"=>$order->product_id,
							 "product"=>$order->product,
							 "part_id"=>$order->part_id,
							 "part"=>$order->part,
							 "price"=>$order->price
						 );
						 $this->db->insert('order_item',$order_item);
					 }
				}
			}
	}


	public function test_email_view($order_id){
		$this->load->library("Email_manager");
		$this->email_manager->order_notification($order_id);
	}

	public function order_comment_modal(){
		$this->load->model('order_model');
		$data = array();
		$data['id'] = $this->input->post('id');
		$data['comments'] = $this->order_model->get_order_comments($data['id']);

		$this->load->model('device_model');
		$this->device_model->read_unread_comment($data['id']);

		$this->load->view('access/order_comment_modal',$data);
	}

	public function comment_save(){
		if($this->input->post('id') && ($this->input->post('comment') || $this->input->post('image'))){
			$this->load->model('order_model');

			$id = $this->order_model->comment_save();

			$image = $this->input->post('image');

			if($image){
				copy("./uploads/_temp/$image","./uploads/comments/$image");
				copy("./uploads/_temp/$image","./uploads/comments/thumbs/$image");
				unlink("./uploads/_temp/$image");
				$this->saveCroppedImage('','','','',"./uploads/comments/thumbs/$image","./uploads/comments/thumbs/$image",200,200);
				$this->resizeImage("./uploads/comments/thumbs/$image",200,200);
			}

			$comment = $this->order_model->get_order_comment($id);

			echo '<li class="left clearfix">'.
				'<div class="chat-body clearfix">'.
					'<div class="header">'.
						'<strong class="primary-font">'.$comment->user_name.'</strong> <small class="pull-right text-muted">'.
							'<span class="glyphicon glyphicon-time"></span>'.date("d M Y H:i A",strtotime($comment->create_date)).'</small>'.
					'</div>'.
					'<p>'.$comment->comment.'</p>';

			if($comment->image){
				echo '<p>'.
							'<a href="'.base_url('uploads/comments/'.$comment->image).'" class="popup">'.
							'<img src="'.base_url('uploads/comments/thumbs/'.$comment->image).'" style="width: 100px;border: 1px solid #ccc; padding: 3px;margin-top: 5px;" />'.
							'</a>'.
						'</p>';
			}

			echo '</div></li>';
/*
			echo '<li class="list-group-item">'.
				'<span style="font-size: 12px; ">'.
				date("d M Y H:i A",strtotime($comment->create_date)).' | '.$comment->user_name.' </span>'.
				'<br>'.
				$comment->comment
			.'</li>';
		*/
		}
	}

	function comment_image_temp_upload(){
		$response = $this->uploadImage("order_comment_image");
		echo json_encode($response);
	}

	function comment_image_temp_remove(){
		$file = $this->input->post('file');
		unlink("./uploads/_temp/$file");
	}

	function uploadImage($field_name = "userfile", $file_name = "", $folder = "_temp/", $max_width = "6000", $max_height = "6000", $min_width = "200", $min_height = "200") {

		$config['upload_path'] = './uploads/' . $folder;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '15360'; //15MB
		$config['max_width'] = $max_width;
		$config['max_height'] = $max_height;
		$config['min_width'] = $min_width;
		$config['min_height'] = $min_height;
		if ($file_name) {
			$config['file_name'] = $file_name;
		} else {
			$config['encrypt_name'] = TRUE;
		}


		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($field_name)) {

			return array('error' => $this->upload->display_errors());
		} else {

			$upload_data = $this->upload->data();
			if($upload_data['image_width'] > 3000 || $upload_data['image_height'] > 3000){
			  $this->resizeImage($upload_data['full_path'], 3000, 3000);
			}

			return array('upload_data' => $upload_data);
		}
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


	public function sync_order_payments(){
		$q = $this->db->get('orders');
		if($q->num_rows()){
			foreach($q->result_array() as $row){
				if(!$row['payment_type']) continue;
				if($this->db->from('order_payments')->where('order_id',$row['id'])->count_all_results()) continue;

				$order_payments = array();
        $order_payments['order_id'] = $row['id'];
        $order_payments['payment_method'] = $row['payment_type'];
        $order_payments['amount'] = $row['price'];

        $this->db->insert('order_payments',$order_payments);
			}
		}
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
