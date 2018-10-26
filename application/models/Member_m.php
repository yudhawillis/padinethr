<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_employee($email){
//        $sql = 'SELECT * FROM employee WHERE email = "'.$email.'" AND password= "'.$password.'"';
        $sql = 'SELECT e.*, r.*, e.status as employee_status
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            WHERE e.email = "'.$email.'"
        ';
        $query = $this->db->query($sql);
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function select_jum_employee(){
        $query = $this->db->get('employee');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_employee($data){
        $this->db->insert('employee', $data);
    }

    function select_detil_employee($id){
        $sql = 'SELECT e.*, r.*, l.*, c.`*`, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            WHERE e.id_employee = "'.$id.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function update_employee($id, $data) {
        $this->db->where('id_employee', $id);
        return $this->db->update('employee', $data);
    }

    function select_all(){
        $sql = 'SELECT e.*, r.*, l.*, c.`*`, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position,
                (SELECT em.leave_quota FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as leave_quota
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }

    function select_all_active(){
        $sql = 'SELECT * FROM employee WHERE status = 1
        ';
        //select A.*, (select B.field1 from tableB B where B.fk = A.pk and B.status = 1 order by B.opo limit 1) from tableA A

//        $sql = 'SELECT e.*,
//            (SELECT em.* FROM employment em WHERE em.id_employee = e.id_employee AND em.status=1)
//            FROM employee e
//
//        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = "SELECT e.*, r.*, l.*, c.*, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position,
                (SELECT em.leave_quota FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as leave_quota
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            ".$where."
            ".$orderby." ".$ordertype."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function select_allpaging($limit,$offset){
//        $query = $this->db->get('employee', $limit, $offset);//namatabel
        $sql = "SELECT e.*, r.*, l.*, c.*, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }
    function select_allpaging_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = "SELECT e.*, r.*, l.*, c.*, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            ".$where."
            ".$orderby." ".$ordertype."
            LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function jum_employee(){
        $query = $this->db->get('employee');//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_employee_search($where){
        $sql = " SELECT e.*, r.*, l.*, c.`*`, e.status as employee_status, 
                (SELECT em.position FROM employment_active em
                WHERE em.id_employee = e.id_employee 
                AND em.status=1) as position
            FROM employee e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            LEFT JOIN employment_active em
            ON e.id_employee = em.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function delete_employee($id) {
        $this->db->where('id_employee', $id);
        return $this->db->delete('employee');
    }

    function insert_employee_history($data){
        $this->db->insert('employee_history', $data);
    }

}