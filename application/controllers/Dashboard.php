<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('member_m','member_m');
        $this->load->model('user_m', 'user_m');
        $this->load->model('role_m', 'role_m');
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'mem_login');
    }
    public function index(){
        redirect(base_url().'dashboard/summary');
    }

    public function summary(){
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
//        var_dump($data);
        $this->load->view('dash_summary_v', $data);
    }

    public function profile(){
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "edit";
        $this->load->library('form_validation');
        $data['list_role'] = $this->role_m->select_all();

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('firstname', 'firstname', 'required');
        $this->form_validation->set_rules('nickname', 'nickname', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('nik_employee', 'nik_employee', 'required');
        $this->form_validation->set_rules('id_role', 'role', 'required');
        if ($this->form_validation->run()==FALSE){
//            var_dump($data);
            $this->load->view('dash_profile_v', $data);
        }
        else {
            $data_formprof['fullname'] = $this->input->post('fullname');
            $data_formprof['nickname'] = $this->input->post('nickname');
            $data_formprof['email'] = $this->input->post('email');
            $data_formprof['birthdate'] = $this->input->post('birthdate');
            $data_formprof['address'] = $this->input->post('address');
            $data_formprof['nik_employee'] = $this->input->post('nik_employee');
            $data_formprof['npwp'] = $this->input->post('npwp');
            $data_formprof['identity_number'] = $this->input->post('identity_number');
            $data_formprof['phone_number'] = $this->input->post('phone_number');
            $data_formprof['id_role'] = $this->input->post('id_role');

            $this->up_imgprofile();
            $upload_data=$this->upload->data();
            $data_formprof['photo'] = $upload_data['file_name'];

            $this->member_m->update_employee($id_current_user, $data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data.');
            redirect(base_url().'dashboard/profile');
//            $this->load->view('dash_profile_v', $data);
        }
    }
    public function up_imgprofile()
    {
        $config['upload_path'] = './assets/uploads/profile/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['maintain_ratio'] = TRUE;
        $config['create_thumb'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $field_name = "profilepic"; //nameinput form

        if (!$this->upload->do_upload($field_name)) {
            $error = array('error' => $this->upload->display_errors());

        }
    }
    public function logged_in(){
        if($this->session->userdata('logged_in')){
            $usr = $this->session->userdata('nickname');
            //echo $usr
            return isset($usr);
        }
//        if($this->session->userdata('id_role')=="root" || $this->session->userdata('level')=="admin" || $this->session->userdata('level')=="super"){
//            if($this->session->userdata('status_aktif_admin')=="1"){
//                $usr = $this->session->userdata('username');
//                return isset($usr);
//            }
//        }
    }
}