<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_m extends CI_Model{
    function __construct(){
        parent::
        __construct();
    }
    function select_all(){
        $query = $this->db->get('role');//namatabel
        $result_array = $query->result_array();

        return $result_array;
    }
}