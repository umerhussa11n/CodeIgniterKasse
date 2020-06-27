<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends CI_Controller {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }

	public function index()
	{
		$boutique_id = $this->session->userdata('active_boutique');
		$this->global_model->check_if_logged_in();
		$q = $this->db->query("SELECT *,CONCAT(date,'T',time) as start, booking.repair as title FROM booking WHERE boutique_id = '$boutique_id'");
		$bookings = array();
		if($q->num_rows()){
			 $bookings = $q->result_array();
		}

		$data['bookings'] = json_encode($bookings);
		$data['yield'] = "booking/index";
		$this->load->view('layout/application',$data);
	}

	public function delete_booking(){
		$id = $this->input->post('id');
		$this->db->where('id',$id)->delete('booking');
		echo 'ok';
	}

	public function update_booking(){
			$id = $this->input->post('booking_id');
			if(!$id) return false;

			$date = $this->input->post('booking_date');
			$time = $this->input->post('booking_time');
			$note = $this->input->post('booking_note');

			$data = array(
				'date' => date("Y-m-d",strtotime($date)),
				'time' => date("H:i:s",strtotime($time)),
				'note' => $note
			);

			$this->db->where('id',$id)->update('booking',$data);

			echo 'ok';
	}

	public function save_booking(){
		header("Access-Control-Allow-Origin: https://www.telerepair.dk");



		$brand = $this->input->post('brand')?$this->input->post('brand'):'';
		$model = $this->input->post('model')?$this->input->post('model'):'';
		$repair = $this->input->post('repair');
		$store = $this->input->post('store_name');
		$date = $this->input->post('date');
		$name = $this->input->post('name');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		$message = $this->input->post('message');
		$time = $this->input->post('time');
		$page_source = $this->input->post('HTTP_REFERER');

		if($name && $email && $phone && $store && $repair && $date && $time){
			/*
			if(!$this->db->from('customers')->where('email',$email)->count_all_results()){
				$customer_data = array(
					'name'=> $name,
					'email' => $email,
					'phone' => $phone,
					'created_timestamp' => date("Y-m-d H:i:s")
				);
				$this->db->insert('customers',$customer_data);
			}
			*/
			$boutique_id = 0;
			$q = $this->db->select('id')->where('name',$store)->where('active',1)->get('boutiques');
			if($q->num_rows()){
				$boutique_id = $q->row()->id;
			}
			$date = date("Y-m-d", strtotime($date));
			$time = date("H:i:s",strtotime($time));
			$data = array(
				'name'=> $name,
				'email' => $email,
				'phone' => $phone,
				'store' => $store,
				'repair' => implode(', ',$repair),
				'date' => $date,
				'time' => $time,
				'brand' => $brand,
				'model' => $model,
				'create_date' => date("Y-m-d H:i:s"),
				'message' => $message,
				'boutique_id' => $boutique_id,
				'page_source' => $page_source
			);

			$this->db->insert('booking',$data);
			
			if(!$this->db->from('customers')->where('phone',$phone)->count_all_results()){
				  $customer_data = array(
					'name' => $name,
					'email' => $email,
					'phone' => $phone,
					'created_timestamp' => date("Y-m-d H:i:s"),
					'type' => 'company'
				  );

				  $this->db->insert('customers',$customer_data);
			  }
	  
			echo "ok";
		}else{
			echo "Fill up all required fields";
		}
	}

	private function danishStrtotime($date_string) {
  $date_string = str_replace('.', '', $date_string); // to remove dots in short names of months, such as in 'janv.', 'févr.', 'avr.', ...
  return strtotime(
    trim(
			strtr(
	      strtolower($date_string), array(
					'januar'=>'jan',
	        'februar'=>'feb',
	        'marts'=>'march',
	        'april'=>'apr',
	        'maj'=>'may',
	        'juni'=>'jun',
	        'juli'=>'jul',
	        'august'=>'aug',
	        'september'=>'sep',
	        'oktober'=>'oct',
	        'november'=>'nov',
	        'december'=>'dec',
	        'mandag' => '',//monday
	        'tirsdag' => '',//tuesday
	        'onsdag' => '',//wednesday
	        'torsdag' => '',//thursday
	        'fredag' => '',//friday
	        'lørdag' => '',//saturday
	        'søndag' => ''//sunday
				)
	    )
		)
  );
}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
