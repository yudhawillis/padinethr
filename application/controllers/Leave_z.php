<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends CI_Controller{
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
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'mem_login');
    }

    public function index(){
        redirect(base_url().'leave/myschedule');
    }

    public function myschedule(){
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);


        $this->load->library('form_validation');
        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('start_date', 'Date Start', 'required');//human name urutan kedua
        $this->form_validation->set_rules('end_date', 'Date End', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        if ($this->form_validation->run()==FALSE){
            $data['status_form']='add';
            $this->load->view('lv_myschedule_v', $data);
        }
        else {
            $weekendtype = "satsun";
            $data_formlv['start_date'] = $this->input->post('start_date');
            $data_formlv['end_date'] = $this->input->post('end_date');
            $data_formlv['description'] = $this->input->post('description');
            $data_formlv['submission_date'] = $this->today_datetime();
            $data_formlv['id_employee'] = $data['current_user'][0]['id_employee'];

            $data_formlv['days'] = $this->count_days($data_formlv['start_date'], $data_formlv['end_date'], $weekendtype);

            //$this->update_leave_employment(); belum selesei, ngambil data utk function
            $this->leave_m->insert_leave($data_formlv);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan input request cuti.');
            redirect(base_url().'leave/myschedule');
        }
    }

    public function myrequest() {
        if($this->session->userdata('submission_date')) $this->session->unset_userdata('nama_speedtes');
        if($this->session->userdata('start_date')) $this->session->unset_userdata('status_aktif');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('status_aktif');
        if($this->session->userdata('days')) $this->session->unset_userdata('status_aktif');
        if($this->session->userdata('reason')) $this->session->unset_userdata('status_aktif');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "leave/myrequest/";
        $config["total_rows"] = $this->leave_m->jum_leave_id();
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
        if($this->uri->segment(3)) $page = ($this->uri->segment(3));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['list_leave'] = $this->leave_m->select_allpaging($config["per_page"], $page);
        $this->load->view('leave/lv_myschedule_v', $data);


//        $data['list_kategori'] = $this->speedtes_m->select_all();
//        $this->load->view('mst/driver_v', $data);

    }
    public function myrequest_search($sortby, $sorttype) {
        if($this->uri->segment(4)) $page = ($this->uri->segment(4));
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
            $data['submission_date'] = $this->input->post('search_submission_date');
            $data['start_date'] = $this->input->post('search_start_date');
            $data['end_date'] = $this->input->post('search_end_date');
            $data['days'] = $this->input->post('search_days');
            $data['reason'] = $this->input->post('search_reason');

            $datasrc = array(
                'e'  => $data['nama_speedtes'],
                'status_aktif'  => $data['status_aktif'],

            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['submission_date'] != "") $kondisi[] = "submission_date LIKE '%".$data['submission_date']."%'";
            if($data['start_date'] != "") $kondisi[] = "start_date LIKE '%".$data['start_date']."%'";
            if($data['end_date'] != "") $kondisi[] = "end_date LIKE '%".$data['end_date']."%'";
            if($data['days'] != "") $kondisi[] = "days LIKE '%".$data['days']."%'";
            if($data['reason'] != "") $kondisi[] = "reason LIKE '%".$data['reason']."%'";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'leave/myrequest');
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_leave'] = $this->leave_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

            }
        }
        else {
            $new_kondisi = $this->session->userdata('new_kondisi');
            $data['submission_date'] = $this->session->userdata('submission_date');
            $data['start_date'] = $this->session->userdata('start_date');
            $data['end_date'] = $this->session->userdata('end_date');
            $data['days'] = $this->session->userdata('days');
            $data['reason'] = $this->session->userdata('reason');

            $data['list_speedtes'] = $this->speedtes_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "leave/myrequest_search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->leave_m->jum_leave_search($new_kondisi);
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
        $this->load->view('admin/speedtes_v', $data);
    }

    private function count_days($start_date, $end_date, $weekendtype) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');
        $interval = $end->diff($start);
        // total days
        $days = $interval->days;
        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = array('2018-07-18'); // harilibur nasional

        foreach($period as $dt) {
            $curr = $dt->format('D');

            // substract if Saturday or Sunday
            if ($weekendtype == "satsun") {
                if ($curr == 'Sat' || $curr == 'Sun') {
                    $days--;
                }
                // (optional) for the updated question
                elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            } else if ($weekendtype == "sun") {
                if ($curr == 'Sun') {
                    $days--;
                }
                elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                    $days--;
                }
            }
        }
        return $days;
    }
    private function update_leave_employment($id_user, $leavequota, $reqleave){
        $data_formprof['leave_quota'] = $leavequota - $reqleave;
        $this->member_m->update_employee($id_user, $data_formprof); // masih salah, update employment bukan employee
    }

    private function today_datetime(){
        $datestring = '%Y-%m-%d %h:%i:%s';
        $time = time();
        $tanggal = mdate($datestring, $time);

        return $tanggal;
    }

    private function logged_in(){
        if($this->session->userdata('logged_in')){
            $usr = $this->session->userdata('firstname');
            return isset($usr);
        }
//        if($this->session->userdata('level')=="root" || $this->session->userdata('level')=="admin" || $this->session->userdata('level')=="super"){
//            if($this->session->userdata('status_aktif_admin')=="1"){
//                $usr = $this->session->userdata('username');
//                return isset($usr);
//            }
//        }
    }
}