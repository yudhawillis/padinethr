<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_leave($email){
//        $sql = 'SELECT * FROM leave_staff WHERE email = "'.$email.'" AND password= "'.$password.'"';
        $sql = 'SELECT e.*, r.*, l.*, e.status as leave_staff_status
            FROM leave_staff e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN leave_staff l
            ON e.id_leave = l.id_leave
            WHERE e.email = "'.$email.'"
        ';
        $query = $this->db->query($sql);
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function select_jum_leave(){
        $query = $this->db->get('leave_staff');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_leave($data){
        $this->db->insert('leave_staff', $data);
    }

    function select_detil_leave($id){
        $sql = " SELECT lv.*, e.* 
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                WHERE lv.id_leave = ".$id."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function update_leave($id, $data) {
        $this->db->where('id_leave', $id);
        return $this->db->update('leave_staff', $data);
    }

    function select_all(){
        $query = $this->db->get_where('leave_staff', array('level !=' => 'super'));//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }
//    function select_all_active(){
//        $query = $this->db->get_where('leave_staff', array('status_aktif' => 1, 'level !=' => 'super'));//namatabel
//        $result_array = $query->result_array();
//
//        return $result_array;
//    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = " SELECT * FROM leave_staff
             ".$where."
              ".$orderby." ".$ordertype."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function select_allpaging($limit,$offset){
        $sql = " SELECT lv.*, e.*, lv.description as leave_description
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                ORDER BY lv.submission_date DESC
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function select_allpaging_superior($limit,$offset,$id_current_user){
        $sql = " SELECT lv.*, e.*, lv.description as leave_description, em.* 
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                LEFT JOIN employment em ON e.id_employee = em.id_employee
                WHERE em.supervisor = ".$id_current_user."
                ORDER BY lv.submission_date DESC
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function select_allpaging_search($where, $orderby, $ordertype, $limit, $offset){
//        $sql = "  SELECT lv.*, e.*
//                FROM leave_staff lv
//                LEFT JOIN employee e ON lv.id_employee = e.id_employee
//             ".$where."
//              ".$orderby." ".$ordertype."
//              LIMIT ".$limit." OFFSET ".$offset."
//        ";
        $sql = " SELECT lv.*, e.*, em.*, lv.description as leave_description
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                LEFT JOIN employment em ON e.id_employee = em.id_employee
                 ".$where."
                  ".$orderby." ".$ordertype."
                LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }

    function select_allpaging_search_superior($where, $orderby, $ordertype, $limit, $offset, $id_current_user){
        $sql = " SELECT lv.*, e.*, em.*, lv.description as leave_description 
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                LEFT JOIN employment em ON e.id_employee = em.id_employee
                WHERE em.supervisor = ".$id_current_user."
                ".$where."
                  ".$orderby." ".$ordertype."
                LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function jum_leave(){
        $query = $this->db->get('leave_staff');//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_leave_search($where){
        $sql = " SELECT lv.*, e.*, em.*, lv.description as leave_description 
                FROM leave_staff lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                LEFT JOIN employment em ON e.id_employee = em.id_employee
                ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function delete_leave($id) {
        $this->db->where('id_leave', $id);
        return $this->db->delete('leave_staff');
    }

    function select_personal_leave($id_employee){
//        $sql = 'SELECT e.*, r.*, l.*, e.status as leave_staff_status
//            FROM employee e
//            LEFT JOIN role r
//            ON e.id_role = r.id_role
//            LEFT JOIN leave_staff l
//            ON e.id_employee = l.id_employee
//            WHERE e.id_employee = "'.$id_employee.'"
//        ';
        $sql = 'SELECT l.*, r.*, e.*, e.status as leave_staff_status
            FROM leave_staff l
            LEFT JOIN employee e
            ON l.id_employee = e.id_employee
            LEFT JOIN role r
            ON e.id_role = r.id_role
            WHERE e.id_employee = "'.$id_employee.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_personal_leave_non_cancel_thisyear($id_employee, $start_date, $end_date){
        $sql = 'SELECT l.*, r.*, e.*, e.status as leave_staff_status
            FROM leave_staff l
            LEFT JOIN employee e
            ON l.id_employee = e.id_employee
            LEFT JOIN role r
            ON e.id_role = r.id_role
            WHERE ((l.start_date BETWEEN "'.$start_date.'"
			AND "'.$end_date.'")
			OR (l.end_date BETWEEN "'.$start_date.'"
			AND "'.$end_date.'"))
            AND e.id_employee = "'.$id_employee.'"
            AND l.cancel_status = 0
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }



    function jum_leave_personal($id_employee){
        $query = $this->db->get_where('leave_staff', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_leave_personal_search($where){
        $sql = " SELECT * FROM leave_staff
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }
    function select_allpaging_personal($limit,$offset,$id_current_user){
        $sql = " SELECT * FROM leave_staff
              WHERE id_employee = ".$id_current_user."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_personal_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM leave_staff
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }


    function insert_leave_history($data){
        $this->db->insert('leave_history', $data);
    }

    function select_detil_leave_history($id_employee, $year){
        $sql = " SELECT lv.*, e.* 
                FROM leave_history lv
                LEFT JOIN employee e ON lv.id_employee = e.id_employee
                WHERE lv.id_employee = ".$id_employee."
                AND lv.year_leave_history = ".$year."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();

        return $jum;
    }

    function select_leave_history_employee($id_employee){
        $query = $this->db->get_where('leave_history', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();

        return $jum;
    }
}
