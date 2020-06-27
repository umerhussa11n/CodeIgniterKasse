<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {


    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_garanti(){
      return $this->db->get('garanti');
    }

    function get_garanti_by_id($id){
      $q = $this->db->where('id',$id)->get('garanti');
      if($q->num_rows()){
        return $q->row_array();
      }else{
        return false;
      }
    }

    function create_garanti(){
      $string = array(
  			'name' => $this->input->post('name'),
  			'text' => $this->input->post('text'),
        'status' => 1
		  );
		  return $this->db->insert('garanti',$string);
    }

    function edit_garanti(){
      $string = array(
        'name' => $this->input->post('name'),
        'text' => $this->input->post('text')
      );
      return $this->db->where('id',$this->input->post('id'))->update('garanti',$string);
    }

    function get_payment(){
      return $this->db->get('payment');
    }

    function create_payment(){
      $string = array(
  			'name' => $this->input->post('name'),
  			'label' => $this->input->post('label'),
        'status' => 1
		  );
		  return $this->db->insert('payment',$string);
    }

    function edit_payment(){
      $string = array(
        'name' => $this->input->post('name'),
        'label' => $this->input->post('label')
      );
      return $this->db->where('id',$this->input->post('id'))->update('payment',$string);
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
