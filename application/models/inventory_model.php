<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function get_number($gb,$product_id,$boutique_id = false)
    {
    	$this->db->where('gb',$gb);
    	if($boutique_id){
	    	$this->db->where('boutique_id',$boutique_id);
    	}else{
    		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
    	}
    	$this->db->where('product_id',$product_id);
        $query = $this->db->get('inventory')->result();
        if($query){
	        return $query[0]->inventory;
        }else{
	        return 0;
        }
    }
	
	function move_inventory($id){
		
		$from = $this->input->post('from');
		$to   = $this->input->post('to');
		$unique_string = $this->input->post('unique_string');
		$product_id    = $id;
		$amount = $this->input->post('amount');
		
		// get from inventory
		$this->db->where('product_id',$product_id);
		$this->db->where('hide',0);
		$this->db->where('unqiue_string',$unique_string);
		$this->db->where('boutique_id',$from);
		$from_part = $this->db->get('parts')->result();
		
		
		// get to inventory
		$this->db->where('product_id',$product_id);
		$this->db->where('hide',0);
		$this->db->where('unqiue_string',$unique_string);
		$this->db->where('boutique_id',$to);
		$to_part = $this->db->get('parts')->result();
		
		
		if($from_part && $to_part){
			
			$new_from_part = $from_part[0]->inventory-$amount;
			$new_to_part = $to_part[0]->inventory+$amount;
			
			$string = array(
				'inventory' => $new_from_part
			);
			$this->db->where('id',$from_part[0]->id);
			$this->db->update('parts',$string);
			
			
			$string = array(
				'inventory' => $new_to_part
			);
			$this->db->where('id',$to_part[0]->id);
			$this->db->update('parts',$string);
			
		}
		
		$string = json_encode(array(
			'from' => array(
				'inventory' => $from_part[0]->inventory,
				'new_inventory' => $new_from_part,
				'id' 		=> $from_part[0]->id
			),
			'to' => array(
				'inventory' => $to_part[0]->inventory,
				'new_inventory' => $new_to_part,
				'id' 		=> $to_part[0]->id
			)
		));
		
		$this->global_model->log_action('inventory_moved',$id,false,$string);
		
		redirect('products/inventory/parts/'.$product_id);
		
	}
	
	function update_inventory($id){
		
		$this->db->where('gb',$this->input->post('gb_id'));
		$this->db->where('product_id',$id);
		$sql = $this->db->get('inventory')->result();
		
		if($sql){
		
			$string = array(
				'inventory' => $this->input->post('inventory_number')
			);
			$this->db->where('gb',$this->input->post('gb_id'));
			$this->db->where('product_id',$id);
			$this->db->update('inventory',$string);
		
			$update_id = $sql[0]->id;
			
			$this->global_model->log_action('inventory_updated',$update_id);
			
		}else{
			
			$string = array(
				'gb'                 => $this->input->post('gb_id'),
				'product_id'         => $id,
				'created_timestamp'  => time(),
				'boutique_id' => $this->session->userdata('active_boutique'),
			);
			$this->db->insert('inventory',$string);
			
			$update_id = $this->db->insert_id();
			
			$this->global_model->log_action('inventory_created',$update_id);
			
		}
		
		//$this->global_model->log_action('updated_inventory',$update_id);
		
		redirect('products/inventory/devices/'.$id);
		
	}
	
	
	function check_if_gbs_is_created($product_id = false){
		
		$this->db->where('product_id',$product_id);
		$this->db->group_by('name');
        $gbs = $this->db->get('gbs')->result();
        
        foreach($gbs as $gb){
        	
        	$this->db->where('product_id',$product_id);
        	$this->db->where('name',$gb->name);
        	$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
        	$check_if_gb_exist = $this->db->get('gbs')->result();
        	
        	if(!$check_if_gb_exist){
        	
		 	$string = array(
	        	'name'              => $gb->name,
	        	'product_id'        => $product_id,
	        	'boutique_id'       => $this->session->userdata('active_boutique'),
	        	'created_timestamp' => time()
	        );
	        $this->db->insert('gbs',$string);
	        
	        }
	        
		}
		
	}
	
	
	function update_intervals($id){
		
		$gb = $this->input->post('gb_field');
		
		$i = 0;
		
		$this->db->where('product_id',$id);
		$this->db->where('gb',$gb);
		$this->db->delete('intervals');
		
		foreach($this->input->post('from') as $from){
			
			$from  = $_POST['from'][$i];
			$to    = $_POST['to'][$i];
			$price = $_POST['price'][$i];
			$condition = $_POST['condition'][$i];
			
			if($price){
								
				$string = array(
					'from' => $from,
					'to'   => $to,
					'condition' => $condition,
					'price' => $price,
					'gb' => $gb,
					'product_id' => $id,
					'created_timestamp' => time()
				);
				$this->db->insert('intervals',$string);
				
			}
						
			$i++;
			
		}
		
		//$this->global_model->log_action('updated_intervals',$id,$gb);
		
		redirect('products/inventory/devices/'.$id);
		
	}
	
	
	function add_part($id){
		
		$this->load->model('boutique_model');
		
		$boutiques = $this->boutique_model->get();
		
		$stringunique = random_string('alnum', 25);
		
		foreach($boutiques as $boutique):
			
			$string = array(
				'name' => $this->input->post('name'),
				'price' => $this->input->post('price'),
				'part_of_inventory' => $this->input->post('part_of_inventory'),
				'product_id' => $id,
				'created_timestamp' => time(),
				'boutique_id' => $boutique->id,
				'inventory' => 0,
				'unqiue_string' => $stringunique
			);
			$this->db->insert('parts',$string);
			
		endforeach;
				
		//$this->global_model->log_action('created_new_part',$insert_id);
		
		redirect('products/inventory/parts/'.$id);
		
	}
	
	function add_defect($id){
		
		$part_id = $this->input->post('access');
		$desc    = $this->input->post('description');
		$boutique    = $this->input->post('boutique');
		
		$this->load->model('boutique_model');
				
		$string = array(
			'part_id' => $part_id,
			'description' => $desc,
			'product_id' => $id,
			'boutique_id' => $boutique,
			'created_timestamp' => time(),
			'uid' => $this->session->userdata('uid')
		);
		$this->db->insert('defects',$string);
		
		
		$product_redirect_id = $id;
		
		$id = $this->db->insert_id();
		
		$this->global_model->log_action('defect_created',$id);
		
		$insert_id = $this->db->insert_id();
		
		$boutique_info = $this->boutique_model->get_by_id($boutique);
		
		if($boutique_info){
			$unique_name = 'DEF'.$boutique_info->initial.''.$insert_id;
						
		}else{
			$unique_name = $insert_id;
		}
		
		$string = array(
			'unique_name' => $unique_name
		);
		$this->db->where('id',$insert_id);
		$this->db->update('defects',$string);
		
		redirect('products/inventory/parts/'.$product_redirect_id);
		
	}
	
	
	function get_phones_in_boutique($boutique_id,$sortby = false){
		
		$this->db->where('boutique_id',$boutique_id);
		$this->db->where('type','bought');
		$this->db->where('sold',0);
		$this->db->where('fraud',0);
		$this->db->where('defect',0);
		$this->db->where('imei !=','');
		if($sortby == 'id'){
			$this->db->order_by('id');
		}elseif($sortby == 'device'){
			$this->db->order_by('product','asc');
		}elseif($sortby == 'imei'){
			$this->db->order_by('imei');
		}
		$phones = $this->db->get('orders')->result();
		
		return $phones;
		
	}
	
	function edit_part($id){
		
		$unique_string = $this->input->post('unique_string');
		$name = $this->input->post('name');	
		$price = $this->input->post('price');
		$part_of_inventory = $this->input->post('part_of_inventory');
			
		$string = array(
			'name' => $name,
			'price' => $price
		);
		$this->db->where('product_id',$id);
		$this->db->where('unqiue_string',$unique_string);
		$this->db->update('parts',$string);
		
		// update boutique lines
		$i = 0;
		foreach($this->input->post('boutique_id') as $boutique_id){
			
			$inventory = $_POST['boutique_inventory'][$i];
			
			if($inventory > 0){
				// update to new inventory
				$this->db->where('product_id',$id);
				$this->db->where('unqiue_string',$unique_string);
				$this->db->where('boutique_id',$boutique_id);
				$part_update = $this->db->get('parts')->result();
				
				if($part_update){
					$new_inventory = $inventory+$part_update[0]->inventory;
					
					$string = array(
						'inventory' => $new_inventory,
						'part_of_inventory' => $part_of_inventory
					);
					
				}else{
					$string = array(
						'part_of_inventory' => $part_of_inventory
					);
				}
				
			}else{
				$string = array(
					'part_of_inventory' => $part_of_inventory
				);
			}
			
			$this->db->where('product_id',$id);
			$this->db->where('unqiue_string',$unique_string);
			$this->db->where('boutique_id',$boutique_id);
			$this->db->update('parts',$string);
			
			$i++;
			
		}
		
		//$this->global_model->log_action('updated_part',$this->input->post('part_id'));
		
		redirect('products/inventory/parts/'.$id);
		
	}

}

// end of model file