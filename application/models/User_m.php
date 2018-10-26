<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_m  extends CI_Model{
    function __construct(){
        parent::
        __construct();
    }
    function insert_user_history($data){
        $this->db->insert('user_history', $data);
    }
}