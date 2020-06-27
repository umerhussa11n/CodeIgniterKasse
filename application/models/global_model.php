<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Global_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        date_default_timezone_set('Europe/Copenhagen');


        if($this->session->userdata('logged_in') && $this->uri->segment(1) != 'logout'){


	        $today_timestamp = strtotime("today 00:00:00");

	        // check if user has logged in today
	        $me = $this->me();

	        if($me[0]->last_login < $today_timestamp){
		        redirect('logout');
	        }elseif($this->session->userdata('active_boutique') == false && $this->uri->segment(1) != 'choose_boutique'){
		        redirect('choose_boutique');
	        }

        }

    }

    function get_month_name($month){
    	$month = $month-1;
	    $months = array("Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December");
	    return $months[$month];
    }


    function get_boutique_name_by_id($id){
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

    function me(){
	    $this->db->where('id',$this->session->userdata('uid'));
	    $users = $this->db->get('users_kasse')->result();

	    return $users;
    }

    function check_permission($permission,$redirect = TRUE){

	    $rank_permissions = $this->get_rank_permissions();

	    if (strpos($rank_permissions,$permission) !== false || strpos($rank_permissions,'all') !== false) {
	    	return true;
	    }else{
	    	if($redirect){
		    	$this->session->set_flashdata('error','Du har ikke adgang til denne handling');
			    redirect('');
		    }else{
			    return false;
		    }
	    }

    }

    function get_boutiques(){
	    $this->db->where('active',1);
		$this->db->order_by('id','desc');
		$boutiques = $this->db->get('boutiques')->result();

		return $boutiques;
    }

    function get_rank_permissions()
    {

        $this->db->where('id',$this->session->userdata('uid'));
        $sql = $this->db->get('users_kasse')->result();

        if($sql){

        	$this->db->where('id',$sql[0]->rank);
        	$rank_info = $this->db->get('ranks')->result();
        	if($rank_info){
	        	return $rank_info[0]->permission;
	        }else{
		        return 0;
	        }
        }else{
	        return 0;
        }

    }

    function payment_type($type){
	    if($type == 'cash'){
		    return 'Kontant';
	    }elseif($type == 'mobilepay'){
		    return 'Mobilepay';
	    }elseif($type == 'card'){
		    return 'Dankort';
		}elseif($type == 'webshop'){
		    return 'Webshop';
		}elseif($type == 'loan'){
		    return 'Lån';
		}elseif($type == 'invoice'){
		    return 'Faktura';
		}elseif($type == 'nettalk'){
		    return 'Nettalk';
	    }else{
		    return '-';
	    }
    }

    function permission_array(){


	    $array = array(
	    	'all'                     		=> 'Alle',
	    	'sold_devices_overview'   		=> 'Solgte enheder oversigt',
	    	'create_sold_device'      		=> 'Opret solgt enhed',
	    	'sell_access_overview'    		=> 'Sælg tilbehør oversigt',
	    	'create_sell_access'      		=> 'Opret solgt tilbehør',
	    	'bought_devices_overview' 		=> 'Købte enheder oversigt',
	    	'create_bought_device'    		=> 'Opret købt enhed',
	    	'tranfer_overview'        		=> 'Overførsler',
	    	'statistic'               		=> 'Statistik',
	    	'boutique_overview'       		=> 'Butikker oversigt',
	    	'create_boutique'         		=> 'Opret butik',
	    	'edit_boutique'           		=> 'Rediger butik',
	    	'deactivate_boutique'     		=> 'Deaktiver butik',
	    	'earning_sidebar'         		=> 'Indtjening i sidebar (butikker)',
	    	'earning_sidebar_month'   		=> 'Indtjening den seneste måned (top)',
	    	'users_overview'          		=> 'Brugere oversigt',
	    	'create_user'             		=> 'Opret bruger',
	    	'edit_user'               		=> 'Rediger bruger',
	    	'deactivate_user'         		=> 'Deaktiver bruger',
	    	'delivery_receipt'        		=> 'Indleverings kvitteringer',
	    	'inventory_overview'      		=> 'Lagerstyrings oversigt',
	    	'logout_cash'             		=> 'Mulighed for at vælge kassebeholdning ved log ud',
	    	'inventory_in_other_boutiques'  => 'Kan se lagerbeholdning i andre butikker',
	    	'kasseafstemning' 		  		=> 'Kasseafstemning',
	    	'timer_overview' 		  		=> 'Oversigt over timer',
	    	'transfer_inventory' 	  		=> 'Overfør lagerbeholdning',
	    	'create_defect'  				=> 'Opret defekt (til lager)',
	    	'transfer_sidebar' 			    => 'Overfør enhed',
	    	'credit_overview' 			    => 'Krediterede salg',
	    	'bought_from_company'			=> 'Opkøb af virksomhed - mulighed for at sætte prisen manuelt',
	    	'sold_insurances'				=> 'Solgte forsikringer',
	    	'hidden_btn' 					=> 'Botón',
	    	'hidden_btn_earning'			=> 'Botón Ganancias',
	    	'complaints'					=> 'Reklamationer'
	    );

	     // get boutiques
	    $this->db->where('active',1);
	    $boutiques = $this->db->get('boutiques')->result();

	    foreach($boutiques as $boutique){
	    	$array[strtolower($boutique->id).'day'] = 'Salg i dag: '.$boutique->name;
	    	$array[strtolower($boutique->id).'month'] = 'Salg denne måned: '.$boutique->name;
	    }

	    $array['total_sale_today'] = 'Total salg i dag';
	    $array['total_sale_month'] = 'Total salg denne måned';

	    return $array;


    }

    function gbs(){

	    $array = array(
	    	'8'			=> '8 GB',
	    	'16'		=> '16 GB',
	    	'32'		=> '32 GB',
	    	'64'		=> '64 GB',
	    	'128'		=> '128 GB',
	    	'256'		=> '256 GB',
			'512'		=> '512 GB',
			'1024'		=> '1 TB'
	    );

	    return $array;


    }

    function check_if_logged_in(){

	    if(!$this->session->userdata('uid')){
		    redirect('login');
		    exit;
	    }

    }

    function get_rank($id){
	    $this->db->where('id',$id);
	    $ranks = $this->db->get('ranks')->result();

	    if($ranks){
		    return $ranks[0]->name;
	    }

    }


    function calculate_earnings_on_phone($id = false){

	    // BOUGHT AMOUNT
	    $total_amount = 0;
	    $total_used_parts = 0;
	    $total_mail_label = 0;
	    $total_post_label = 0;
	    $bought_price = 0;

	    $this->db->where('id',$id);
	    $this->db->where('hidden',0);
	    $orderlines = $this->db->get('orders')->result();

	    foreach($orderlines as $orderline){

			$total_amount += $orderline->price;


			// get bought order
			$this->db->where('id',$orderline->bought_from_order_id);
			$this->db->where('hidden',0);
			$bought_order = $this->db->get('orders')->result();

			if($bought_order){
				$bought_price = $bought_order[0]->price;
			}else{
				$bought_price = 0;
			}

			// calculate use
			$this->db->select('*');
			$this->db->from('parts');
			$this->db->where('parts_used.order_id',$orderline->bought_from_order_id);
			$this->db->join('parts_used', 'parts_used.part_id = parts.id');

			$used_parts = $this->db->get()->result();

			foreach($used_parts as $parts){
				$total_used_parts += $parts->price;
			}


			if($orderline->type == 'access'){

				$this->db->where('id',$orderline->part_id);
				$part_info = $this->db->get('parts')->result();

				if($part_info){
					$total_used_parts += $part_info[0]->price;
				}

			}

	    }

		$result = $total_amount-$total_used_parts-$bought_price;

	    // BUY UP PRICES

	    return $result;

    }


    function get_earnings_for_boutique($id = false){

	    $start_month = strtotime("first of month");

	    $this->db->select_sum('total');
	    $this->db->where('boutique_id',$id);
	    $this->db->where('created_timestamp >=',$start_month);
	    $orders = $this->db->get('day_results')->result();

	    if($orders){
		    return $orders[0]->total;
	    }else{
		    return 0;
	    }

    }

    function end_of_day_calculate($type){
	    $start_today = strtotime("today 00:00:00");
	    $end_today = strtotime("today 23:59:59");

	    $this->db->select_sum('price');
	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $this->db->where('type','sold');
	    $this->db->where('payment_type',$type);
	    $this->db->where('hidden',0);
	    $this->db->where('created_timestamp >=',$start_today);
	    $this->db->where('created_timestamp <=',$end_today);
	    $orders = $this->db->get('orders')->result();

	    if($orders){
		    return $orders[0]->price;
	    }else{
		    return 0;
	    }
    }


    function calculate_revenue_by_month($month = false,$boutique_id = false){

	     // BOUGHT AMOUNT
	    $total_amount = 0;
	    $total_used_parts = 0;
	    $total_mail_label = 0;
	    $total_post_label = 0;
	    $bought_price = 0;
	    $sold_price = 0;

	    $from = strtotime($month.' 00:00:00');
	    $to   = strtotime($month.' 23:59:59');

	    $this->db->where('created_timestamp >=',$from);
	    $this->db->where('created_timestamp <=',$to);
	    if($boutique_id){
			if($boutique_id != 'all'){
				$this->db->where('boutique_id',$boutique_id);
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('cancelled',0);
		$this->db->where('type !=','cancelled');
		$this->db->where('hidden',0);
	    $orderlines = $this->db->get('orders')->result();

	    foreach($orderlines as $orderline){

			$total_amount += $orderline->price;

			if($orderline->type == 'sold'){
				$sold_price   += $orderline->price;
			}elseif($orderline->type == 'bought'){
				$bought_price += $orderline->price;
			}

			// calculate use
			$this->db->select('*');
			$this->db->from('parts');
			$this->db->where('parts_used.order_id',$orderline->id);
			$this->db->join('parts_used', 'parts_used.part_id = parts.id');

			$used_parts = $this->db->get()->result();

			foreach($used_parts as $parts){
				$total_used_parts += $parts->price;
			}

	    }


	    $this->db->select_sum('price');

		$this->db->where('created_timestamp >=',$from);
	    $this->db->where('created_timestamp <=',$to);
	    if($boutique_id){
			if($boutique_id != 'all'){
				$this->db->where('boutique_id',$boutique_id);
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('cancelled',0);
		$this->db->where('type','access');
		$this->db->where('hidden',0);
		$access = $this->db->get('orders')->result();

		if($access){
			if($access[0]->price == false){
				$access = 0;
			}else{
				$access = $access[0]->price;
			}
		}else{
			$access = 0;
		}


		$result = ($sold_price+$access)-($bought_price+$total_used_parts);

		return $result;

    }



    function calculate_sale_by_month($month = false,$boutique_id = false,$fromvar = false, $tovar = false){

	     // BOUGHT AMOUNT
	    $total_amount = 0;
	    $total_used_parts = 0;
	    $total_mail_label = 0;
	    $total_post_label = 0;
	    $bought_price = 0;
	    $sold_price = 0;

	    if($month){
		   $from = mktime(0, 0, 0, date("n"), 1);
		   $to   = mktime(23, 59, 0, date("n"), date("t"));
	    }else{
	    	$from = strtotime(date("Y-m-d").' 00:00:00');
			$to   = strtotime(date("Y-m-d").' 23:59:59');
	    }

	    if($fromvar){
		   $from = strtotime($fromvar.' 00:00:00');
		   $to   = strtotime($tovar.' 23:59:59');
	    }

	    $this->db->where('created_timestamp >=',$from);
	    $this->db->where('created_timestamp <=',$to);
	    if($boutique_id){
			if($boutique_id != 'all'){
				$this->db->where('boutique_id',$boutique_id);
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('type !=','cancelled');
		$this->db->where('hidden',0);
	    $orderlines = $this->db->get('orders')->result();

	    foreach($orderlines as $orderline){

			$total_amount += $orderline->price;

			if($orderline->type == 'sold'){
				$sold_price   += $orderline->price;
			}elseif($orderline->type == 'bought'){
				$bought_price += $orderline->price;
			}

			// calculate use
			$this->db->select('*');
			$this->db->from('parts');
			$this->db->where('parts_used.order_id',$orderline->id);
			$this->db->join('parts_used', 'parts_used.part_id = parts.id');

			$used_parts = $this->db->get()->result();

			foreach($used_parts as $parts){
				$total_used_parts += $parts->price;
			}

	    }


	    $this->db->select_sum('price');

		$this->db->where('created_timestamp >=',$from);
	    $this->db->where('created_timestamp <=',$to);
	    if($boutique_id){
			if($boutique_id != 'all'){
				$this->db->where('boutique_id',$boutique_id);
			}
		}else{
			$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		}
		$this->db->where('type','access');
		$this->db->where('hidden',0);
		$access = $this->db->get('orders')->result();

		if($access){
			if($access[0]->price == false){
				$access = 0;
			}else{
				$access = $access[0]->price;
			}
		}else{
			$access = 0;
		}


		$result = $sold_price+$access;

		return $result;

    }


    function calculate_sale_by_payment_type($payment_type = false,$date,$type = false,$timeonday = false){

	    if($timeonday){
		    $start = strtotime($date." ".$timeonday);
		    $end = strtotime(date("Y-m-d")." 23:59:59");
	    }else{
		    $start = strtotime($date." 00:00:00");
		    $end = strtotime($date." 23:59:59");
	    }

	    $this->db->select_sum('price');
	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $this->db->where('payment_type',$payment_type);
	    $this->db->where('created_timestamp >=',$start);
	    $this->db->where('created_timestamp <=',$end);
	    if($type == false){
		    $this->db->where('type !=','cancelled');
		    $this->db->where('type !=','bought');
		    $this->db->where('type !=','credit');
		    $this->db->where('cancelled',0);
	    }else{
		    $this->db->where('type',$type);
	    }
	    $this->db->where('hidden',0);
	    $sql = $this->db->get('orders')->result();


	    if(!$sql[0]->price){
		    return 0;
	    }else{
	    	return $sql[0]->price;
	    }

    }

    function calculate_sale_by_payment_type_new($payment_type = false,$date,$type = false,$timeonday = false){

	    if($timeonday){
		    $start = strtotime($date." ".$timeonday);
		    $end = strtotime(date("Y-m-d")." 23:59");
	    }else{
		    $start = strtotime($date." 00:00");
		    $end = strtotime($date." 23:59");
	    }


      if($type != 'access'){
        $this->db->select_sum('price');
  	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
  	    $this->db->where('payment_type',$payment_type);
  	    $this->db->where('created_timestamp >=',$start);
  	    $this->db->where('created_timestamp <=',$end);
  	    if($type == false){
          $this->db->where('type !=','access');
  		    $this->db->where('type !=','cancelled');
  		    $this->db->where('type !=','bought');
  		    $this->db->where('type !=','credit');
  		    $this->db->where('cancelled',0);
  	    }else{
  		    $this->db->where('type',$type);
  	    }
  	    $this->db->where('hidden',0);
  	    $sql = $this->db->get('orders')->result();

        $this->db->select_sum('amount');
        $this->db->join("orders","orders.id = order_payments.order_id");
        $this->db->where('order_payments.payment_method',$payment_type);
        $this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
        $this->db->where('orders.created_timestamp >=',$start);
  	    $this->db->where('orders.created_timestamp <=',$end);
        $this->db->where('orders.hidden',0);
        $this->db->where('orders.type','access');
        $access_total = $this->db->get('order_payments');
        $access_total = $access_total->num_rows()?$access_total->row()->amount:0;
  	    if(!$sql[0]->price){
  		    return 0 + $access_total;
  	    }else{
  	    	return $sql[0]->price + $access_total;
  	    }
      }else{
        $this->db->select_sum('amount');
        $this->db->join("orders","orders.id = order_payments.order_id");
        $this->db->where('order_payments.payment_method',$payment_type);
        $this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
        $this->db->where('orders.created_timestamp >=',$start);
  	    $this->db->where('orders.created_timestamp <=',$end);
        $this->db->where('orders.hidden',0);
        $this->db->where('orders.type','access');
        $access_total = $this->db->get('order_payments');
        $access_total = $access_total->num_rows()?$access_total->row()->amount:0;

        return $access_total;
      }


    }


    function get_sale_by_type_cronjob($start_date,$end_date,$type = false,$boutique_id = false){

	    $this->db->select_sum('price');
	    if($boutique_id){
	    $this->db->where('boutique_id',$boutique_id);
	    }
	    $this->db->where('created_timestamp >=',$start_date);
	    $this->db->where('created_timestamp <=',$end_date);
	    $this->db->where('type',$type);
	    $this->db->where('cancelled',0);
	    $this->db->where('hidden',0);
	    $sql = $this->db->get('orders')->result();

	    if(!$sql[0]->price){
		    return 0;
	    }else{
	    	return $sql[0]->price;
	    }

    }

    function calculate_sale_by_payment_typeRevenue($date,$type){

	    $start = strtotime($date." 00:00:00");
	    $end = strtotime($date." 23:59:59");

	    $this->db->select_sum('price');
	    $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
	    $this->db->where('created_timestamp >=',$start);
	    $this->db->where('created_timestamp <=',$end);
	    $this->db->where('type',$type);
	    $this->db->where('hidden',0);
	    $sql = $this->db->get('orders')->result();

	    if(!$sql[0]->price){
		    return 0;
	    }else{
	    	return $sql[0]->price;
	    }

    }

    function calculate_sale_by_payment_typeRevenue_new($date,$type){

	    $start = strtotime($date." 00:00");
	    $end = strtotime($date." 23:59");

      if($type != 'access'){
        $this->db->select_sum('price');
        $this->db->where('boutique_id',$this->session->userdata('active_boutique'));
        $this->db->where('created_timestamp >=',$start);
        $this->db->where('created_timestamp <=',$end);
        $this->db->where('type',$type);
        $this->db->where('hidden',0);
        $sql = $this->db->get('orders')->result();

        $sold_price_new = 0;
        if($type == 'sold'){
          $this->db->select_sum('order_item.total_price_with_discount');
          $this->db->join('orders','orders.id=order_item.order_id');
          $this->db->where('order_item.bought_from_order_id >',0);
          $this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
          $this->db->where('orders.created_timestamp >=',$start);
          $this->db->where('orders.created_timestamp <=',$end);
          $this->db->where('orders.type','access');
          $this->db->where('orders.hidden',0);
          $sold_price_new = $this->db->get('order_item');
          $sold_price_new = $sold_price_new->num_rows()?$sold_price_new->row()->total_price_with_discount:0;
        }

        if(!$sql[0]->price){
          return 0 + $sold_price_new;
        }else{
          return $sql[0]->price + $sold_price_new;
        }
      }elseif($type == 'access'){
        $this->db->select_sum('order_item.total_price_with_discount');
        $this->db->join('orders','orders.id=order_item.order_id');
        $this->db->where('order_item.bought_from_order_id',0);
        $this->db->where('orders.boutique_id',$this->session->userdata('active_boutique'));
        $this->db->where('orders.created_timestamp >=',$start);
        $this->db->where('orders.created_timestamp <=',$end);
        $this->db->where('orders.type','access');
        $this->db->where('orders.hidden',0);
        $access_price_new = $this->db->get('order_item');
        return $access_price_new->num_rows()?$access_price_new->row()->total_price_with_discount:0;
      }


    }


    function log_action($action,$action_id,$from_boutique_id = false,$string = false,$to_boutique = false){

	    $string = array(
	    	'action_id' => $action_id,
	    	'action' => $action,
	    	'created_timestamp' => time(),
	    	'uid' => $this->session->userdata('uid'),
	    	'boutique_id' => $this->session->userdata('active_boutique'),
	    	'from_boutique_id' => $from_boutique_id,
	    	'to_boutique' => $to_boutique,
	    	'string' => $string
	    );
	    $this->db->insert('log',$string);

    }

    function get_active_timer(){
	    $date = date("Y-m-d");

		// check if one is active
		$this->db->where('date',$date);
		$this->db->where('active',1);
		$this->db->where('uid',$this->session->userdata('uid'));
		$timer = $this->db->get('timer')->result();

		return $timer;
    }

	function get_product_name($id=""){
		if($id){
			$q = $this->db->select('name')->where('id',$id)->get('products');
			if($q->num_rows()){
				return $q->row()->name;
			}
		}

		return '';
	}

  function get_garanti(){
    $q = $this->db->where('status',1)->get('garanti');
    if($q->num_rows()){
      return $q->result_array();
    }else{
      return array();
    }
  }

  function get_garanti_by_id($id){
    $q = $this->db->where('id',$id)->get('garanti');
    if($q->num_rows()){
      return $q->row_array();
    }else{
      return false;
    }
  }

  function get_payment(){
    $q = $this->db->where('status',1)->get('payment');
    if($q->num_rows()){
      return $q->result_array();
    }else{
      return array();
    }
  }

  function get_payment_by_id($id){
    $q = $this->db->where('id',$id)->get('payment');
    if($q->num_rows()){
      return $q->row_array();
    }else{
      return false;
    }
  }

}

// end of model file
