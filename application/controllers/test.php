<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

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
	public function index()
	{
		echo number_format('42653.46',2,',','.');
	}
	
	function weektest(){
		echo strtotime('next sunday 22:59:59', time());
	}
	
	function get_products($boutique_id = 0){
				
		$this->db->group_by('unqiue_string');
		$this->db->where('unqiue_string !=','');
		$parts = $this->db->get('parts')->result();
		
		foreach($parts as $part){
			
			$string = array(
				'name' => $part->name,
				'inventory' => 0,
				'price' => $part->price,
				'product_id' => $part->product_id,
				'created_timestamp' => time(),
				'boutique_id' => $boutique_id,
				'hide' => $part->hide,
				'unqiue_string' => $part->unqiue_string,
				'part_of_inventory' => $part->part_of_inventory
			);
			$this->db->insert('parts',$string);
			
		}
		
	}

	function get_cash(){
		
		$this->db->select_sum('price');
		$this->db->where('boutique_id',5);
		$this->db->where('payment_type','cash');
		$this->db->where('type','sold');
		$this->db->where('cancelled',0);
		$this->db->where('created_timestamp >=',1446332400);
		$this->db->where('created_timestamp <=',1449010799);
		$sql = $this->db->get('orders')->result();
		
		echo $sql[0]->price;
		
	}
	
	
	/*function empty_parts(){
		
		
		$parts = $this->db->get('parts')->result();
		
		foreach($parts as $part){
		
			$this->db->where('part_id',$part->id);
			$defects = $this->db->get('defects')->num_rows();
			
			if($defects > 0){
				$inventory = $defects;
			}else{
				$inventory = 0;
			}
			
			$string = array(
				'inventory' => $inventory
			);
			$this->db->where('id',$part->id);
			$this->db->update('parts',$string);
		
		}
		
	}*/
	
	
	function empty_inventory(){
		
		echo '<meta charset="UTF-8">';
		
		$this->db->where('active',1);
		$boutique = $this->db->get('boutiques')->result();
		
		foreach($boutique as $boutique_info){

			$this->db->where('part_of_inventory',1);
			$this->db->where('price',0);
			$this->db->where('unqiue_string !=','');		   
			$this->db->where('boutique_id',$boutique_info->id);
			$inventory = $this->db->get('parts')->result();
	
			
			$on_inventory = 0;
			
		    foreach ($inventory as $order) {
		    	
		    	$this->db->where('id',$order->product_id);
		    	$productinfo = $this->db->get('products')->result();
		    	
		    	if($productinfo){
			    	$productname = $productinfo[0]->name;
		    	}else{
			    	$productname = '';
		    	}
		    	
		    	echo $order->id.' - '.$order->name.' - '.$boutique_info->name.' - '.$productname.' <br />';
		        
		    }
		    
	    }
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */