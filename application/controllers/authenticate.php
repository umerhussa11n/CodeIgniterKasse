<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticate extends CI_Controller {

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
	public function login()
	{
		
		if($this->input->post('login')){
			$this->load->model('user_model');
			$this->user_model->login();
		}
				
		$data['yield'] = "welcome_message";
		$this->load->view('login/index',$data);
	}
	
	public function choose_boutique()
	{
	
		$this->global_model->check_if_logged_in();
		
		$data['me'] = $this->global_model->me();
		
		if($this->input->post('choose_boutique')){
			$this->load->model('user_model');
			$this->user_model->choose_boutique($data['me']);
		}
		
		$data['yield'] = "choose_boutique";
		$this->load->view('login/choose_boutique',$data);
	}
	
	public function login_status()
	{
		
		if($this->input->post('login')){
			$this->load->model('user_model');
			$this->user_model->login_status();
		}
		
		$data['yield'] = "login/status";
		$this->load->view('login/index',$data);
	}
	
	
	function logout(){
	
		$this->global_model->log_action('logout',$this->session->userdata('uid'));
	
		$this->session->sess_destroy();
		redirect('login');
	}
	
	
	function logout_end_day(){
		
		$this->global_model->check_if_logged_in();
		
		$cash_boutique = $this->input->post('cash_boutique');
		$card_boutique = $this->input->post('card_boutique');
		
		// calculate card / mobilepay / cash
		$card      = $this->global_model->end_of_day_calculate('card');
		$mobilepay = $this->global_model->end_of_day_calculate('mobilepay');
		$cash      = $this->global_model->end_of_day_calculate('cash');
		
		if($card == null){
			$card = 0;
		}
		
		if($mobilepay == null){
			$mobilepay = 0;
		}
		
		if($cash == null){
			$cash = 0;
		}
		
		$total_earnings_today = $cash_boutique+$card_boutique+$card+$mobilepay+$cash;
		
		// check if already exists
		$this->db->where('date',date("Y-m-d",time()));
		$this->db->where('boutique_id',$this->session->userdata('active_boutique'));
		$end_result = $this->db->get('day_results')->result();
		
		$string = array(
			'cash' => $cash,
			'mobilepay' => $mobilepay,
			'card' => $card,
			'cash_boutique' => $cash_boutique,
			'card_boutique' => $card_boutique,
			'total' => $total_earnings_today,
			'boutique_id'   => $this->session->userdata('active_boutique'),
			'uid' => $this->session->userdata('uid'),
			'created_timestamp' => time(),
			'date' => date("Y-m-d",time())
		);
		
		if(!$end_result){
			$this->db->insert('day_results',$string);
		}else{
			$this->db->where('id',$end_result[0]->id);
			$this->db->update('day_results',$string);
		}
		
		
		// logout
		$this->logout();
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */