<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boutique_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get(){
    	$this->db->order_by('id','desc');
    	$this->db->where('active',1);
	    $b = $this->db->get('boutiques')->result();
	    return $b;
    }


    function get_by_id($id){
    	$this->db->order_by('id','desc');
    	$this->db->where('id',$id);
    	$this->db->where('active',1);
	    $b = $this->db->get('boutiques')->result();
	    if($b){
	    	return $b[0];
	    }else{
			return false;
	    }
    }


    function get_name_by_id($id){
    	$this->db->order_by('id','desc');
    	$this->db->where('id',$id);
    	$this->db->where('active',1);
	    $b = $this->db->get('boutiques')->result();
	    if($b){
	    	return $b[0]->name;
	    }else{
			return false;
	    }
    }


	function create(){

	    $name            = $this->input->post('name');
	    $address         = $this->input->post('address');
	    $initial         = $this->input->post('initial');

	    $tlcvremail      = $this->input->post('tlcvremail');

      $smtp_username      = $this->input->post('smtp_username');
      $smtp_password      = $this->input->post('smtp_password');
      $smtp_host      = $this->input->post('smtp_host');
      $smtp_port      = $this->input->post('smtp_port');

	    $string = array(
			'name' => $name,
			'initial' => $initial,
			'address' => $address,
			'active' => 1,
			'rank' => '',
			'tlcvremail' => $tlcvremail,
			'created_timestamp' => time(),
      'smtp_username' => $smtp_username,
      'smtp_password' => $smtp_password,
      'smtp_host' => $smtp_host,
      'smtp_port' => $smtp_port
		);
		$this->db->insert('boutiques',$string);

		$id = $this->db->insert_id();


		$get_gbs = $this->db->get('gbs')->result();

		foreach($get_gbs as $gbinfo){

			$this->db->where('name',$gbinfo->name);
			$this->db->where('boutique_id',$id);
			$this->db->where('product_id',$gbinfo->product_id);
			$check_exist = $this->db->get('gbs')->result();

			if(!$check_exist){

			$string = array(
	        	'name'              => $gbinfo->name,
	        	'price' => '0.00',
	        	'default' => 0,
	        	'new_price' => '0.00',
	        	'used_price' => '0.00',
	        	'product_id'        => $gbinfo->product_id,
	        	'boutique_id'       => $id,
	        	'created_timestamp' => time()
	        );
	        $this->db->insert('gbs',$string);

	        }

		}

		$this->db->group_by('unqiue_string');
		$this->db->where('unqiue_string !=','');
		$this->db->where('hide',0);
		$parts = $this->db->get('parts')->result();

		foreach($parts as $part){

			$string = array(
				'name' => $part->name,
				'inventory' => 0,
				'price' => $part->price,
				'product_id' => $part->product_id,
				'created_timestamp' => time(),
				'boutique_id' => $id,
				'hide' => $part->hide,
				'unqiue_string' => $part->unqiue_string,
				'part_of_inventory' => $part->part_of_inventory,
				'part_order' => 0
			);
			$this->db->insert('parts',$string);

		}

		$this->global_model->log_action('boutique_created',$id);

		redirect('boutiques');

    }


    function edit($id){

	    $name            = $this->input->post('name');
	    $address         = $this->input->post('address');
	    $initial         = $this->input->post('initial');

	    $tlcvremail      = $this->input->post('tlcvremail');

      $smtp_username      = $this->input->post('smtp_username');
      $smtp_password      = $this->input->post('smtp_password');
      $smtp_host      = $this->input->post('smtp_host');
      $smtp_port      = $this->input->post('smtp_port');

	    $string = array(
  			'name' => $name,
  			'initial' => $initial,
  			'address' => $address,
  			'tlcvremail' => $tlcvremail,
        'smtp_username' => $smtp_username,
        'smtp_password' => $smtp_password,
        'smtp_host' => $smtp_host,
        'smtp_port' => $smtp_port
  		);
  		$this->db->where('id',$id);
  		$this->db->update('boutiques',$string);

  		$this->global_model->log_action('boutique_update',$id);

  		redirect('boutiques');

    }

    function create_counting(){

	    $date               = $this->input->post('date');

	    $dankort      		= $this->input->post('dankort');
	    $danskeecmcvi      	= $this->input->post('danskeecmcvi');
	    $udlexmxvijcb      	= $this->input->post('udlexmxvijcb');
	    $gebyr		      	= $this->input->post('gebyr');

	    $halvore      		= $this->input->post('halvore');
	    $enkr      			= $this->input->post('enkr');
	    $tokr      			= $this->input->post('tokr');
	    $femkr      		= $this->input->post('femkr');
	    $tikr      			= $this->input->post('tikr');
	    $tyvekr      		= $this->input->post('tyvekr');
	    $halvtredskr      	= $this->input->post('halvtredskr');
	    $hundkr     		= $this->input->post('hundkr');
	    $tohundkr      		= $this->input->post('tohundkr');
	    $femhundkr      	= $this->input->post('femhundkr');
	    $tusindkr      		= $this->input->post('tusindkr');

	    $to_bank            = $this->input->post('to_bank');

	    $to_bank 			= str_replace(",", ".", $to_bank);

	    $dankort 		= str_replace(",", ".", $dankort);
	    $danskeecmcvi 	= str_replace(",", ".", $danskeecmcvi);
	    $udlexmxvijcb 	= str_replace(",", ".", $udlexmxvijcb);
	    $gebyr 			= str_replace(",", ".", $gebyr);

	    $array = array(
	    	'cardInfo' => array(
	    		'dankort' => $dankort,
	    		'danskeecmcvi' => $danskeecmcvi,
	    		'udlexmxvijcb' => $udlexmxvijcb,
	    		'gebyr' => $gebyr
	    	),
	    	'cashInfo' => array(
	    		'halvore' => $halvore,
	    		'enkr' => $enkr,
	    		'tokr' => $tokr,
	    		'femkr' => $femkr,
	    		'tikr' => $tikr,
	    		'tyvekr' => $tyvekr,
	    		'halvtredskr' => $halvtredskr,
	    		'hundkr' => $hundkr,
	    		'tohundkr' => $tohundkr,
	    		'femhundkr' => $femhundkr,
	    		'tusindkr' => $tusindkr
	    	)
	    );

	    $info = json_encode($array);

	    if($date){

		    $start = strtotime($date.' 00:00:00');
			$end = strtotime($date.' 23:59:59');

			$created_timestamp = strtotime($date);

	    }else{
		    $start = strtotime(date("Y-m-d").' 00:00:00');
		    $end = strtotime(date("Y-m-d").' 23:59:59');

			$created_timestamp = time();

	    }

	    // CALCULATE ULTIMO

	    /*$this->db->where('created_timestamp <',time());
	    $this->db->limit(1);
	    $kasseafstemning = $this->db-get('kasseafstemning')->result();

	    print_r($kasseafstemning);
	    exit;
	    */
	    /*$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $this->db->where('uid',$this->session->userdata('uid'));
	    $this->db->where('created_timestamp >= ',$start);
	    $this->db->where('created_timestamp <= ',$end);
	    $counting = $this->db->get('kasseafstemning')->result();

	    if($counting){

		    $string = array(
		    	'info' => $info,
		    	'to_bank' => $to_bank
		    );
		    $this->db->where('id',$counting[0]->id);
		    $this->db->update('kasseafstemning',$string);

		    $id = $counting[0]->id;

	    }*/


	    // get last counting
	    $this->db->order_by('id','desc');
	    $this->db->limit(1);
	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $last_boutique_id = $this->db->get('kasseafstemning')->result();

	    if($last_boutique_id){
		    $unique_id = $last_boutique_id[0]->unique_id+1;
	    }else{
		    $unique_id = 1;
	    }

	    $string = array(
	    	'info' => $info,
	    	'boutique_id' => $this->session->userdata('active_boutique'),
	    	'uid' => $this->session->userdata('uid'),
	    	'created_timestamp' => time(),
	    	'webshop' => 0,
	    	'ultimo' => '0.00',
	    	'to_bank' => $to_bank,
	    	'unique_id' => $unique_id
	    );
	    $this->db->insert('kasseafstemning',$string);

	    $id = $this->db->insert_id();

	    $this->global_model->log_action('counting_created',$id);

	    redirect('status/generate/'.$id);

    }


    function edit_counting(){

	    $dankort      		= $this->input->post('dankort');
	    $danskeecmcvi      	= $this->input->post('danskeecmcvi');
	    $udlexmxvijcb      	= $this->input->post('udlexmxvijcb');
	    $gebyr		      	= $this->input->post('gebyr');

	    $halvore      		= $this->input->post('halvore');
	    $enkr      			= $this->input->post('enkr');
	    $tokr      			= $this->input->post('tokr');
	    $femkr      		= $this->input->post('femkr');
	    $tikr      			= $this->input->post('tikr');
	    $tyvekr      		= $this->input->post('tyvekr');
	    $halvtredskr      	= $this->input->post('halvtredskr');
	    $hundkr     		= $this->input->post('hundkr');
	    $tohundkr      		= $this->input->post('tohundkr');
	    $femhundkr      	= $this->input->post('femhundkr');
	    $tusindkr      		= $this->input->post('tusindkr');

	    $to_bank            = $this->input->post('to_bank');


	    $dankort 		= str_replace(",", ".", $dankort);
	    $danskeecmcvi 	= str_replace(",", ".", $danskeecmcvi);
	    $udlexmxvijcb 	= str_replace(",", ".", $udlexmxvijcb);
	    $gebyr 			= str_replace(",", ".", $gebyr);

	    $id                 = $this->input->post('id');

	    $array = array(
	    	'cardInfo' => array(
	    		'dankort' => $dankort,
	    		'danskeecmcvi' => $danskeecmcvi,
	    		'udlexmxvijcb' => $udlexmxvijcb,
	    		'gebyr' => $gebyr
	    	),
	    	'cashInfo' => array(
	    		'halvore' => $halvore,
	    		'enkr' => $enkr,
	    		'tokr' => $tokr,
	    		'femkr' => $femkr,
	    		'tikr' => $tikr,
	    		'tyvekr' => $tyvekr,
	    		'halvtredskr' => $halvtredskr,
	    		'hundkr' => $hundkr,
	    		'tohundkr' => $tohundkr,
	    		'femhundkr' => $femhundkr,
	    		'tusindkr' => $tusindkr
	    	)
	    );

	    $info = json_encode($array);

	    $string = array(
	    	'info' => $info,
	    	'to_bank' => $to_bank
	    );
	    $this->db->where('id',$id);
	    $this->db->update('kasseafstemning',$string);

	    $this->global_model->log_action('counting_updated',$id);

	    redirect('status/generate/'.$id);

    }


    function create_phone_counting(){

	    $boutique_info = $this->get_by_id($this->session->userdata('active_boutique'));

	    $this->db->where('id',$this->session->userdata('uid'));
	    $userinfo  = $this->db->get('users_kasse')->result();

	    if($userinfo){
		    $username = $userinfo[0]->name;
	    }else{
		    $username = '?';
	    }

	    $mail_info = '';

	    $on_inventory_list = array();
	    $not_on_inventory_list = array();

	    $this->load->model('inventory_model');

	    $checked = $this->input->post('on_inventory');

	    $phones = $this->inventory_model->get_phones_in_boutique($this->session->userdata('active_boutique'));

	    foreach($phones as $phone){

	    	if(in_array($phone->id,$checked)){
	    		$on_inventory_list[$phone->id] = array(
	    			'imei' => $phone->imei,
	    			'name' => $phone->product.', '.$phone->gb.'GB, '.$phone->color,
	    			'id' => $phone->id
	    		);
	    	}else{
		    	$not_on_inventory_list[$phone->id] = array(
	    			'imei' => $phone->imei,
	    			'name' => $phone->product.', '.$phone->gb.'GB, '.$phone->color,
	    			'id' => $phone->id
	    		);

	    		$mail_info = $mail_info.'#'.$phone->id.'<br />';

	    	}

	    }

	    $on_inventory_list = json_encode($on_inventory_list);
	    $not_on_inventory_list = json_encode($not_on_inventory_list);

	    // get last counting
	    $this->db->order_by('id','desc');
	    $this->db->limit(1);
	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $last_boutique_id = $this->db->get('telefonafstemning')->result();

	    if($last_boutique_id){
		    $unique_id = $last_boutique_id[0]->unique_id+1;
	    }else{
		    $unique_id = 1;
	    }

	    $string = array(
	    	'info' => $on_inventory_list,
	    	'missing_phones' => $not_on_inventory_list,
	    	'created_timestamp' => time(),
	    	'uid' => $this->session->userdata('uid'),
	    	'boutique_id' => $this->session->userdata('active_boutique'),
	    	'unique_id' => $unique_id,
	    	'date' => date("Y-m-d")
	    );
	    $this->db->insert('telefonafstemning',$string);

	    $insert_id = $this->db->insert_id();

	    if($mail_info){

			$subject = 'Lagerafstemning stemmer ikke '.$boutique_info->name.'';
			$message = '#'.$unique_id.' - '.$boutique_info->name.' lagerafstemning - '.date("d/m/Y H:i").' - '.$username.'<br />
Følgende enheder er ikke på lager ved dagens afstemning af: "'.$username.'"<br />
'.$mail_info.'';

			$this->load->library('email');

			$config['mailtype'] = 'html';
			$this->email->initialize($config);

			$this->email->from('no-reply@2ndbest.dk', '2ndbest');
			$this->email->to('simon@vato.dk, rh@brugteiphones.dk, jw@brugteiphones.dk, nf@brugteiphones.dk');

			$this->email->subject($subject);
			$this->email->message($message);

			$this->email->send();


		}

		$this->global_model->log_action('phone_counting_created',$insert_id,false);

	    redirect('status/phone/'.$insert_id);

    }

}

// end of model file
