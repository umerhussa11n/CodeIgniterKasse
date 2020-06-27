<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('statistic');
    }

	public function index()
	{

		$data['title'] = 'Statistik';

		if($this->input->get('from')){
			$start_period = strtotime($this->input->get('from').' 00:00:00');
			$end_period   = strtotime($this->input->get('to').' 23:59:59');

			$data['start_date'] = date("Y-m-d",strtotime($this->input->get('from').' 00:00:00'));
			$data['end_date'] = date("Y-m-d",strtotime($this->input->get('to').' 23:59:59'));

		}else{
			$start_period = strtotime("first day of this month");
			$end_period   = strtotime("today 23:59:59");

			$data['start_date'] = date("Y-m-d",strtotime("first day of this month"));
			$data['end_date'] = date("Y-m-d",strtotime("today 23:59:59"));

		}


		$payment = $this->global_model->get_payment();
		$data['payment'] = $payment;

		// get earnings month by month
		$data['january'] = $this->global_model->calculate_revenue_by_month('january',$this->input->get('boutique'));
		$data['february'] = $this->global_model->calculate_revenue_by_month('february',$this->input->get('boutique'));
		$data['march'] = $this->global_model->calculate_revenue_by_month('march',$this->input->get('boutique'));
		$data['april'] = $this->global_model->calculate_revenue_by_month('april',$this->input->get('boutique'));
		$data['may'] = $this->global_model->calculate_revenue_by_month('may',$this->input->get('boutique'));
		$data['june'] = $this->global_model->calculate_revenue_by_month('june',$this->input->get('boutique'));
		$data['july'] = $this->global_model->calculate_revenue_by_month('july',$this->input->get('boutique'));
		$data['august'] = $this->global_model->calculate_revenue_by_month('august',$this->input->get('boutique'));
		$data['september'] = $this->global_model->calculate_revenue_by_month('september',$this->input->get('boutique'));
		$data['october'] = $this->global_model->calculate_revenue_by_month('october',$this->input->get('boutique'));
		$data['november'] = $this->global_model->calculate_revenue_by_month('november',$this->input->get('boutique'));
		$data['december'] = $this->global_model->calculate_revenue_by_month('december',$this->input->get('boutique'));

		// get sale
		$this->db->select_sum('price');

		if($this->input->get('boutique')){
			if($this->input->get('boutique') != 'all'){
				$this->db->where('boutique_id',$this->input->get('boutique'));
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}

		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','sold');
		$this->db->where('cancelled',0);
		$this->db->where('hidden',0);
		$access = $this->db->get('orders')->result();

		$this->db->select_sum('order_item.total_price_with_discount');
		$this->db->join('orders','orders.id=order_item.order_id');
		$this->db->where('order_item.bought_from_order_id >',0);
		$this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
		$this->db->where('orders.created_timestamp >=',$start_period);
		$this->db->where('orders.created_timestamp <=',$end_period);
		$this->db->where('orders.type','access');
		$this->db->where('orders.hidden',0);
		$sold_price_new = $this->db->get('order_item');
		$sold_price_new = $sold_price_new->num_rows()?$sold_price_new->row()->total_price_with_discount:0;
		if($access){
			if($access[0]->price == false){
				$data['sale'] = 0 + $sold_price_new;
			}else{
				$data['sale'] = $access[0]->price + $sold_price_new;
			}
		}else{
			$data['sale'] = 0 + $sold_price_new;
		}

		//////////////////////////////////

		foreach($payment as $row){
			$this->db->select_sum('price');

			if($this->input->get('boutique')){
				if($this->input->get('boutique') != 'all'){
					$this->db->where('boutique_id',$this->input->get('boutique'));
				}
			}else{
				$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
			}

			$this->db->where('created_timestamp >=',$start_period);
			$this->db->where('created_timestamp <=',$end_period);
			$this->db->where('type','sold');
			$this->db->where('cancelled',0);
			$this->db->where('hidden',0);
			$this->db->where('payment_type',$row['name']);
			$access = $this->db->get('orders')->result();

			$this->db->select_sum('order_payments.amount');
			$this->db->join("order_item","order_item.order_id = order_payments.order_id");
			$this->db->join("orders","orders.id = order_payments.order_id");
			$this->db->where('order_item.bought_from_order_id >',0);
			$this->db->where('order_payments.payment_method',$row['name']);
			if($this->input->get('boutique')){
				if($this->input->get('boutique') != 'all'){
					$this->db->where('orders.boutique_id',$this->input->get('boutique'));
				}
			}else{
				$this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
			}
			$this->db->where('orders.created_timestamp >=',$start_period);
			$this->db->where('orders.created_timestamp <=',$end_period);
			$this->db->where('orders.hidden',0);
			$this->db->where('orders.type','access');
			$sold_total = $this->db->get('order_payments');
			$sold_total = $sold_total->num_rows()?$sold_total->row()->amount:0;
			if($access){
				if($access[0]->price == false){
					$data[$row['name'].'_sold'] = 0 + $sold_total;
				}else{
					$data[$row['name'].'_sold'] = $access[0]->price + $sold_total;
				}
			}else{
				$data[$row['name'].'_sold'] = 0;
			}
		}




		// get buy
		$this->db->select_sum('price');

		if($this->input->get('boutique')){
			if($this->input->get('boutique') != 'all'){
				$this->db->where('boutique_id',$this->input->get('boutique'));
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}

		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','bought');
		$this->db->where('cancelled',0);
		$this->db->where('hidden',0);
		$access = $this->db->get('orders')->result();

		if($access){
			if($access[0]->price == false){
				$data['bought'] = 0;
			}else{
				$data['bought'] = $access[0]->price;
			}
		}else{
			$data['bought'] = 0;
		}


		// get access

		/*
		$this->db->select_sum('price');

		if($this->input->get('boutique')){
			if($this->input->get('boutique') != 'all'){
				$this->db->where('boutique_id',$this->input->get('boutique'));
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}

		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','access');
		$this->db->where('cancelled',0);
		$this->db->where('hidden',0);
		$access = $this->db->get('orders')->result();
		*/
		$this->db->select_sum('order_payments.amount');
		$this->db->join("order_item","order_item.order_id = order_payments.order_id");
		$this->db->join("orders","orders.id = order_payments.order_id");
		$this->db->where('order_item.bought_from_order_id',0);
		if($this->input->get('boutique')){
			if($this->input->get('boutique') != 'all'){
				$this->db->where('orders.boutique_id',$this->input->get('boutique'));
			}
		}else{
			$this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('orders.created_timestamp >=',$start_period);
		$this->db->where('orders.created_timestamp <=',$end_period);
		$this->db->where('orders.hidden',0);
		$this->db->where('orders.cancelled',0);
		$this->db->where('orders.type','access');
		$access_total = $this->db->get('order_payments');
		$access_total = $access_total->num_rows()?$access_total->row()->amount:0;
		//echo $access_total; die;
		$data['access'] = $access_total;
		/*
		if($access){
			if($access[0]->price == false){
				$data['access'] = 0;
			}else{
				$data['access'] = $access[0]->price;
			}
		}else{
			$data['access'] = 0;
		}
		*/


		// get parts used
		$this->db->select_sum('parts.price');
		$this->db->from('parts');
		if($this->input->get('boutique')){
			if($this->input->get('boutique') != 'all'){
				$this->db->where('parts_used.boutique_id',$this->input->get('boutique'));
			}
		}else{
			$this->db->where('parts_used.boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('parts_used.created_timestamp >=',$start_period);
		$this->db->where('parts_used.created_timestamp <=',$end_period);
		$this->db->join('parts_used', 'parts_used.part_id = parts.id');

		$access = $this->db->get()->result();

		if($access){
			if($access[0]->price == false){
				$data['parts'] = 0;
			}else{
				$data['parts'] = $access[0]->price;
			}
		}else{
			$data['parts'] = 0;
		}


		// result
		$data['result'] = ($data['access']+$data['sale'])-($data['parts']+$data['bought']);

		if($data['result'] < 0){
			$data['type'] = 'negative';
		}else{
			$data['type'] = 'positive';
		}

		$data['yield'] = "statistic/index";
		$this->load->view('layout/application',$data);
	}



	function hidden($boutique_id = false){
		$this->load->model('device_model');
		$start_date = $this->input->get('sdate');
		$end_date = $this->input->get('edate');

		$start_period = strtotime("$start_date 00:00:00");
		$end_period   = strtotime("$end_date 23:59:59");

		$this->db->where('id',$boutique_id);
		$data['boutique'] = $this->db->get('boutiques')->result();

		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where("((type = 'sold' AND hidden = 1) OR (type = 'access' AND (SELECT COUNT(*) FROM order_payments JOIN order_item ON order_item.order_id=order_payments.order_id WHERE order_payments.order_id = orders.id AND order_payments.hidden = 1 AND order_item.bought_from_order_id > 0) > 0))");
		//$this->db->where('hidden',1);
		$data['orders_bought'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','bought');
		$this->db->where('hidden',1);
		$data['orders_sold'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','credit');
		$this->db->where('hidden',1);
		$data['orders_credit'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		//$this->db->where('type','access');
		$this->db->where("((type = 'access' AND hidden = 1) OR (type = 'access' AND (SELECT COUNT(*) FROM order_payments JOIN order_item ON order_item.order_id=order_payments.order_id WHERE order_payments.order_id = orders.id AND order_payments.hidden = 1 AND order_item.bought_from_order_id = 0) > 0))");
		//$this->db->where('hidden',1);
		$data['access'] = $this->db->get('orders')->result();

		$data['yield'] = "statistic/day_hidden";
		$this->load->view('layout/application',$data);
	}

	function interval($boutique_id = false){
		$this->load->model('device_model');
		$start_date = $this->input->get('sdate');
		$end_date = $this->input->get('edate');

		$start_period = strtotime("$start_date 00:00:00");
		$end_period   = strtotime("$end_date 23:59:59");

		$this->db->where('id',$boutique_id);
		$data['boutique'] = $this->db->get('boutiques')->result();

		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','sold');
		$this->db->where('hidden',0);
		$data['orders_bought'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','bought');
		$this->db->where('hidden',0);
		$data['orders_sold'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','credit');
		$this->db->where('hidden',0);
		$data['orders_credit'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','access');
		$this->db->where('hidden',0);
		$data['access'] = $this->db->get('orders')->result();

		$data['yield'] = "statistic/day";
		$this->load->view('layout/application',$data);
	}


	function month_numbers(){

		$this->load->model('boutique_model');

		$data['yield'] = "statistic/month_numbers";
		$this->load->view('layout/application',$data);

	}

	function month_numbers_detailed($month,$boutique){

		$this->load->model('boutique_model');

		$boutique_id = $boutique;
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));

		$start_period = strtotime(date("01-$month-Y").' 00:00:00');
		$end_period   = strtotime(date("$days_in_month-$month-Y").' 23:59:59');

		$this->db->where('id',$boutique_id);
		$data['boutique'] = $this->db->get('boutiques')->result();

		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('id !=',0);
		$this->db->where('bought_from_order_id !=',0);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','sold');
		$this->db->where('hidden',0);
		$data['orders_bought'] = $this->db->get('orders')->result();


		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('created_timestamp >=',$start_period);
		$this->db->where('created_timestamp <=',$end_period);
		$this->db->where('type','access');
		$this->db->where('hidden',0);
		$data['access'] = $this->db->get('orders')->result();

		$data['yield'] = "statistic/month_numbers_detailed";
		$this->load->view('layout/application',$data);

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
