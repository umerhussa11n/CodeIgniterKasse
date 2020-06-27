<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cronjob extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function send_inventory_list($id = 1)
	{
		
		$start = time();
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$next_query = $this->db->get('export_boutiques')->result();
		
		if(!$next_query){
			// first time running
		}
		
		if(time() > $next_query[0]->next_export){
		
			//$this->db->where('id',$id);
			/*$this->db->where('active',1);
			$boutique = $this->db->get('boutiques')->result();
			
			foreach($boutique as $boutique_info){
			*/
			$handle = "";
			
			//$banme = $boutique_info->name;
			$banme = 'udtraek';
			
			$from = strtotime('2014-01-01 00:00:00');
			$to   = strtotime(''.date("Y-m-d").' 23:59:59');
			
			
			$soldamount = 0;
	
			
			// 31.3.2015 - 1427752800
			// 30.6.2015 - 1435615200
	
			$export_name = $banme.' telefoner';
			$my_file = "uploads/".$export_name.".xls";
	
			if(!file_exists("uploads/".$export_name.".xls")){
	    		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly 
	    	}
	    	
	    	// create attach array
	    	$attach_array[] = $my_file;
	
		    $handle = fopen($my_file, 'w');
		    
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
		    
			//$this->db->where('boutique_id',$boutique_info->id);
			
			$this->db->where('created_timestamp >=',$from);
			$this->db->where('created_timestamp <=',$to);
			
			//$this->db->where('defect',0);
			//$this->db->where('fraud',0);
			
			$orders = $this->db->get('orders')->result();
	
			
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
		    
		    //}
			
			$this->load->library('email');
	
			$this->email->from('no-reply@brugteiphones.dk', '2ndbest');
			//$this->email->to('simon@vato.dk, jty@Weco.dk, jw@2ndbest.dk'); 
			$this->email->to('simon@vato.dk');
			
			$this->email->subject('2ndbest lagerudtræk');
			$this->email->message('Udtræk af lagerlisten for samtlige butikker');	
			
			foreach($attach_array as $attach){
				$this->email->attach($attach);
			}
			
			if($this->email->send()){
			
				$end = time();
				
				$total_exec = $end-$start;
				
				$next_month = date('F',strtotime(''.date('F').' + 1 month'));
				$next_export_timestamp = strtotime("22:00 last day of ".$next_month."");
				
				$string = array(
					'created_timestamp' => time(),
					'next_export' => $next_export_timestamp,
					'execution' => $total_exec
				);
				$this->db->insert('export_boutiques',$string);
				
			}
			
		}else{
			// dont run it
		}
		
	}
	
	
	
	
	public function send_access_list($id = 1)
	{
		
		$start = time();
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$next_query = $this->db->get('export_access')->result();
		
		if(!$next_query){
			// first time running
		}
		
		if(time() > $next_query[0]->next_export){
		
			$export_name = 'Reservedele';
			$my_file = "uploads/".$export_name.".xls";
	
			if(!file_exists("uploads/".$export_name.".xls")){
	    		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly 
	    	}
	    	
	    	// create attach array
	    	$attach_array[] = $my_file;
	
		    $handle = fopen($my_file, 'w');
		    
		    fputcsv($handle, array(
		    	'ID',
		    	'Navn',
		    	'Antal på lager',
		    	'Pris'
		    ),"\t");
		    
		    $this->db->select('inventory, id, name, price');
		    $this->db->select_sum('inventory');
			$this->db->where('part_of_inventory',1);
			$this->db->where('inventory >',0);	
			$this->db->group_by('unqiue_string');	   
			$inventory = $this->db->get('parts')->result();
	
			
			$on_inventory = 0;
			
		    foreach ($inventory as $order) {
		    	
		    	fputcsv($handle, array(
		        	$order->id,
		        	$order->name,
		        	$order->inventory,
		        	number_format($order->price,0)
		        ),"\t");
		        
		    }
	
		    fclose($handle);
			
			$this->load->library('email');
	
			$this->email->from('no-reply@brugteiphones.dk', '2ndbest');
			$this->email->to('simon@vato.dk'); 
			//$this->email->to('simon@vato.dk');
			
			$this->email->subject('2ndbest reservedeludtræk');
			$this->email->message('Udtræk af reservedelslager for samtlige butikker');	
			
			foreach($attach_array as $attach){
				$this->email->attach($attach);
			}
			
			if($this->email->send()){
			
				$end = time();
				
				$total_exec = $end-$start;
				
				$next_month = date('F',strtotime(''.date('F').' + 1 month'));
				$next_export_timestamp = strtotime("22:00 last day of ".$next_month."");
				
				$string = array(
					'created_timestamp' => time(),
					'next_export' => $next_export_timestamp,
					'execution' => $total_exec
				);
				$this->db->insert('export_access',$string);
				
			}
			
		}else{
			// dont run it
		}
		
	}
	
	
	function update_not_sold_to_sold(){
		
		$this->db->where('type','bought');
		$this->db->where('sold',0);
		$orders = $this->db->get('orders')->result();
		
		foreach($orders as $order){
		
			$this->db->where('bought_from_order_id',$order->id);
			$this->db->where('cancelled',0);
			$this->db->where('type','sold');
			$bought_order = $this->db->get('orders')->result();
			
			if($bought_order){
				echo $order->id.' solgt<br />';
				
				$string = array(
					'sold' => 1
				);
				$this->db->where('id',$order->id);
				$this->db->update('orders',$string);
				
			}
		
		}
		
	}
	
	/*function send_inventory_list_new(){
		
		
		//$this->db->where('id',$id);
		$boutique = $this->db->get('boutiques')->result();
		
		foreach($boutique as $boutique_info){
		
		$handle = "";
		
		$banme = $boutique_info->name;
		
		$from = strtotime('2014-01-01 00:00:00');
		$to   = strtotime('2015-10-31 23:59:59');
		
		
		$soldamount = 0;

		
		// 31.3.2015 - 1427752800
		// 30.6.2015 - 1435615200

		$export_name = $banme.' telefoner';
		$my_file = "uploads/".$export_name.".xls";

		if(!file_exists("uploads/".$export_name.".xls")){
    		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly 
    	}
    	
    	// create attach array
    	$attach_array[] = $my_file;

	    $handle = fopen($my_file, 'w');
	    
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
	    
		$this->db->where('boutique_id',$boutique_info->id);
		
		$this->db->where('created_timestamp >=',$from);
		$this->db->where('created_timestamp <=',$to);
		
		//$this->db->where('defect',0);
		//$this->db->where('fraud',0);
		
		$orders = $this->db->get('orders')->result();

		
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
	    
	    }
		
		$this->load->library('email');

		$this->email->from('no-reply@2ndbest.dk', '2ndbest');
		$this->email->to('simon@vato.dk'); 
		//$this->email->to('simon@vato.dk');
		
		$this->email->subject('2ndbest lagerudtræk');
		$this->email->message('Udtræk af lagerlisten for samtlige butikker');	
		
		foreach($attach_array as $attach){
			$this->email->attach($attach);
		}
		
		if($this->email->send()){
			
		}
		
	}
	*/
	
	
	function weeklist(){
		
		if($this->input->get('w')){
			$data['week_number'] = $this->input->get('w');
		}else{
			$data['week_number'] = date("W");
		}
		
		$start = time();
		
		$this->db->order_by('id','desc');
		$this->db->limit(1);
		$next_query = $this->db->get('export_weeklist')->result();
		
		if(!$next_query){
			// first time running
			exit;
		}
		
		if(time() > $next_query[0]->next_export){
		
			$this->load->model('boutique_model');
			
			$data['boutiques'] = $this->boutique_model->get();
					
	    	$message = $this->load->view('cronjob/week',$data,true);
	    
	    
	    	$this->load->library('email');
	
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
	
			$this->email->from('no-reply@brugteiphones.dk', '2ndbest');
			$this->email->to('simon@vato.dk, jw@brugteiphones.dk'); 
			//$this->email->to('simon@vato.dk');
			
			$this->email->subject('Omsætning uge '.$data['week_number'].'');
			$this->email->message($message);	
	
			if($this->email->send()){
				
				$end = time();
				
				$total_exec = $end-$start;
				
				$next_export_timestamp = strtotime('next sunday 22:59:59', time());
				
				$string = array(
					'created_timestamp' => time(),
					'next_export' => $next_export_timestamp,
					'execution' => $total_exec
				);
				$this->db->insert('export_weeklist',$string);
				
			}
		
		}
		
	}
	
	
	
	function week_test(){
		
		$this->load->model('boutique_model');
			
		$data['boutiques'] = $this->boutique_model->get();
				
    	$this->load->view('cronjob/week',$data);
		
	}
	
	function weeklist_november(){
		
		$this->load->model('boutique_model');
			
		$data['boutiques'] = $this->boutique_model->get();
				
    	$message = $this->load->view('cronjob/week',$data,true);
    
    
    	$this->load->library('email');

		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('no-reply@2ndbest.dk', '2ndbest');
		$this->email->to('simon@vato.dk, jw@brugteiphones.dk'); 
		//$this->email->to('simon@vato.dk');
		
		if($this->input->get('week')){
			$weekname = $this->input->get('week');
		}else{
			$weekname = date("W");
		}
		
		$this->email->subject('Omsætning uge '.$weekname.'');
		$this->email->message($message);
		
		$this->email->send();

		
	}
	
	function test(){
		
		
		$next_month = date('F',strtotime(''.date('F').' + 1 month'));
		$next_export_timestamp = strtotime("22:00 last day of ".$next_month."");
		
	}
	
	
	function check_if_counting_is_missing(){
		
		$missing_counting = '';
		$phone_counting = '';
		
		$start_date_today = strtotime("today 00:00:00");
		$end_date_today = strtotime("today 23:59:59");
				
		$boutiques = $this->global_model->get_boutiques();
		
		foreach($boutiques as $boutique){
			
			$this->db->where('created_timestamp >=',$start_date_today);
			$this->db->where('created_timestamp <=',$end_date_today);
			$this->db->where('boutique_id',$boutique->id);
			$sql = $this->db->get('kasseafstemning')->result();
			
			if(!$sql){	
				
				if(!$missing_counting){
					$missing_counting = '<b>Kasseafstemninger der mangler at blive afstemt i dag</b><br />';
				}
				
				$missing_counting = $missing_counting.''.$boutique->name.' <br />';
			}
			
			
			/// check phone
			
			$this->db->where('created_timestamp >=',$start_date_today);
			$this->db->where('created_timestamp <=',$end_date_today);
			$this->db->where('boutique_id',$boutique->id);
			$sql_phone = $this->db->get('telefonafstemning')->result();

			if(!$sql_phone){	
				
				if(!$phone_counting){
					$phone_counting = '<b>Telefonafstemninger der mangler at blive afstemt i dag</b><br />';
				}
				
				$phone_counting = $phone_counting.''.$boutique->name.' <br />';
			}
		
		}
		
		$collect_info = $missing_counting.$phone_counting;
		
		if($collect_info){
			
			// send email with info
			$this->load->library('email');

			$config['mailtype'] = 'html';
			$this->email->initialize($config);
	
			$this->email->from('no-reply@2ndbest.dk', '2ndbest');
			$this->email->to('simon@vato.dk, jw@brugteiphones.dk, rh@brugteiphones.dk'); 

			$this->email->subject('Afstemninger mangler d. '.date("d/m/Y").'');
			$this->email->message($collect_info);
			
			$this->email->send();
			
		}
				
	}
	
	function send_earnings_each_day(){
		
		
		$boutiques = $this->global_model->get_boutiques();
		
		$total_earnings = 0;
		$message = '';
        $total_earnings_month = 0;
        $earnings_month_contest = 0;
        $total_earnings_month_contest = 0;

        foreach($boutiques as $boutique):
        
	        // get earnings
	        $earnings = $this->global_model->calculate_sale_by_month(false,$boutique->id);
			
			$total_sale += $earnings;
			
			$message = $message.'Omsætning '.$boutique->name.': '.number_format($earnings,2,',','.').' kr<br /><br />';
			
        endforeach;
        
        $message = $message.'Total omsætning: '.number_format($total_sale,2,',','.').' kr';
        
        $this->load->library('email');

		$config['mailtype'] = 'html';
		$this->email->initialize($config);

		$this->email->from('no-reply@2ndbest.dk', '2ndbest');
		$this->email->to('simon@vato.dk, rh@brugteiphones.dk, jw@brugteiphones.dk, nf@brugteiphones.dk');
        
        $this->email->subject('Omsætning '.date("d/m/Y").'');
		$this->email->message($message);
		
		$this->email->send();
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */