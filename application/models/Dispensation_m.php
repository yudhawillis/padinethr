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

	function select_dispensation_leave($id_leave){
		$query = $this->db->get_where('dispensation', array('id_leave' => $id_leave));//namatabel
		$result_array = $query->result_array();

		return $result_array;
	}

	function select_dispensation_employee_year($id_employee, $year_select){
		$sql = "SELECT e.*, l.*, d.*
                FROM employee e
                LEFT JOIN leave_staff l
                ON e.id_employee = l.id_employee
                LEFT JOIN dispensation d
                ON l.id_leave = d.id_leave
                WHERE d.year = '".$year_select."'
                AND e.id_employee = ".$id_employee."
        ";
        $query = $this->db->query($sql);
		$result_array = $query->result_array();

		return $result_array;
	}

	function jum_dispensation_employee_year($id_employee, $year_select){
        $sql = "SELECT e.*, l.*, d.*
                FROM employee e
                LEFT JOIN leave_staff l
                ON e.id_employee = l.id_employee
                LEFT JOIN dispensation d
                ON l.id_leave = d.id_leave
                WHERE d.year = '".$year_select."'
                AND e.id_employee = ".$id_employee."
        ";
        $query = $this->db->query($sql);
		$jum = $query->num_rows();
		return $jum;
	}


}
