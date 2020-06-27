<?php
 if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DataTable extends CI_Controller {
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

public function getTable(){

    // DB table to use
    $table = $this->input->post('table');

    // Table's primary key
    $primaryKey = $_POST['primary_key'];

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes

    $columns = $_POST['columns'];

    //$columns['id'] = $columns[`artwork`.`artwork_id`];


    // SQL server connection information
    $CI = &get_instance();
    $CI->load->database();

    $sql_details = array(
            'user' => $CI->db->username,
            'pass' => $CI->db->password,
            'db'   => $CI->db->database,
            'host' => $CI->db->hostname
        );


    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP
     * server-side, there is no need to edit below this line.
     */

    //require( 'ssp.class.php' );

    //$joinQuery = "FROM {$table} AS u LEFT JOIN country AS m on u.country_id = m.country_id LEFT JOIN region AS r ON u.region_id = r.region_id AND u.country_id = r.country_id";

    $boutique_id = $this->session->userdata('active_boutique');

    $joinQuery = ""; $extraWhere = ""; $extraWhere2 = ""; $group_by = ""; $having = "";

    if($_POST['page'] == "access"){
        $extraWhere = "(boutique_id = '$boutique_id' AND type = 'access' AND cancelled=0 AND hidden=0)";

        if($this->input->post('action') == 'filter_by_customer' && $this->input->post('customer_id')){
          $phone = $this->db->select('phone')->where('id',$this->input->post('customer_id'))->get('customers')->row()->phone;
          $extraWhere .= " AND (number ='$phone')";
        }

        $group_by = "";

    }

	if($_POST['page'] == "receipt" || $_POST['page'] == "bestilling"){
        $extraWhere = "(boutique_id = '$boutique_id')";

        if($this->input->post('action') == 'filter_by_customer' && $this->input->post('customer_id')){
          $phone = $this->db->select('phone')->where('id',$this->input->post('customer_id'))->get('customers')->row()->phone;
          $extraWhere .= " AND (phone ='$phone')";
        }
        $group_by = "";

    }

    if($_POST['page'] == "transfer"){
      $status = $_POST['status'];
      $where_add = "";
      if($status == 1){
        $where_add = "AND transfered = '1'";
      }elseif($status == 2){
        $where_add = "AND transfered = '0' AND 'wrong' = '0'";
      }elseif($status == 3){
        $this->db->where('wrong',1);
        $where_add = "AND 'wrong' = '1'";
      }

        $extraWhere = "(type = 'bought' AND exchange = '0' $where_add)";


        $group_by = "";

    }

    $this->load->library('ssp');

    echo json_encode($this->ssp->simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $group_by , $having, $_POST['page']));

    }

}

?>
