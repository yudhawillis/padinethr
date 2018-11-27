<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adjustment_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function insert_adjustment($data){
        $this->db->insert('leave_adjustment', $data);
    }

    function select_all(){
        $query = $this->db->get('leave_adjustment');//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_adjustment_employee($id_employee){
        $query = $this->db->get_where('leave_adjustment', array('id_employee' => $id_employee));//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_allpaging_adjustment($limit,$offset,$id_current_user){
        $sql = " SELECT * FROM leave_adjustment
              WHERE id_employee = ".$id_current_user."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_adjustment_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM leave_adjustment
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }

    function select_adjustment_employee_thisyear($id_employee, $start_date, $start_end){
    	$sql = 'SELECT * FROM leave_adjustment
				WHERE (datetime BETWEEN "'.$start_date.'"
				AND "'.$start_end.'") 
				AND id_employee = "'.$id_employee.'"';
		$query = $this->db->query($sql);
		$result = $query->result_array();

		return $result;
	}

    function jum_adjustment_personal($id_employee){
        $query = $this->db->get_where('leave_adjustment', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_adjustment_personal_search($where){
        $sql = " SELECT * FROM leave_adjustment
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

}
