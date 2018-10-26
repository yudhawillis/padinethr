<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class City_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_city($email){
//        $sql = 'SELECT * FROM city_staff WHERE email = "'.$email.'" AND password= "'.$password.'"';
        $sql = 'SELECT e.*, r.*, l.*, e.status as city_staff_status
            FROM city_staff e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN city_staff l
            ON e.id_city = l.id_city
            WHERE e.email = "'.$email.'"
        ';
        $query = $this->db->query($sql);
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function select_jum_city(){
        $query = $this->db->get('city_staff');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_city($data){
        $this->db->insert('city_staff', $data);
    }

    function select_detil_city($id){
        $sql = 'SELECT e.*, r.*, l.*, e.status as city_staff_status
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN city_staff l
            ON e.id_city = l.id_city
            WHERE l.id_city = "'.$id.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function update_city($id, $data) {
        $this->db->where('id_city', $id);
        return $this->db->update('city_staff', $data);
    }

    function select_all(){
        $query = $this->db->get('city');//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }
//    function select_all_active(){
//        $query = $this->db->get_where('city_staff', array('status_aktif' => 1, 'city !=' => 'super'));//namatabel
//        $result_array = $query->result_array();
//
//        return $result_array;
//    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = " SELECT * FROM city_staff
             ".$where."
              ".$orderby." ".$ordertype."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function select_allpaging($limit,$offset){
        $sql = " SELECT * FROM city_staff
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM city_staff
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function jum_city(){
        $query = $this->db->get('city_staff');//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_city_search($where){
        $sql = " SELECT * FROM city_staff
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function delete_city($id) {
        $this->db->where('id_city', $id);
        return $this->db->delete('city_staff');
    }

    function select_personal_city($id_employee){
        $sql = 'SELECT e.*, r.*, l.*, e.status as city_staff_status
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN city_staff l
            ON e.id_city = l.id_city
            WHERE e.id_employee = "'.$id_employee.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function jum_city_personal($id_employee){
        $query = $this->db->get_where('city_staff', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_city_personal_search($where){
        $sql = " SELECT * FROM city_staff
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }
    function select_allpaging_personal($limit,$offset){
        $sql = " SELECT * FROM city_staff
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_personal_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM city_staff
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }


}