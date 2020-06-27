<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Complain_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function create()
    {
        
        $name 			= $this->input->post('name');
        $number 		= $this->input->post('number');
        $email 			= $this->input->post('email');
        $order_id 		= $this->input->post('order_id');
        $imei 			= $this->input->post('imei');
        $model 			= $this->input->post('model');
        $color 			= $this->input->post('color');
        $zipcode 		= $this->input->post('zipcode');
        $city 			= $this->input->post('city');
        $address 		= $this->input->post('address');
        $description 	= $this->input->post('description');
        
        
        $string = array(
        	'name' => $name,
        	'number' => $number,
        	'email' => $email,
        	'order_id' => $order_id,
        	'imei' => $imei,
        	'model' => $model,
        	'color' => $color,
        	'zipcode' => $zipcode,
        	'city' => $city,
        	'address' => $address,
        	'description' => $description,
        	'created_timestamp' => time(),
        	'boutique_id' => $this->session->userdata('active_boutique'),
        	'uid' => $this->session->userdata('uid')
        );
        $this->db->insert('complaints',$string);
        
        $inserted_id = $this->db->insert_id();
        
        redirect('complaints?open_receipt='.$inserted_id);
        
    }

}

// end of model file