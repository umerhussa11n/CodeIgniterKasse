<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
    }

	function index(){

		$data['me'] = $this->global_model->me();

		if($this->input->post('submit')){

			if($this->input->post('type_choose') == 'all'){
				redirect('export/csv_phones_all/'.$this->input->post('boutique').'?from='.$this->input->post('from').'&to='.$this->input->post('to').'&type_choose='.$this->input->post('type_choose'));
			}else{
				redirect('export/'.$this->input->post('type').'/'.$this->input->post('boutique').'?from='.$this->input->post('from').'&to='.$this->input->post('to').'&type_choose='.$this->input->post('type_choose'));
			}

		}

		$data['yield'] = "export/index";
		$this->load->view('layout/application',$data);

	}


	function permission_list(){

		$this->load->view('export/permission');

	}


	function label(){

		$this->load->view('export/label');

	}

	public function print_($id = false)
	{
		$this->db->where('id',$id);
		$data['phone'] = $this->db->get('orders')->result();

		if(!$data['phone']){
			redirect('');
		}

		$data['order_item'] = $this->db->where('order_id',$id)->get('order_item');

		if($data['phone'][0]->type == 'bought'){
			$data['type'] = 'buy';
		}elseif($data['phone'][0]->type == 'access'){
			$data['type'] = 'access';
		}else{
			$data['type'] = 'sale';
		}

		// get boutique info
		$this->db->where('id',$data['phone'][0]->boutique_id);
		$data['boutique_info'] = $this->db->get('boutiques')->result();

		if($data['boutique_info']){
			$data['initial'] = $data['boutique_info'][0]->initial;
			$data['address'] = $data['boutique_info'][0]->address;
			$data['tlfcvrinfo'] = $data['boutique_info'][0]->tlcvremail;
		}else{
			$data['initial'] = '';
			$data['tlfcvrinfo'] = '';
			$data['address'] = '';
		}

		if($data['order_item']->num_rows()){
			$this->load->view('export/print_multi',$data);
		}else{
			$this->load->view('export/print',$data);
		}


	}


	public function print_test($orderid = false,$id = false)
	{

		if($this->input->post('ready')){
			$this->load->model('order_model');
			$this->order_model->test_complete($orderid);
		}

		$data['me'] = $this->global_model->me();

		$data['title'] = 'Test enhed';

		$this->load->model('order_model');
		$data['order'] = $this->order_model->get_order_by_id($orderid,false,false);

		$this->db->where('order_id',$orderid);
		$this->db->where('id',$id);
		$data['test'] = $this->db->get('tests')->result();

		$this->db->where('id',$data['order']->boutique_id);
		$data['boutique'] = $this->db->get('boutiques')->result();

		$this->db->where('id',$data['test'][0]->uid);
		$data['user'] = $this->db->get('users_kasse')->result();

		$this->load->view('export/print_test',$data);
	}


	function csv($id = false){

		/*$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();

		$banme = $boutique[0]->name;
		*/
		$this->db->where('type = \'bought\' OR type = \'sold\'');
		//$this->db->where('boutique_id',$id);
		/*$this->db->where('created_timestamp >=',1427752800);
		$this->db->where('created_timestamp <=',1435615200);*/
		$orders = $this->db->get('orders')->result();


		$soldamount = 0;

		/*foreach ($orders as $order) {


	    	if($order->type == 'sold'){
		    	$this->db->where('bought_from_order_id',$order->id);
				$sold_info = $this->db->get('orders')->result();

				$soldamount += $order->price;

				if($sold_info){

			        //$soldamount = $sold_info[0]->price+$soldamount;

		        }else{

		        }

	    	}elseif($order->type == 'bought'){

	    	}

	    }*/


		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		header("Content-type: application/csv");
	    header("Content-Disposition: attachment; filename=\"butik.csv\"");
	    header("Pragma: no-cache");
	    header("Expires: 0");

	    $handle = fopen('php://output', 'w');
	    fputcsv($handle, array(
	    	'ID',
	    	'Enhed',
	    	'IMEI',
	    	'Købsdato',
	    	'Købspris',
	        'Salgsdato',
	        'Salgspris',
	        'På lager'
	    ));

		$this->db->where('type = \'bought\' OR type = \'sold\'');
		$this->db->where('boutique_id',$id);
		$this->db->where('created_timestamp >=',1427752800);
		$this->db->where('created_timestamp <=',1435615200);
		$orders = $this->db->get('orders')->result();


	    foreach ($orders as $order) {

	    	if($order->type == 'sold'){
		    	$this->db->where('id',$order->bought_from_order_id);
				$sold_info = $this->db->get('orders')->result();


				if($sold_info){

			        fputcsv($handle, array(
			        	$order->id,
			        	$order->product.' '.$order->gb,
			        	$order->imei,
			        	date("d/m/Y",$sold_info[0]->created_timestamp),
			        	number_format($sold_info[0]->price,0,',',''),
			            date("d/m/Y",$order->created_timestamp),
			            number_format($order->price,0,',',''),
			            0
			        ));

		        }else{

			        fputcsv($handle, array(
			        	$order->id,
			        	$order->product.' '.$order->gb,
			        	$order->imei,
			        	date("d/m/Y",$order->created_timestamp),
			        	number_format($order->price,0,',',''),
			        	'',
			            '',
			            0
			        ));

		        }

	    	}elseif($order->type == 'bought'){
		    	$this->db->where('bought_from_order_id',$order->id);
				$sold_info = $this->db->get('orders')->result();


				if($sold_info){

			        fputcsv($handle, array(
			        	$order->id,
			        	$order->product.' '.$order->gb,
			        	$order->imei,
			        	date("d/m/Y",$order->created_timestamp),
			        	number_format($order->price,0,',',''),
			        	date("d/m/Y",$sold_info[0]->created_timestamp),
			            number_format($sold_info[0]->price,0,',',''),
			            0
			        ));

		        }else{

			        fputcsv($handle, array(
			        	$order->id,
			        	$order->product.' '.$order->gb,
			        	$order->imei,
			        	date("d/m/Y",$order->created_timestamp),
			        	number_format($order->price,0,',',''),
			        	'',
			            '',
			            1
			        ));

		        }

	    	}

	    }


	    fclose($handle);
	    exit;

	}




	function csv_phones($id = false){

		$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();

		$banme = $boutique[0]->name;

		$from = strtotime($this->input->get('from').' 00:00:00');
		$to   = strtotime($this->input->get('to').' 23:59:59');

		$this->db->where('type = \'bought\'');
		//$this->db->where('boutique_id',$id);
		/*$this->db->where('created_timestamp >=',1427752800);
		$this->db->where('created_timestamp <=',1435615200);*/
		$orders = $this->db->get('orders')->result();


		$soldamount = 0;

		/*foreach ($orders as $order) {


	    	if($order->type == 'sold'){
		    	$this->db->where('bought_from_order_id',$order->id);
				$sold_info = $this->db->get('orders')->result();

				$soldamount += $order->price;

				if($sold_info){

			        //$soldamount = $sold_info[0]->price+$soldamount;

		        }else{

		        }

	    	}elseif($order->type == 'bought'){

	    	}

	    }*/


		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		$export_name = $banme.' telefoner - '.date("d/m/Y");

		header("Content-type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=\"$export_name.xls\"");
	    header("Pragma: no-cache");
	    header("Expires: 0");

	    $handle = fopen('php://output', 'w');

	    if($this->input->get('type_choose') == 'sale'){
		    fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Enhed',
		    	'IMEI',
		    	'Salgsdato',
		    	'Salgspris',
		    ),"\t");

			$this->db->where('type = \'sold\'');
			$this->db->where('cancelled',0);

		}elseif($this->input->get('type_choose') == 'buy' || $this->input->get('type_choose') == 'phones_on_inventory'){

			fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Enhed',
		    	'IMEI',
		    	'Købsdato',
		    	'Købspris',
		        'På lager'
		    ),"\t");

			$this->db->where('type = \'bought\'');
			$this->db->where('cancelled',0);

			if($this->input->get('type_choose') == 'phones_on_inventory'){
				$this->db->where('sold',0);
			}

		}elseif($this->input->get('type_choose') == 'fraud'){

			fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Enhed',
		    	'IMEI',
		    	'Købsdato',
		    	'Købspris',
		    	'Overført til svind',
		    	'Overført af'
		    ),"\t");

			$this->db->where('type = \'bought\'');
			$this->db->where('fraud',1);
			$this->db->where('cancelled',0);

		}elseif($this->input->get('type_choose') == 'defect'){

			fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Enhed',
		    	'IMEI',
		    	'Købsdato',
		    	'Købspris',
		    	'Overført til defekt',
		    	'Overført af'
		    ),"\t");

			$this->db->where('type = \'bought\'');
			$this->db->where('defect',1);
			$this->db->where('cancelled',0);

		}else{
			fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Enhed',
		    	'IMEI',
		    	'Købsdato',
		    	'Købspris',
		    	'Salgsdato',
		    	'Salgspris',
		        'På lager'
		    ),"\t");

		    $this->db->where('(type = \'bought\' OR type = \'sold\')');
		    $this->db->where('cancelled',0);

		}
		if($id){
		$this->db->where('boutique_id',$id);
		}
		if($this->input->get('type_choose') == 'phones_on_inventory' || $this->input->get('type_choose') == 'fraud' || $this->input->get('type_choose') == 'defect'){}else{
		$this->db->where('created_timestamp >=',$from);
		$this->db->where('created_timestamp <=',$to);
		}

		if($this->input->get('type_choose') == 'fraud' || $this->input->get('type_choose') == 'defect'){}else{
			$this->db->where('defect',0);
			$this->db->where('fraud',0);
		}

		$orders = $this->db->get('orders')->result();

		/*echo '<pre>';
		print_r($orders);
		echo '</pre>';
		exit;
		*/

		$on_inventory = 0;

	    foreach ($orders as $order) {

	    	if($order->type == 'sold'){
		    	$this->db->where('id',$order->bought_from_order_id);
		    	$this->db->where('cancelled',0);
				$sold_info = $this->db->get('orders')->result();


				if($sold_info){

		    		if($this->input->get('type_choose') == 'all'){
			    		fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$sold_info[0]->created_timestamp),
				        	number_format($sold_info[0]->price,0,',',''),
				            date("d/m/Y",$order->created_timestamp),
				            number_format($order->price,0,',',''),
				            0
				        ),"\t");
				    }elseif($this->input->get('type_choose') == 'fraud' || $this->input->get('type_choose') == 'defect'){

				    	$this->db->where('action_id',$order->id);
				    	$this->db->order_by('id','desc');
				    	$this->db->limit(1);
				    	if($this->input->get('type_choose') == 'fraud'){
				    		$this->db->where('action','fraud_order');
				    	}else{
					    	$this->db->where('action','defect_order');
				    	}
				    	$log = $this->db->get('log')->result();

				    	if($log){

				    		$this->db->where('id',$log[0]->uid);
				    		$loguser = $this->db->get('users_kasse')->result();

				    		if($loguser){
					    		$log_name = $loguser[0]->name;
				    		}else{
					    		$log_name = '?';
				    		}

					    	$log_date = date("d/m/Y H:i",$log[0]->created_timestamp);
				    	}else{
					    	$log_name = '?';
					    	$log_date = '?';
				    	}

			    		fputcsv($handle, array(
				        	$order->id,
				        	$sold_info[0]->name,
				        	$sold_info[0]->product.' '.$sold_info[0]->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	$log_date,
				        	$log_name
				        ),"\t");
		    		}else{
				        fputcsv($handle, array(
				        	$order->id,
				        	$sold_info[0]->name,
				        	$sold_info[0]->product.' '.$sold_info[0]->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        ),"\t");
			        }

		        }else{

			        if($this->input->get('type_choose') == 'all'){

			        	fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	'',
				            '',
				            0
				        ),"\t");

					}elseif($this->input->get('type_choose') == 'fraud' || $this->input->get('type_choose') == 'defect'){

				    	$this->db->where('action_id',$order->id);
				    	$this->db->order_by('id','desc');
				    	$this->db->limit(1);
				    	if($this->input->get('type_choose') == 'fraud'){
				    		$this->db->where('action','fraud_order');
				    	}else{
					    	$this->db->where('action','defect_order');
				    	}
				    	$log = $this->db->get('log')->result();

				    	if($log){

				    		$this->db->where('id',$log[0]->uid);
				    		$loguser = $this->db->get('users_kasse')->result();

				    		if($loguser){
					    		$log_name = $loguser[0]->name;
				    		}else{
					    		$log_name = '?';
				    		}

					    	$log_date = date("d/m/Y H:i",$log[0]->created_timestamp);
				    	}else{
					    	$log_name = '?';
					    	$log_date = '?';
				    	}

			    		fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	$log_date,
				        	$log_name
				        ),"\t");

			        }else{

				        fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        ),"\t");

			        }

		        }

	    	}elseif($order->type == 'bought'){
		    	$this->db->where('bought_from_order_id',$order->id);
		    	$this->db->where('cancelled',0);
				$sold_info = $this->db->get('orders')->result();

				if($sold_info){
					$inventory = 0;
				}else{
					$inventory = 1;
					$on_inventory += 1;
				}

				if($sold_info){

					if($this->input->get('type_choose') == 'phones_on_inventory'){}else{

					if($this->input->get('type_choose') == 'all'){

						fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	date("d/m/Y",$sold_info[0]->created_timestamp),
				        	number_format($sold_info[0]->price,0,',',''),
				            $inventory
				        ),"\t");

					}else{

						fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				            $inventory
				        ),"\t");

			        }

			        }

				}else{

					if($this->input->get('type_choose') == 'all'){

						fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	'',
				        	'',
				            $inventory
				        ),"\t");

					}elseif($this->input->get('type_choose') == 'fraud' || $this->input->get('type_choose') == 'defect'){

				    	$this->db->where('action_id',$order->id);
				    	$this->db->order_by('id','desc');
				    	$this->db->limit(1);
				    	if($this->input->get('type_choose') == 'fraud'){
				    		$this->db->where('action','fraud_order');
				    	}else{
					    	$this->db->where('action','defect_order');
				    	}
				    	$log = $this->db->get('log')->result();

				    	if($log){

				    		$this->db->where('id',$log[0]->uid);
				    		$loguser = $this->db->get('users_kasse')->result();

				    		if($loguser){
					    		$log_name = $loguser[0]->name;
				    		}else{
					    		$log_name = '?';
				    		}

					    	$log_date = date("d/m/Y H:i",$log[0]->created_timestamp);
				    	}else{
					    	$log_name = '?';
					    	$log_date = '?';
				    	}

			    		fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				        	$log_date,
				        	$log_name
				        ),"\t");

					}else{

						fputcsv($handle, array(
				        	$order->id,
				        	$order->name,
				        	$order->product.' '.$order->gb,
				        	$order->imei,
				        	date("d/m/Y",$order->created_timestamp),
				        	number_format($order->price,0,',',''),
				            $inventory
				        ),"\t");

			        }

		        }

	    	}

	    }


	    /*fputcsv($handle, array(
        	'#',
        	'#',
        	'#',
        	'#',
        	'200.450',
            $on_inventory
        ));*/

	    fclose($handle);
	    exit;

	}






	function csv_phones_all($id = false){

		$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();

		$banme = $boutique[0]->name;

		$from = strtotime($this->input->get('from').' 00:00:00');
		$to   = strtotime($this->input->get('to').' 23:59:59');


		$soldamount = 0;


		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		$export_name = $banme.' telefoner - '.date("d/m/Y");

		header("Content-type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=\"$export_name.xls\"");
	    header("Pragma: no-cache");
	    header("Expires: 0");


	    $handle = fopen('php://output', 'w');

	    fputcsv($handle, array(
	    	'ID',
	    	'Navn',
	    	'Enhed',
	    	'IMEI',
	    	'Købsdato',
	    	'Købspris',
	    	'Salgsdato',
	    	'Salgspris',
	        'På lager',
	        'Svind',
	        'Defekt'
	    ),"\t");

	    $this->db->where('type','bought');

		if($id){
		$this->db->where('boutique_id',$id);
		}

		$this->db->where('created_timestamp >=',$from);
		$this->db->where('created_timestamp <=',$to);

		//$this->db->where('defect',0);
		//$this->db->where('fraud',0);
		$this->db->where('cancelled',0);
		$orders = $this->db->get('orders')->result();

		/*echo '<pre>';
		print_r($orders);
		echo '</pre>';
		exit;*/


		$on_inventory = 0;

	    foreach ($orders as $order) {

	    	$this->db->where('bought_from_order_id',$order->id);
	    	$this->db->where('cancelled',0);
			$sold_info = $this->db->get('orders')->result();

			if($sold_info){
				$inventory = 0;
			}else{
				$inventory = 1;
				$on_inventory += 1;
			}

			if($sold_info){

				fputcsv($handle, array(
		        	$order->id,
		        	$order->name,
		        	$order->product.' '.$order->gb,
		        	$order->imei,
		        	date("d/m/Y",$order->created_timestamp),
		        	number_format($order->price,0,',',''),
		        	date("d/m/Y",$sold_info[0]->created_timestamp),
		        	number_format($sold_info[0]->price,0,',',''),
		            $inventory,
		            $order->fraud,
		            $order->defect
		        ),"\t");

			}else{

				fputcsv($handle, array(
		        	$order->id,
		        	$order->name,
		        	$order->product.' '.$order->gb,
		        	$order->imei,
		        	date("d/m/Y",$order->created_timestamp),
		        	number_format($order->price,0,',',''),
		        	'',
		        	'',
		            $inventory,
		            $order->fraud,
		            $order->defect
		        ),"\t");

	        }

	    }

	    fclose($handle);
	    exit;

	}



	function csv_access_export($id = false){

		$from = strtotime($this->input->post('from').' 00:00:00');
		$to   = strtotime($this->input->post('to').' 23:59:59');

		$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();

		$banme = $boutique[0]->name;

		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		$export_name = $banme.' tilbehør - '.date("d/m/Y");

		header("Content-type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=\"$export_name.xls\"");
	    header("Pragma: no-cache");
	    header("Expires: 0");

	    $handle = fopen('php://output', 'w');
	    fputcsv($handle, array(
	    	'Ordre nr',
	    	'Beskrivelse',
	        'Salgsdato',
	        'Pris',
	    ),"\t");

		$this->db->where('type','access');
		$this->db->where('boutique_id',$id);
		$this->db->where('cancelled',0);
		$this->db->where('created_timestamp >=',$from);
		$this->db->where('created_timestamp <=',$to);
		$orders = $this->db->get('orders')->result();


	    foreach ($orders as $order) {

	    	fputcsv($handle, array(
	        	$order->id,
	        	$order->part,
	            date("d/m/Y",$order->created_timestamp),
	            number_format($order->price,0,',','')
	        ),"\t");

	    }

	    fclose($handle);
	    exit;

	}



	function csv_access(){

		header("Content-type: application/vnd.ms-excel");
	    header("Content-Disposition: attachment; filename=\"bi_access.xls\"");
	    header("Pragma: no-cache");
	    header("Expires: 0");

	    $handle = fopen('php://output', 'w');
	    fputcsv($handle, array(
	    	'ID',
	        'Pris',
	        'Part',
	        'Dato',
	    ),"\t");

		$this->db->where('type','access');
		$orders = $this->db->get('orders')->result();

	    foreach ($orders as $order) {

	    	fputcsv($handle, array(
	        	$order->id,
	            number_format($order->price,0,',',''),
	            $order->part,
	            date("d/m/Y H:i",$order->created_timestamp)
	        ),"\t");

	    }

	    fclose($handle);
	    exit;

	}


	function part_list($boutique_id = false, $product_id = false){


		$data['yield'] = "export/inventory";
		$this->load->view('layout/application_raw',$data);

	}






	function csv_phones_all_excel_test($id = false){

		$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();

		$banme = $boutique[0]->name;

		$from = strtotime($this->input->get('from').' 00:00:00');
		$to   = strtotime($this->input->get('to').' 23:59:59');


		$soldamount = 0;


		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		$export_name = $banme.' telefoner - '.date("d/m/Y");

	    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$export_name.xls");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);


	    $handle = fopen('php://output', 'w');

	    fputcsv($handle, array(
	    	'ID',
	    	'Navn',
	    	'Enhed',
	    	'IMEI',
	    	'Købsdato',
	    	'Købspris',
	    	'Salgsdato',
	    	'Salgspris',
	        'På lager',
	        'Svind',
	        'Defekt'
	    ),"\t");

	    $this->db->where('type','bought');

		if($id){
		$this->db->where('boutique_id',$id);
		}

		$this->db->where('created_timestamp >=',$from);
		$this->db->where('created_timestamp <=',$to);

		//$this->db->where('defect',0);
		//$this->db->where('fraud',0);

		$orders = $this->db->get('orders')->result();

		/*echo '<pre>';
		print_r($orders);
		echo '</pre>';
		exit;*/


		$on_inventory = 0;

	    foreach ($orders as $order) {

	    	$this->db->where('bought_from_order_id',$order->id);
			$sold_info = $this->db->get('orders')->result();

			if($sold_info){
				$inventory = 0;
			}else{
				$inventory = 1;
				$on_inventory += 1;
			}

			if($sold_info){

				fputcsv($handle, array(
		        	$order->id,
		        	$order->name,
		        	$order->product.' '.$order->gb,
		        	$order->imei,
		        	date("d/m/Y",$order->created_timestamp),
		        	number_format($order->price,0,',',''),
		        	date("d/m/Y",$sold_info[0]->created_timestamp),
		        	number_format($sold_info[0]->price,0,',',''),
		            $inventory,
		            $order->fraud,
		            $order->defect
		        ),"\t");

			}else{

				fputcsv($handle, array(
		        	$order->id,
		        	$order->name,
		        	$order->product.' '.$order->gb,
		        	$order->imei,
		        	date("d/m/Y",$order->created_timestamp),
		        	number_format($order->price,0,',',''),
		        	'',
		        	'',
		            $inventory,
		            $order->fraud,
		            $order->defect
		        ),"\t");

	        }

	    }

	    fclose($handle);
	    exit;

	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
