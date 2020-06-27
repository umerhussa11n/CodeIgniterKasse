<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Email Library that will be used in overall site for sending emails
 */
class Email_manager {

    public $protocol = "mail";
    public $smtp_host = "";
    public $smtp_user = "";
    public $smtp_pass = ""; //++RelTest
    public $smtp_port = "";
    public $email_from = "info@telrep.dk";
    public $email_from_name = "Telrep.dk";
    public $mailtype = "html";

    public function __construct() {
      $CI = &get_instance();
      $boutique_id = $CI->session->userdata('active_boutique');
      $q = $CI->db->where('id',$boutique_id)->get('boutiques');
      if($q->num_rows()){
        $boutique = $q->row_array();
        if($boutique['smtp_username'] && $boutique['smtp_password'] && $boutique['smtp_host'] && $boutique['smtp_port']){
          $this->protocol = "smtp";
          $this->smtp_host = $boutique['smtp_host'];
          $this->smtp_user = $boutique['smtp_username'];
          $this->smtp_pass = $boutique['smtp_password'];
          $this->smtp_port = $boutique['smtp_port'];
          $this->email_from = $boutique['smtp_username'];
        }
      }
    }

    /**
     * This is the main method for sending email.
     * All other methods will set the public properties
     * if needed and use this method to send email
     * @param string $email_to
     * @param string $sub
     * @param string $msg
     * @param string $cc
     * @return boolean true if successfully sent
     * @author Ashikur Rahman
     */
    public function send_email($email_to, $sub, $msg, $cc = "", $attach = "") {
        $CI = &get_instance();
        $CI->load->library('email');

        $email_from = $this->email_from;
        $email_from_name = $this->email_from_name;

        $config['protocol'] = $this->protocol;
        $config['smtp_host'] = $this->smtp_host;
        $config['smtp_user'] = $this->smtp_user;
        $config['smtp_pass'] = $this->smtp_pass;
        $config['smtp_port'] = $this->smtp_port;
        $config['mailtype'] = $this->mailtype;



        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';

        $CI->email->initialize($config);
        $CI->email->from($email_from, $email_from_name);
        $CI->email->to($email_to);

        if ($cc != "") {
            $CI->email->cc($cc);
        }
        if (!empty($attach)) {
          if(is_array($attach)){
            foreach($attach as $file){
              $CI->email->attach($file);
            }
          }else{
            $CI->email->attach($attach);
          }

        }
        $CI->email->subject($sub);
        $CI->email->message($msg);

        return $CI->email->send();

        //echo $CI->email->print_debugger();
    }


    public function order_notification($order_id) {
        $CI = &get_instance();
        $order = $CI->db->where('id', $order_id)->get('orders');
    		if(!$order->num_rows()){

    			return false;
    		}
        //$user_email = $user->user_email;
        $data['order'] = $order->row_array();
        if(!$data['order']['email']) return false;

        $data['items'] = $CI->db->where('order_id', $order_id)->get('order_item')->result_array();
        $data['order_id'] = $order_id;

        $CI->db->where('id',$data['order']['boutique_id']);
    		$data['boutique_info'] = $CI->db->get('boutiques')->result();

    		if($data['boutique_info']){
    			$data['initial'] = $data['boutique_info'][0]->initial;
    			$data['address'] = $data['boutique_info'][0]->address;
    			$data['tlfcvrinfo'] = $data['boutique_info'][0]->tlcvremail;
    		}else{
    			$data['initial'] = '';
    			$data['tlfcvrinfo'] = '';
    			$data['address'] = '';
    		}

        $sub = "Kvittering #$order_id fra Telerepair";
        $msg = $CI->load->view('email/order_notification', $data, true);
        return $this->send_email($data['order']['email'], $sub, $msg);
    }


    //Like above, create seperate methods for each email event and call it with required parameters where needed.
}
