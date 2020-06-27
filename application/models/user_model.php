<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    var $title   = '';
    var $content = '';
    var $date    = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function login()
    {
    
    	$personal_id = $this->input->post('personal_id');
    	$password = $this->input->post('password');
        
        $this->db->where('active',1);
        $this->db->where('personal_id',$personal_id);
        $this->db->where('password',sha1($password));
        $sql = $this->db->get('users_kasse')->result();
 
        if($sql){
           
           if($sql[0]->boutiques){
	           $explode_boutiques = explode(",",$sql[0]->boutiques);
	           
	           $count_boutiques = count($explode_boutiques);
	           
	           if($count_boutiques == 1){
		           $active_boutique = $sql[0]->boutiques;
	           }else{
		           $active_boutique = $explode_boutiques[0];
	           }
	           
           }else{
	           $active_boutique = 0;
           }
           
           $string = array(
		   	'last_login' => time()
           );
           $this->db->where('id',$sql[0]->id);
           $this->db->update('users_kasse',$string);
        	
	       $newdata = array(
               'uid'  => $sql[0]->id,
               'personal_id'     => $personal_id,
               'logged_in' => TRUE
           );

		   $this->session->set_userdata($newdata);
		   		   
		   redirect('choose_boutique');
		   
        }else{
	        redirect('login');
        }
        
    }
    
    function choose_boutique($me){
	    
	    $boutique_id = $this->input->post('id');
	    
	    $my_boutiques = explode(",",$me[0]->boutiques);
	    
	    if(in_array($boutique_id,$my_boutiques)){

		    $newdata = array(
               'active_boutique' => $boutique_id
           );

		   $this->session->set_userdata($newdata);
		   
		    $this->global_model->log_action('login',$me[0]->id);
		   
		   redirect('');
		   
	    }else{

		    redirect('choose_boutique');
	    }
	    
    }
    
    function create_user(){
	    
	    $name            = $this->input->post('name');
	    $personal_id     = $this->input->post('personal_id');
	    $password        = $this->input->post('password');
	    $rank            = $this->input->post('rank');
	    
	    $boutiques     = $this->input->post('boutiques');
	    
	    $boutiques_list = false;
	    foreach($boutiques as $boutique){
	    	if($boutiques_list){
		    	$boutiques_list = $boutiques_list.', '.$boutique;
	    	}else{
		    	$boutiques_list = $boutique;
	    	}
	    }
	    
	    $string = array(
			'name' => $name,
			'personal_id' => $personal_id,
			'boutiques' => $boutiques_list,
			'password' => sha1($password),
			'last_login' => 0,
			'active' => 1,
			'created_timestamp' => time(),
			'rank'              => $rank
		);
		$this->db->insert('users_kasse',$string);
		
		$id = $this->db->insert_id();
		
		$this->global_model->log_action('user_created',$id);
		
		//$this->global_model->log_action('created_new_admin',$id);
		
		redirect('users');
	    
    }
    
    
    function update(){
	    
	    $name            = $this->input->post('name');
	    $personal_id     = $this->input->post('personal_id');
	    $password        = $this->input->post('password');
	    $rank            = $this->input->post('rank');
	    
	    $uid             = $this->input->post('uid');
	    
	    $boutiques       = $this->input->post('boutiques');
	    
	    $boutiques_list  = false;
	    foreach($boutiques as $boutique){
	    	if($boutiques_list){
		    	$boutiques_list = $boutiques_list.', '.$boutique;
	    	}else{
		    	$boutiques_list = $boutique;
	    	}
	    }
	    
	    if($password){
		    $string = array(
				'name'              => $name,
				'personal_id'       => $personal_id,
				'password'          => sha1($password),
				'boutiques'         => $boutiques_list,
				'rank'              => $rank
			);
	    }else{
		    $string = array(
				'name'              => $name,
				'personal_id'       => $personal_id,
				'boutiques'         => $boutiques_list,
				'rank'              => $rank
			);
	    }
	    
	    
		$this->db->where('id',$uid);
		$this->db->update('users_kasse',$string);
		
		$id = $this->db->insert_id();
		
		$this->global_model->log_action('user_updated',$uid);
		
		//$this->global_model->log_action('created_new_admin',$id);
		
		redirect('users');
	    
    }
    
    
    function create_permission(){
	    
	    $name            = $this->input->post('name');
	    $permissions     = $this->input->post('permissions');
	    	    
	    $permission_list = false;
	    foreach($permissions as $permission){
	    	if($permission_list){
		    	$permission_list = $permission_list.', '.$permission;
	    	}else{
		    	$permission_list = $permission;
	    	}
	    }
	    
	    $string = array(
			'name' => $name,
			'permission' => $permission_list,
			'created_timestamp' => time(),
		);
		$this->db->insert('ranks',$string);
		
		$id = $this->db->insert_id();
		
		$this->global_model->log_action('permission_created',$id);
		
		redirect('users/permissions');
	    
    }
    
    
    function update_permission(){
	    
	    $id              = $this->input->post('id');
	    $name            = $this->input->post('name');
	    $permissions     = $this->input->post('permissions');
	    
	    $permission_list = false;
	    foreach($permissions as $permission){
	    	if($permission_list){
		    	$permission_list = $permission_list.', '.$permission;
	    	}else{
		    	$permission_list = $permission;
	    	}
	    }

	    
	    $string = array(
			'name' => $name,
			'permission' => $permission_list
		);
		$this->db->where('id',$id);
		$this->db->update('ranks',$string);
				
		$this->global_model->log_action('permission_updated',$id);
		
		redirect('users/permissions');
	    
    }
    
    
    function get_users(){
	    
	    $this->db->where('active',1);
	    $this->db->order_by('id','desc');
	    $users = $this->db->get('users_kasse')->result();
	    return $users;
	    
    }
    
    function get_user_by_id($id = false){
	    
	    $this->db->order_by('id','desc');
	    $this->db->where('active',1);
	    $this->db->where('id',$id);
	    $users = $this->db->get('users_kasse')->result();
	    return $users;
	    
    }
    
    function get_ranks(){
	    
	    $this->db->order_by('name','asc');
	    $this->db->where('active',1);
	    $ranks = $this->db->get('ranks')->result();
	    return $ranks;
	    
    }
    
    
    function get_rank_by_id($id){
	    
	    $this->db->order_by('name','asc');
	    $this->db->where('active',1);
	    $this->db->where('id',$id);
	    $ranks = $this->db->get('ranks')->result();
	    return $ranks;
	    
    }

}

// end of model file