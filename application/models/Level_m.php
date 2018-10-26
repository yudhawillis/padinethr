<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Level_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_level($email){
//        $sql = 'SELECT * FROM level_staff WHERE email = "'.$email.'" AND password= "'.$password.'"';
        $sql = 'SELECT e.*, r.*, l.*, e.status as level_staff_status
            FROM level_staff e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN level_staff l
            ON e.id_level = l.id_level
            WHERE e.email = "'.$email.'"
        ';
        $query = $this->db->query($sql);
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function select_jum_level(){
        $query = $this->db->get('level_staff');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_level($data){
        $this->db->insert('level_staff', $data);
    }

    function select_detil_level($id){
        $sql = 'SELECT * FROM level_staff
            WHERE id_level = "'.$id.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function update_level($id, $data) {
        $this->db->where('id_level', $id);
        return $this->db->update('level_staff', $data);
    }

    function select_all(){
        $query = $this->db->get('level_staff');//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }
//    function select_all_active(){
//        $query = $this->db->get_where('level_staff', array('status_aktif' => 1, 'level !=' => 'super'));//namatabel
//        $result_array = $query->result_array();
//
//        return $result_array;
//    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = " SELECT * FROM level_staff
             ".$where."
              ".$orderby." ".$ordertype."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function select_allpaging($limit,$offset){
        $sql = " SELECT * FROM level_staff
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM level_staff
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function jum_level(){
        $query = $this->db->get('level_staff');//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_level_search($where){
        $sql = " SELECT * FROM level_staff
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function delete_level($id) {
        $this->db->where('id_level', $id);
        return $this->db->delete('level_staff');
    }

    function select_personal_level($id_employee){
        $sql = 'SELECT e.*, r.*, l.*, e.status as level_staff_status
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN level_staff l
            ON e.id_level = l.id_level
            WHERE e.id_employee = "'.$id_employee.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function jum_level_personal($id_employee){
        $query = $this->db->get_where('level_staff', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_level_personal_search($where){
        $sql = " SELECT * FROM level_staff
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }
    function select_allpaging_personal($limit,$offset){
        $sql = " SELECT * FROM level_staff
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_personal_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM level_staff
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }


}