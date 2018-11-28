<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavedebt_m extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function insert_leave_debt($data){
		$this->db->insert('leave_debt', $data);
	}

	function select_leave_debt_personal_year($id_employee, $year){
		$sql = " SELECT * FROM leave_debt
              WHERE id_employee = ".$id_employee."
              AND year = ".$year."
        ";
		$query = $this->db->query($sql);
		$result = $query->result_array();

		return $result;
	}



}
