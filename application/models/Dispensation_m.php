<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dispensation_m extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function insert_dispensation($data){
		$this->db->insert('dispensation', $data);
	}

	function select_all(){
		$query = $this->db->get('dispensation');//namatabel
		$result_array = $query->result_array();

		return $result_array;
	}

	function select_dispensation_employee($id_employee){
		$query = $this->db->get_where('dispensation', array('id_employee' => $id_employee));//namatabel
		$result_array = $query->result_array();

		return $result_array;
	}

	function select_dispensation_employee_year($id_employee, $year_select){
		$query = $this->db->get_where('dispensation', array('id_employee' => $id_employee, 'year' => $year_select));//namatabel
		$result_array = $query->result_array();

		return $result_array;
	}

	function jum_dispensation_personal($id_employee){
		$query = $this->db->get_where('dispensation', array('id_employee' => $id_employee));//namatabel
		$jum = $query->num_rows();
		return $jum;
	}


}
