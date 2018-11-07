<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Jointholiday extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('member_m','member_m');
        $this->load->model('user_m', 'user_m');
        $this->load->model('leave_m', 'leave_m');
        $this->load->model('holiday_m', 'holiday_m');
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'mem_login');
    }

    public function index(){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        if($this->session->userdata('new_kondisi')) $this->session->unset_userdata('new_kondisi');
        if($this->session->userdata('date_holiday')) $this->session->unset_userdata('date_holiday');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "leave/jointholiday/";
        $config["total_rows"] = $this->holiday_m->jum_joint_holiday();
        $config["per_page"] = 10;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        if($this->uri->segment(4)) $page = ($this->uri->segment(4));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_joint_holiday'] = $this->holiday_m->select_allpaging($config["per_page"], $page);
        $this->load->view('lv_jointholiday_v', $data);

    }

    public function search($sortby, $sorttype) {
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');

        if($this->uri->segment(6)) $page = ($this->uri->segment(6));
        else $page = 0;
        $per_page = 10;
        if ($sortby == "orby") $orderby = "";
        else $orderby = "ORDER BY ".$sortby;

        if ($sorttype == "ortype"){
            $data['typeorder'] = "0";
            $ordertype="";
        }
        else if ($sorttype == 0){
            $data['typeorder'] = 1;
            $ordertype="desc";
        }
        else if ($sorttype == 1) {
            $data['typeorder'] = 0;
            $ordertype="asc";
        }
        if($this->input->post()){

            $data['date_holiday'] = $this->input->post('search_date_holiday');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'date_holiday'  => $data['date_holiday'],
                'description'  => $data['description'],
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['date_holiday'] != "") $kondisi[] = "date_holiday LIKE '%".$data['date_holiday']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'leave/jointholiday');
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_joint_holiday'] = $this->holiday_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

            }
        }
        else {
            $new_kondisi = $this->session->userdata('new_kondisi');
            $data['date_holiday'] = $this->session->userdata('date_holiday');
            $data['description'] = $this->session->userdata('description');

            $data['list_joint_holiday'] = $this->holiday_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "leave/jointholiday/search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->holiday_m->jum_joint_holiday_search($new_kondisi);
        $config["per_page"] = $per_page;
        $config['next_page'] = '&gt;';
        $config['prev_page'] = '&lt;';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['tipe_print'] = 'search';//untuk button print
        $data['sortby'] = $sortby;//untuk button print
        $data['sorttype'] = $sorttype;//untuk button print
        var_dump($new_kondisi);
        $this->load->view('lv_jointholiday_v', $data);

    }
    public function tambah_data(){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "add";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('date_holiday', 'date_holiday', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        if ($this->form_validation->run()==FALSE){
            $this->load->view('lv_jointholiday_form_v', $data);
        }
        else {
            $data_formjo['date_holiday'] = $this->input->post('date_holiday');
            $data_formjo['description'] = $this->input->post('description');

            $this->holiday_m->insert_joint_holiday($data_formjo);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil menambahkan data.');
            redirect(base_url().'leave/jointholiday');
        }
    }

    public function edit_data($id){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "edit";
        $data['list_holiday'] = $this->holiday_m->select_detil_joint_holiday($id);
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('date_holiday', 'date_holiday', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        if ($this->form_validation->run()==FALSE){
            $this->load->view('lv_jointholiday_form_v', $data);
        }
        else {
            $data_formjo['date_holiday'] = $this->input->post('date_holiday');
            $data_formjo['description'] = $this->input->post('description');

            $this->holiday_m->update_joint_holiday($id, $data_formjo);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data.');
            redirect(base_url().'leave/jointholiday');
        }
    }

    public function hapus_data($id){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $this->holiday_m->delete_joint_holiday($id);
        $this->session->set_flashdata('pesan', 'Anda telah berhasil menghapus data.');
        redirect(base_url().'leave/jointholiday');
    }

    private function today_datetime(){
        $datestring = '%Y-%m-%d %h:%i:%s';
        $time = time();
        $tanggal = mdate($datestring, $time);

        return $tanggal;
    }

    private function logged_in(){
        if($this->session->userdata('logged_in')){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }

    private function log_super_hr(){
        if($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }

    private function log_super_hr_staff(){
        if($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2 || $this->session->userdata('id_role')==3 || $this->session->userdata('id_role')==4){
            $usr = $this->session->userdata('nickname');
            return isset($usr);
        }
    }
}