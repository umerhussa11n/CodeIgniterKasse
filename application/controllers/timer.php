<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timer extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->global_model->check_if_logged_in();
        $this->global_model->check_permission('timer_overview');
    }
    
    
	function index(){
		
		$data['yield'] = "timer/index";
		$this->load->view('layout/application',$data);
		
	}
	
	public function action()
	{
	
		$date = date("Y-m-d");
		
		// check if one is active
		$this->db->where('date',$date);
		$this->db->where('active',1);
		$this->db->where('uid',$this->session->userdata('uid'));
		$timer = $this->db->get('timer')->result();
		
		if($timer){
			
			$end_time = time();
			
			$string = array(
				'end' => time(),
				'active' => 0
			);
			$this->db->where('id',$timer[0]->id);
			$this->db->update('timer',$string);
						
			$this->global_model->log_action('timer_stopped',$timer[0]->id);
							
		}else{
			$end_time = 0;
			$string = array(
				'uid' => $this->session->userdata('uid'),
				'start' => time(),
				'date' => $date,
				'active' => 1
			);
			$this->db->insert('timer',$string);
			
			$timer_id = $this->db->insert_id();
			
			$this->global_model->log_action('timer_started',$timer_id);
			
		}
		
		
		// update total timer
		$this->db->where('date',$date);
		$this->db->where('uid',$this->session->userdata('uid'));
		$timer_total = $this->db->get('timer_total')->result();
		
		if($timer_total){
			
			// seconds on count
			if($end_time == false){
				$counted_seconds = 0;
			}else{
				$counted_seconds = $end_time-$timer[0]->start;
			}
			
			$total_seconds = $timer_total[0]->total_seconds+$counted_seconds;
			
			$hours = gmdate("H", $total_seconds);
			$minutes = gmdate("i", $total_seconds);
			$seconds = gmdate("s", $total_seconds);
			
			$string = array(
				'hours' => $hours,
				'minutes' => $minutes,
				'seconds' => $seconds,
				'total_seconds' => $total_seconds
			);
			$this->db->where('id',$timer_total[0]->id);
			$this->db->update('timer_total',$string);
			
		}else{
			
			$string = array(
				'hours' => 0,
				'minutes' => 0,
				'seconds' => 0,
				'total_seconds' => 0,
				'uid' => $this->session->userdata('uid'),
				'date' => date("Y-m-d")
			);
			$this->db->insert('timer_total',$string);
									
		}
	
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */