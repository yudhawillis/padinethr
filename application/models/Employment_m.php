<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employment_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function get_employment($email){
//        $sql = 'SELECT * FROM employment WHERE email = "'.$email.'" AND password= "'.$password.'"';
        $sql = 'SELECT e.*, r.*, e.status as employment_status
            FROM employment e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            WHERE e.email = "'.$email.'"
        ';
        $query = $this->db->query($sql);
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function select_jum_employment(){
        $query = $this->db->get('employment');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_employment($data){
        $this->db->insert('employment', $data);
    }

    function select_detil_employment($id){
        $sql = 'SELECT em.*, r.*, em.status as employment_status
            FROM employment e
            LEFT JOIN role r
            ON e.id_role = r.id_role
            WHERE e.id_employment = "'.$id.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_detil_employment_active($id_employee){
        $sql = 'SELECT em.*, c.*
            FROM employment em
            LEFT JOIN city c ON em.id_city = c.id_city
            WHERE em.id_employee = "'.$id_employee.'"
            AND em.status = 1
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function update_employment($id, $data) {
        $this->db->where('id_employment', $id);
        return $this->db->update('employment', $data);
    }

//    function update_employment_active($id_employee, $tgl_berakhir, $status) {
//        $sql = "UPDATE employment SET status = '.$status.', tgl_berakhir = '".$tgl_berakhir."'
//            WHERE id_employee = '.$id_employee.'
//        ";
//        $this->db->query($sql);
//    }
	function update_employment_active($id_employee, $data) {
		//$this->db->where('id_employee', $id_employee);
		$this->db->where(array('id_employee' => $id_employee, 'status' => 1));
		return $this->db->update('employment', $data);
	}

    function jum_employment_staff($id_employee){
        $query = $this->db->get_where('employment', array('id_employee' => $id_employee));//namatabel
        $jum = $query->num_rows();
        return $jum;
    }

    function select_all_employee($id_employee){
        $sql = 'SELECT e.*, r.*, l.*, c.*, e.status as employment_status, em.*
            FROM employment e      
            LEFT JOIN level_staff l
            ON e.id_level = l.id_level
            LEFT JOIN city c
            ON e.id_city = c.id_city
            LEFT JOIN employee em
            ON e.id_employee = em.id_employee
            LEFT JOIN role r
            ON em.id_role = r.id_role
            WHERE em.id_employee = '.$id_employee.'
            ORDER BY employment_status DESC
            ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }






    function select_all(){
//        $sql = 'SELECT e.*, r.*, l.*, c.`*`, e.status as employment_status
//            FROM employment e
//            LEFT JOIN role r
//            ON e.id_role = r.id_role
//            LEFT JOIN employment em
//            ON e.id_employment = em.id_employment
//            LEFT JOIN level_staff l
//            ON em.id_level = l.id_level
//            LEFT JOIN city c
//            ON em.id_city = c.id_city
//        ';
        $sql = 'SELECT e.*, r.*, l.*, c.*, e.status as employment_status, em.*
            FROM employment e      
            LEFT JOIN level_staff l
            ON e.id_level = l.id_level
            LEFT JOIN city c
            ON e.id_city = c.id_city
            LEFT JOIN employee em
            ON e.id_employee = em.id_employee
            LEFT JOIN role r
            ON em.id_role = r.id_role
            ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
        return $result_array;
    }

    function select_active_employment($id_employee){
        $sql = 'SELECT em.*, l.*, c.*, e.*, em.status as employment_status, e2.fullname as fullname_sup
            FROM employment em
            LEFT JOIN employee e
            ON em.id_employee = e.id_employee
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            LEFT JOIN employee e2
            ON em.supervisor = e2.id_employee
            WHERE em.status = 1
            AND e.id_employee = "'.$id_employee.'"
        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();

        return $result_array;
    }

    function select_all_active(){
        $sql = 'SELECT em.*, l.*, c.*, e.*, em.status as employment_status
            FROM employment em
            LEFT JOIN employee e
            ON em.id_employment = e.id_employment
            LEFT JOIN level_staff l
            ON em.id_level = l.id_level
            LEFT JOIN city c
            ON em.id_city = c.id_city
            WHERE em.status = 1
        ';
        //select A.*, (select B.field1 from tableB B where B.fk = A.pk and B.status = 1 order by B.opo limit 1) from tableA A

//        $sql = 'SELECT e.*,
//            (SELECT em.* FROM employment em WHERE em.id_employment = e.id_employment AND em.status=1)
//            FROM employment e
//
//        ';
        $query = $this->db->query($sql);
        $result_array = $query->result_array();
    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = "SELECT e.*, l.*, c.`*`, e.status as employment_status, 
                (SELECT em.position FROM employment em
                WHERE em.id_employment = e.id_employment 
                AND em.status=1) as position
            FROM employment e
            LEFT JOIN employment em
            ON e.id_employment = em.id_employment
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
//        $query = $this->db->get('employment', $limit, $offset);//namatabel
        $sql = "SELECT e.*, l.*, c.*, e.status as employment_status, 
                (SELECT em.position FROM employment em
                WHERE em.id_employment = e.id_employment 
                AND em.status=1) as position
            FROM employment e
            LEFT JOIN employment em
            ON e.id_employment = em.id_employment
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
        $sql = "SELECT e.*, l.*, c.*, e.status as employment_status, 
                (SELECT em.position FROM employment em
                WHERE em.id_employment = e.id_employment 
                AND em.status=1) as position
            FROM employment e
            LEFT JOIN employment em
            ON e.id_employment = em.id_employment
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
    function jum_employment(){
        $query = $this->db->get('employment');//namatabel
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_employment_search($where){
        $sql = " SELECT e.*, l.*, c.`*`, e.status as employment_status, 
                (SELECT em.position FROM employment em
                WHERE em.id_employment = e.id_employment 
                AND em.status=1) as position
            FROM employment e
            LEFT JOIN employment em
            ON e.id_employment = em.id_employment
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

    function delete_employment($id) {
        $this->db->where('id_employment', $id);
        return $this->db->delete('employment');
    }

    function insert_employment_history($data){
        $this->db->insert('employment_history', $data);
    }
}
