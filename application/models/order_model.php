<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_order_by_id($id = false,$type = false,$boutique = true,$all_scenarios = false)
    {
    	$this->db->where('id',$id);
    	if($type){
	    	$this->db->where('type',$type);
    	}
    	if($boutique){
    	$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
    	}
    	if(!$all_scenarios){
    	$this->db->where('defect',0);
    	$this->db->where('fraud',0);
    	}
    	$this->db->where('hidden',0);
        $query = $this->db->get('orders')->result();
        if($query){
	        return $query[0];
        }else{
	        return false;
        }
    }


    function update_parts_used($id){

	    $order_info = $this->get_order_by_id($id);

	    if($order_info){

		    $used_parts = $this->input->post('used_parts');

		    $this->db->where('order_id',$order_info->id);
		    $current_parts = $this->db->get('parts_used')->result();

		    foreach($current_parts as $c_parts){
			    // update inventory
			    $this->db->where('id',$c_parts->part_id);
			    $part_info = $this->db->get('parts')->result();

			    if($part_info){

			    	$new_inventory = $part_info[0]->inventory+1;

				    $string = array(
				    	'inventory' => $new_inventory
				    );
				    $this->db->where('id',$part_info[0]->id);
				    $this->db->update('parts',$string);
			    }
		    }

		    $this->db->where('order_id',$id);
		    $this->db->delete('parts_used');

		    if($used_parts){

		    foreach($used_parts as $part){

			    $string = array(
			    	'product_id' => $order_info->product_id,
			    	'part_id' => $part,
			    	'orderline_id' => 0,
			    	'order_id' => $id,
			    	'created_timestamp' => time(),
			    	'boutique_id' => $this->session->userdata('active_boutique')
			    );
			    $this->db->insert('parts_used',$string);

			    // update inventory
			    $this->db->where('id',$part);
			    $this->db->where('part_of_inventory',1);
			    $part_info = $this->db->get('parts')->result();

			    if($part_info){

			    	$new_inventory = $part_info[0]->inventory-1;

				    $string = array(
				    	'inventory' => $new_inventory
				    );
				    $this->db->where('id',$part_info[0]->id);
				    $this->db->update('parts',$string);
			    }


		    }

		    }

	    }

	    redirect('orders/show/'.$id);

    }


    function test_complete($order_id){


	    $signal 		= $this->input->post('signal');
	    $lcd 			= $this->input->post('lcd');
	    $microphone 	= $this->input->post('microphone');

	    $wifi 			= $this->input->post('wifi');
	    $earpiece 		= $this->input->post('earpiece');
	    $buttons 		= $this->input->post('buttons');

	    $digitizer 		= $this->input->post('digitizer');
	    $speaker 		= $this->input->post('speaker');
	    $chargin 		= $this->input->post('chargin');

	    $onoff 			= $this->input->post('onoff');
	    $vibrate 		= $this->input->post('vibrate');
	    $frontcamera 	= $this->input->post('frontcamera');

	    $backcamera 	= $this->input->post('backcamera');
	    $proximity_sensor = $this->input->post('proximity_sensor');
	    $volume 		= $this->input->post('volume');

	    $touchid 		= $this->input->post('touchid');
	    $simtray 		= $this->input->post('simtray');
	    $screw 			= $this->input->post('screw');

	    $battery 		= $this->input->post('battery');
	    $battery_cycles = $this->input->post('battery_cycles');
	    $battery_design = $this->input->post('battery_design');
	    $battery_fullcharge = $this->input->post('battery_fullcharge');


	    $string = array(
	    	'signal' => $signal,
	    	'lcd' => $lcd,
	    	'microphone' => $microphone,
	    	'wifi' => $wifi,
	    	'earpiece' => $earpiece,
	    	'buttons' => $buttons,
	    	'digitizer' => $digitizer,
	    	'speaker' => $speaker,
	    	'chargin' => $chargin,
	    	'onoff' => $onoff,
	    	'vibrate' => $vibrate,
	    	'frontcamera' => $frontcamera,
	    	'backcamera' => $backcamera,
	    	'proximity_sensor' => $proximity_sensor,
	    	'volume' => $volume,
	    	'touchid' => $touchid,
	    	'simtray' => $simtray,
	    	'screw' => $screw,
	    	'battery' => $battery,
	    	'battery_cycles' => $battery_cycles,
	    	'battery_design' => $battery_design,
	    	'battery_fullcharge' => $battery_fullcharge,
	    	'uid' => $this->session->userdata('uid'),
	    	'created_timestamp' => time(),
	    	'order_id' => $order_id
	    );
	    $this->db->insert('tests',$string);

	    $id = $this->db->insert_id();

	    $this->global_model->log_action('test_completed',$id);

	    redirect('export/print_test/'.$order_id.'/'.$id);

    }


	function create_comment($id){

		$comment = $this->input->post('comment_text');

		$string = array(
			'comment' => $comment,
			'created_timestamp' => time(),
			'uid' => $this->session->userdata('uid'),
			'order_id' => $id,
			'active' => 1
		);
		$this->db->insert('comments',$string);

		$id = $this->db->insert_id();

		$this->global_model->log_action('comment_created',$id);

		redirect('orders/show/'.$id.'#intern_comments');

	}

  function get_order_items($order_id){
    $this->db->where('order_id',$order_id);
    $q = $this->db->get('order_item');
    if($q->num_rows()){
      return $q->result_array();
    }else{
      return false;
    }
  }

  function get_order_comments($order_id){
    $this->db->select('order_comment.*,users_kasse.name as user_name');
    $this->db->from('order_comment');
    $this->db->join('users_kasse','users_kasse.id = order_comment.user_id','left');
    $this->db->where('order_id',$order_id);
    $q = $this->db->get();
    if($q->num_rows()){
      return $q->result();
    }

    return false;
  }

  function get_order_comment($id){
    $this->db->select('order_comment.*,users_kasse.name as user_name');
    $this->db->from('order_comment');
    $this->db->join('users_kasse','users_kasse.id = order_comment.user_id','left');
    $this->db->where('order_comment.id',$id);
    $q = $this->db->get();
    if($q->num_rows()){
      return $q->row();
    }

    return false;
  }

  function comment_save(){
    $data['order_id'] = $this->input->post('id');
    $data['comment'] = $this->input->post('comment');
	$data['image'] = $this->input->post('image');
    $data['user_id'] = $this->session->userdata('uid');
    $data['create_date'] = date("Y-m-d H:i:s");
    $this->db->insert('order_comment',$data);

    return $this->db->insert_id();
  }

  function get_order_payments($order_id){
    $this->db->where('order_id',$order_id);
    return $this->db->get('order_payments');
  }

}

// end of model file
