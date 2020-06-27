<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {


	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('kasseafstemning');
    }

	public function index($id = false)
	{

		$rank_permissions = $this->global_model->get_rank_permissions();


		if($this->input->post('create')){

			$this->load->model('boutique_model');
			$this->boutique_model->create_counting();

		}elseif($this->input->post('edit')){

			$this->load->model('boutique_model');
			$this->boutique_model->edit_counting();

		}

		$date = $this->input->get('date');
		$timeonday = false;


		if($date){
			$start = strtotime($date.' 00:00:00');
			$end = strtotime($date.' 23:59:59');

			$date = $date;

		}else{
			$start = strtotime(date("Y-m-d").' 00:00:00');
		    $end = strtotime(date("Y-m-d").' 23:59:59');

		    $date = date("Y-m-d");
		}

		if($id){
			$this->db->where('id',$id);
		}else{
			$this->db->where('created_timestamp >= ',$start);
		    $this->db->where('created_timestamp <= ',$end);
	    }
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$this->db->limit(1);
		$this->db->order_by('id','desc');
		$data['counting'] = $this->db->get('kasseafstemning');

		if($data['counting']->num_rows()){
			$data['counting'] = $data['counting']->result();
		}else{
			$data['counting'] = false;
		}
		$this->db->where('created_timestamp <',time());
		$this->db->limit(1);
		$this->db->order_by('id','desc');
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$data['counting_from_yesterday'] = $this->db->get('kasseafstemning')->result();

		if($data['counting']){
			if($data['counting'][0]->ultimo != '0.00'){
				$data['primo_amount'] = $data['counting'][0]->ultimo;
			}else{
				$data['primo_amount'] = 0;
			}

			$date = date("Y-m-d",$data['counting'][0]->created_timestamp);
			$timeonday = date("H:i",$data['counting'][0]->created_timestamp);

		}else{
			$data['primo_amount'] = 0;
		}

		//$data['counting']  = false;

		$this->db->where('id',$this->session->userdata('active_boutique'));
		$data['boutique'] = $this->db->get('boutiques')->result();

		$payments = $this->global_model->get_payment();

		$data['payments'] = array();
		$data['payments']['card'] = 0;
		$data['payments']['cash'] = 0;
		$data['payment_label']['cash'] = 'Kontant';
		$data['payment_label']['card'] = 'Kort';
	//echo $date;
		if($payments){
			foreach($payments as $payment){
				$data['payment_label'][$payment['name']] = $payment['label'];
				$data['payments'][$payment['name']] = $this->global_model->calculate_sale_by_payment_type_new($payment['name'],$date,false,$timeonday);
			}
		}
		//print_r($data['payments']); die;
		/*
		$data['card'] = $this->global_model->calculate_sale_by_payment_type_new('card',$date,false,$timeonday);
		$data['cash'] = $this->global_model->calculate_sale_by_payment_type_new('cash',$date,false,$timeonday);
		$data['mobilepay'] = $this->global_model->calculate_sale_by_payment_type_new('mobilepay',$date,false,$timeonday);
		$data['invoice'] = $this->global_model->calculate_sale_by_payment_type_new('invoice',$date,false,$timeonday);
		$data['webshop'] = $this->global_model->calculate_sale_by_payment_type_new('webshop',$date,false,$timeonday);
		$data['loan'] = $this->global_model->calculate_sale_by_payment_type_new('loan',$date,false,$timeonday);
		$data['nettalk'] = $this->global_model->calculate_sale_by_payment_type_new('nettalk',$date,false,$timeonday);
		*/
		$data['date'] = $date;

		$data['yield'] = "status/index";
		$this->load->view('layout/application',$data);
	}


	public function phone($id = false)
	{

		$rank_permissions = $this->global_model->get_rank_permissions();


		if($this->input->post('create')){

			$this->load->model('boutique_model');
			$this->boutique_model->create_phone_counting();

		}

		$date = $this->input->get('date');

		if($id){
			$this->db->where('id',$id);
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$data['counting'] = $this->db->get('telefonafstemning')->result();
		}else{
			$data['counting'] = false;
	    }


		$this->db->where('id',$this->session->userdata('active_boutique'));
		$data['boutique'] = $this->db->get('boutiques')->result();

		$this->load->model('inventory_model');
		$data['phones'] = $this->inventory_model->get_phones_in_boutique($this->session->userdata('active_boutique'),$this->input->get('sortby'));

		$data['date'] = $date;

		$data['yield'] = "status/phone";
		$this->load->view('layout/application',$data);
	}


	/*function webshop($date = false){

		if(!$date){
			$date = date("Y-m-d");
		}
		$start = strtotime($date.' 00:00:00');
	    $end = strtotime($date.' 23:59:59');

	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $this->db->where('uid',$this->session->userdata('uid'));
	    $this->db->where('created_timestamp >= ',$start);
	    $this->db->where('created_timestamp <= ',$end);
	    $counting = $this->db->get('kasseafstemning')->result();

	    if($counting){

	    	$id = $counting[0]->id;

	    }else{

	    	if($date){
		    	$created_timestamp = $start;
	    	}else{
		    	$created_timestamp = time();
	    	}

		    $string = array(
		    	'boutique_id' => $this->session->userdata('active_boutique'),
		    	'uid'         => $this->session->userdata('uid'),
		    	'created_timestamp' => $created_timestamp,
		    	'webshop' => 1
		    );
		    $this->db->insert('kasseafstemning',$string);

		    $id = $this->db->insert_id();

	    }

	    redirect('status/generate/'.$id);

	}*/

	function generate($id = false){

		$this->db->where('id',$id);
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$data['counting'] = $this->db->get('kasseafstemning')->result();

		if(!$data['counting']){
			redirect('status');
		}

		// get counting from days before
		$this->db->where('created_timestamp <',$data['counting'][0]->created_timestamp);
		$this->db->limit(1);
		$this->db->order_by('id','desc');
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$data['counting_from_yesterday'] = $this->db->get('kasseafstemning')->result();

		if($data['counting'][0]->boutique_id != 45454545454545){

			$data['start'] = strtotime(date("Y-m-d",$data['counting'][0]->created_timestamp).' 00:00:00');
			$data['end'] = strtotime(date("Y-m-d",$data['counting'][0]->created_timestamp).' 23:59:59');

			$this->db->where('id',$this->session->userdata('active_boutique'));
			$data['boutique'] = $this->db->get('boutiques')->result();

			$payments = $this->global_model->get_payment();
			$data['payments'] = array();
			$data['payments']['card'] = 0;
			$data['payments']['cash'] = 0;
			$data['payment_label']['cash'] = 'Kontant';
			$data['payment_label']['card'] = 'Kort';
			$totalRevenue = 0;
			if($payments){
				//echo date("Y-m-d",$data['counting'][0]->created_timestamp);
				foreach($payments as $payment){
					$data['payment_label'][$payment['name']] = $payment['label'];
					$data['payments'][$payment['name']] = $this->global_model->calculate_sale_by_payment_type_new($payment['name'],date("Y-m-d",$data['counting'][0]->created_timestamp),false,date("H:i",$data['counting'][0]->created_timestamp));
					$totalRevenue += $data['payments'][$payment['name']];
				}
			}

		//	print_r($data['payments']);die;
			/*
			$data['card'] = $this->global_model->calculate_sale_by_payment_type('card',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['cash'] = $this->global_model->calculate_sale_by_payment_type('cash',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['mobilepay'] = $this->global_model->calculate_sale_by_payment_type('mobilepay',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['invoice'] = $this->global_model->calculate_sale_by_payment_type('invoice',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['loan'] = $this->global_model->calculate_sale_by_payment_type('loan',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['webshop'] = $this->global_model->calculate_sale_by_payment_type('webshop',date("Y-m-d",$data['counting'][0]->created_timestamp));
			$data['nettalk'] = $this->global_model->calculate_sale_by_payment_type('nettalk',date("Y-m-d",$data['counting'][0]->created_timestamp));
			*/
			$data['totalRevenue'] = $totalRevenue;

			$data['phoneSale'] = $this->global_model->calculate_sale_by_payment_typeRevenue_new(date("Y-m-d",$data['counting'][0]->created_timestamp),'sold');
			$data['access'] = $this->global_model->calculate_sale_by_payment_typeRevenue_new(date("Y-m-d",$data['counting'][0]->created_timestamp),'access');


			// user
			$this->db->where('id',$data['counting'][0]->uid);
			$data['user'] = $this->db->get('users_kasse')->result();

			$prevdayTimestamp = $data['counting'][0]->created_timestamp-86400;
			$data['cashPrevDay'] = $this->global_model->calculate_sale_by_payment_type_new('cash',date("Y-m-d",$prevdayTimestamp));

			// yesterday counting
			$yesterday_counting_start = strtotime(date("Y-m-d",$prevdayTimestamp).' 00:00:00');
			$yesterday_counting_end = strtotime(date("Y-m-d",$prevdayTimestamp).' 23:59:59');


			/*$this->db->where('created_timestamp >=',$yesterday_counting_start);
			$this->db->where('created_timestamp <=',$yesterday_counting_end);
			$this->db->limit(1);
			$this->db->order_by('created_timestamp','desc');
			$this->db->where('boutique_id',$data['counting'][0]->boutique_id);
			$yesterday_counting = $this->db->get('kasseafstemning')->result();

			if($yesterday_counting){
				$to_bank_yesterday = $yesterday_counting[0]->to_bank;

				if($to_bank_yesterday){
					$data['primo_amount'] = $data['cashPrevDay']-$to_bank_yesterday;
				}else{
					$data['primo_amount'] = $data['cashPrevDay'];
				}

				$cashYesterday = $this->global_model->calculate_sale_by_payment_type('cash',date("Y-m-d",$yesterday_counting_start));

				//$data['primo_amount'] = $cashYesterday+$yesterday_counting[0]->to_bank;
				// PRIMO = kontantomsætning+primo fra dagen før+til bank
				// WIP - Udregn primo beløb

			}else{
				$data['primo_amount'] = 0;
			}*/


			if($data['counting_from_yesterday']){
				if($data['counting_from_yesterday'][0]->ultimo != '0.00'){
					$data['primo_amount'] = $data['counting_from_yesterday'][0]->ultimo;
				}else{
					$data['primo_amount'] = 0;
				}
			}else{
				$data['primo_amount'] = 0;
			}


			$data['yield'] = "status/generate";

		}else{

			$this->db->where('id',$this->session->userdata('active_boutique'));
			$data['boutique'] = $this->db->get('boutiques')->result();

			$data['start'] = strtotime(date("Y-m-d",$data['counting'][0]->created_timestamp).' 00:00:00');
			$data['end'] = strtotime(date("Y-m-d",$data['counting'][0]->created_timestamp).' 23:59:59');

			$data['yield'] = "status/generate_webshop";

		}

		$this->load->view('layout/application_raw',$data);

	}


	function unique_id(){

		$prev_id = 0;
		$prev_unique = 0;

		$this->db->order_by('boutique_id, id');
		$kasse = $this->db->get('kasseafstemning')->result();

		foreach($kasse as $kasse){

			if($prev_id != $kasse->boutique_id){
				$i = 1;
			}

			$string = array(
				'unique_id' => $i
			);
			$this->db->where('id',$kasse->id);
			$this->db->update('kasseafstemning',$string);

			$prev_id = $kasse->boutique_id;
			$prev_unique = $i;
			$i++;

		}

	}


	function calculate_ultimo(){


		$this->db->order_by('id');
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$countinfo = $this->db->get('kasseafstemning')->result();


		foreach($countinfo as $counting){


			// get counting from days before
			$this->db->where('created_timestamp <',$counting->created_timestamp);
			$this->db->limit(1);
			$this->db->order_by('id','desc');
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			$data['counting_from_yesterday'] = $this->db->get('kasseafstemning')->result();

			if($data['counting_from_yesterday']){
				if($data['counting_from_yesterday'][0]->ultimo != '0.00'){
					$primo_amount = $data['counting_from_yesterday'][0]->ultimo;
				}else{
					$primo_amount = 0;
				}
			}else{
				$primo_amount = 0;
			}

			$card = $this->global_model->calculate_sale_by_payment_type('card',date("Y-m-d",$counting->created_timestamp));
			$cash = $this->global_model->calculate_sale_by_payment_type('cash',date("Y-m-d",$counting->created_timestamp));
			$mobilepay = $this->global_model->calculate_sale_by_payment_type('mobilepay',date("Y-m-d",$counting->created_timestamp));
			$invoice = $this->global_model->calculate_sale_by_payment_type('invoice',date("Y-m-d",$counting->created_timestamp));
			$loan = $this->global_model->calculate_sale_by_payment_type('loan',date("Y-m-d",$counting->created_timestamp));
			$nettalk = $this->global_model->calculate_sale_by_payment_type('nettalk',date("Y-m-d",$counting->created_timestamp));

			$info = json_decode($counting->info);

			if(isset($info->cashInfo->halvore)){
				if($info->cashInfo->halvore){
					$halvore = $info->cashInfo->halvore/2;
				}else{
					$halvore = 0;
				}
			}else{
				$halvore = 0;
			}
			if(isset($info->cashInfo->enkr)){
				if($info->cashInfo->enkr){
					$enkr    = $info->cashInfo->enkr;
				}else{
					$enkr = 0;
				}
			}else{
				$enkr 	 = 0;
			}
			if(isset($info->cashInfo->tokr)){
				if($info->cashInfo->tokr){
					$tokr 	 = $info->cashInfo->tokr*2;
				}else{
					$tokr = 0;
				}
			}else{
				$tokr	 = 0;
			}
			if(isset($info->cashInfo->femkr)){
				if($info->cashInfo->femkr){
					$femkr   = $info->cashInfo->femkr*5;
				}else{
					$femkr = 0;
				}
			}else{
				$femkr   = 0;
			}
			if(isset($info->cashInfo->tikr)){
				if($info->cashInfo->tikr){
					$tikr    = $info->cashInfo->tikr*10;
				}else{
					$tikr = 0;
				}
			}else{
				$tikr    = 0;
			}
			if(isset($info->cashInfo->tyvekr)){
				if($info->cashInfo->tyvekr){
					$tyvekr  = $info->cashInfo->tyvekr*20;
				}else{
					$tyvekr = 0;
				}
			}else{
				$tyvekr  = 0;
			}
			if(isset($info->cashInfo->halvtredskr)){
				if($info->cashInfo->halvtredskr){
					$halvtredskr = $info->cashInfo->halvtredskr*50;
				}else{
					$halvtredskr = 0;
				}
			}else{
				$halvtredskr = 0;
			}
			if(isset($info->cashInfo->hundkr)){
				if($info->cashInfo->hundkr){
					$hundkr  = $info->cashInfo->hundkr*100;
				}else{
					$hundkr = 0;
				}
			}else{
				$hundkr  = 0;
			}
			if(isset($info->cashInfo->tohundkr)){
				if($info->cashInfo->tohundkr){
					$tohundkr = $info->cashInfo->tohundkr*200;
				}else{
					$tohundkr = 0;
				}
			}else{
				$tohundkr = 0;
			}
			if(isset($info->cashInfo->femhundkr)){
				if($info->cashInfo->femhundkr){
					$femhundkr = $info->cashInfo->femhundkr*500;
				}else{
					$femhundkr = 0;
				}
			}else{
				$femhundkr = 0;
			}
			if(isset($info->cashInfo->tusindkr)){
				if($info->cashInfo->tusindkr){
					$tusindkr  = $info->cashInfo->tusindkr*1000;
				}else{
					$tusindkr = 0;
				}
			}else{
				$tusindkr  = 0;
			}

			$total = $halvore+$enkr+$tokr+$femkr+$tikr+$tyvekr+$halvtredskr+$hundkr+$tohundkr+$femhundkr+$tusindkr;

			$ultimo = ($primo_amount+$cash)-$counting->to_bank;

			$total_cash = $ultimo-$total;

			if(isset($info->cardInfo->dankort)){
				if($info->cardInfo->dankort){
					$dankort = $info->cardInfo->dankort;
				}else{
					$dankort = 0;
				}
			}else{
				$dankort = 0;
			}
			if(isset($info->cardInfo->danskeecmcvi)){
				if($info->cardInfo->danskeecmcvi){
					$danskeecmcvi = $info->cardInfo->danskeecmcvi;
				}else{
					$danskeecmcvi = 0;
				}
			}else{
				$danskeecmcvi = 0;
			}
			if(isset($info->cardInfo->udlexmxvijcb)){
				if($info->cardInfo->udlexmxvijcb){
					$udlexmxvijcb = $info->cardInfo->udlexmxvijcb;
				}else{
					$udlexmxvijcb = 0;
				}
			}else{
				$udlexmxvijcb = 0;
			}
			if(isset($info->cardInfo->gebyr)){
				if($info->cardInfo->gebyr){
					$gebyr = $info->cardInfo->gebyr;
				}else{
					$gebyr = 0;
				}
			}else{
				$gebyr = 0;
			}

			$total_card = ($dankort+$danskeecmcvi+$udlexmxvijcb-$gebyr);

			$card_diff = $total_card-$card;


			// update ultimo
			$string = array(
				'ultimo' => $ultimo
			);
			$this->db->where('id',$counting->id);
			$this->db->update('kasseafstemning',$string);


		}

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
