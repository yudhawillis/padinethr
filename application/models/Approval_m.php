<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function insert_approval($data){
        $this->db->insert('approval', $data);
    }

    function select_leave_approval($id_leave){
        $sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status, lv.*, ea.*
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            LEFT JOIN leave_staff lv ON ap.id_leave = lv.id_leave
            LEFT JOIN employment_active ea ON e.id_employee = ea.id_employee
            WHERE ap.id_leave = "'.$id_leave.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_jumleave_approved_by($id_user_approval, $id_leave){
        $sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            WHERE ap.id_user_approval = "'.$id_user_approval.'"
            AND ap.id_leave = "'.$id_leave.'"
        ';
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function select_jum_approve_leave($id_leave){
        $sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            WHERE ap.id_leave = "'.$id_leave.'"
        ';
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function select_leave_approved_by($id_user_approval, $id_leave){
        $sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            WHERE ap.id_user_approval = "'.$id_user_approval.'"
            AND ap.id_leave = "'.$id_leave.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_leave_approved_by_role($id_role, $id_leave){
        $sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            WHERE r.id_role = "'.$id_role.'"
            AND ap.id_leave = "'.$id_leave.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

	function select_leave_rejected_by_role($id_leave){
		$sql = 'SELECT ap.*, e.*, e2.*, e2.fullname as ap_fullname, r.*, ap.status as ap_status
            FROM approval ap 
            LEFT JOIN employee e ON ap.id_employee = e.id_employee
            LEFT JOIN employee e2 ON ap.id_user_approval = e2.id_employee
            LEFT JOIN role r ON e2.id_role = r.id_role
            WHERE ap.id_leave = "'.$id_leave.'"
        ';
		$query = $this->db->query($sql);
		$result_array = $query->result_array();

		return $result_array;
	}

}
