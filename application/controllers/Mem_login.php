<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');
class Mem_login extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->helper('security');
        $this->load->library('session');
        $this->load->model('member_m','member_m');
        $this->load->model('user_m','user_m');
    }
    public function index(){
        $this->load->view('login_v');
//        $data['password'] = "12345";
//        $pengacak = "1S4I0V2I1A9A9Z7IZAH";
//        $data['passwordnew'] = password_hash($data['password'].$pengacak, PASSWORD_BCRYPT);
//        echo $data['passwordnew'];
    }
    public function submit_login(){
        $data['email'] = $this->db->escape_str( $this->input->post('email') );
        $data['password'] = $this->db->escape_str( $this->input->post('password') );

        if ($data['email'] != "" && $data['password'] != ""){
            $this->authentification($data['email'], $data['password']);
        }
        else {
            $this->session->set_flashdata('select', 'Pastikan anda mengisikan input.');
            redirect(base_url().'mem_login');
        }
    }
    private function authentification($email, $password){
        $data['email'] = $email;
        $data['password'] = $password;
        $pengacak = "1S4I0V2I1A9A9Z7IZAH";
//        $data['passwordnew'] = password_hash($data['password'], PASSWORD_BCRYPT);
        //echo $data['passwordnew'];
        $hasil = $this->member_m->get_employee($data['email']);
        echo $hasil['jumlah'];
        if ($hasil['jumlah'] > 0){
            $dataresult = $hasil['result'][0];
            if(password_verify($data['password'].$pengacak, $dataresult['password'])){
                $data['logged_in'] = TRUE;

                $data['id_employee'] = $dataresult['id_employee'];
                $data['fullname'] = $dataresult['fullname'];
                $data['nickname'] = $dataresult['nickname'];
                $data['id_role'] = $dataresult['id_role'];
                $data['employee_status'] = $dataresult['employee_status'];

                $data_hist['id_employee'] = $data['id_employee'];
                $data_hist['time'] = $this->today_datetime();
                $this->user_m->insert_user_history($data_hist);

                $this->session->set_userdata($data);
                $this->session->set_flashdata('pesan', 'Selamat datang !');
//                var_dump($data);
                redirect(base_url().'dashboard');
            }
            else {
                $this->session->set_flashdata('stop', 'Gagal !.');
                redirect(base_url().'mem_login');
            }
        }
        else {
            $this->session->set_flashdata('select', 'Mohon periksa kembali email dan password anda.');
            redirect(base_url().'mem_login');
        }
    }
    public function logged_in(){
        if($this->session->userdata('logged_in')){
            $usr = $this->session->userdata('nickname');
            //echo $usr
            return isset($usr);
        }
    }
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url().'mem_login');
    }

    private function today_datetime(){
        $datestring = '%Y-%m-%d %h:%i:%s';
        $time = time();
        $tanggal = mdate($datestring, $time);

        return $tanggal;
    }

}
?>