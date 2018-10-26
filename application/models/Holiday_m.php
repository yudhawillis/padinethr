<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_m extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function select_jum_joint_holiday(){
        $query = $this->db->get('joint_holiday');
        $hasil['result'] = $query->result_array();
        $hasil['jumlah'] = $query->num_rows();
        return $hasil;
    }

    function insert_joint_holiday($data){
        $this->db->insert('joint_holiday', $data);
    }

    function select_detil_joint_holiday($id){
        $query = $this->db->get_where('joint_holiday', array('id_joint_holiday' => $id));//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }

    function update_joint_holiday($id, $data) {
        $this->db->where('id_joint_holiday', $id);
        return $this->db->update('joint_holiday', $data);
    }

    function select_all(){
        $sql = " SELECT * FROM joint_holiday
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function select_all_search($where, $orderby, $ordertype){
        $sql = " SELECT * FROM joint_holiday
             ".$where."
              ".$orderby." ".$ordertype."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function select_allpaging($limit,$offset){
        $sql = " SELECT * FROM joint_holiday
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    function select_allpaging_search($where, $orderby, $ordertype, $limit, $offset){
        $sql = " SELECT * FROM joint_holiday
             ".$where."
              ".$orderby." ".$ordertype."
              LIMIT ".$limit." OFFSET ".$offset."
        ";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;

    }
    function jum_joint_holiday(){
        $sql = " SELECT * FROM joint_holiday";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }
    function jum_joint_holiday_search($where){
        $sql = " SELECT * FROM joint_holiday
              ".$where."
        ";
        $query = $this->db->query($sql);
        $jum = $query->num_rows();
        return $jum;
    }

    function delete_joint_holiday($id) {
        $this->db->where('id_joint_holiday', $id);
        return $this->db->delete('joint_holiday');
    }

    function insert_joint_holiday_history($data){
        $this->db->insert('joint_holiday_history', $data);
    }
}