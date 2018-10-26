<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller{
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
        $this->load->model('approval_m', 'approval_m');
        $this->load->model('employment_m', 'employment_m');
//        $this->load->model('websetting_m', 'web_set');

        if($this->logged_in()){
        }
        else redirect(base_url().'dashboard');
    }

    public function index(){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        if($this->session->userdata('submission_date')) $this->session->unset_userdata('submission_date');
        if($this->session->userdata('start_date')) $this->session->unset_userdata('start_date');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('end_date');
        if($this->session->userdata('fullname')) $this->session->unset_userdata('fullname');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "leave/approval/";
        $config["total_rows"] = $this->leave_m->jum_leave();
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
//        $data['list_leave'] = $this->leave_m->select_allpaging($config["per_page"], $page);

        $data['detail_member'] = $this->member_m->select_detil_employee($id_current_user);
        $id_current_role = $data['detail_member'][0]['id_role'];

        if ($id_current_role == 4) { //membatasi supervisor
            $data['list_leave'] = $this->leave_m->select_allpaging_superior($config["per_page"], $page, $id_current_user);
        } else if ($id_current_role == 1 || $id_current_role == 2) { //membatasi hr
            $data['list_leave'] = $this->leave_m->select_allpaging($config["per_page"], $page);
        }

//        $data['list_leave'] = $this->leave_m->select_allpaging($config["per_page"], $page);
        $i = 0;
        foreach ($data['list_leave'] as $leave){

            $id_leave = $data['list_leave'][$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $data['list_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                    $data['list_leave'][$i]{'app_name_'.$j} = $app["name_role"];
					$data['list_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
            $i++;
        }
		//var_dump($data['list_leave']);
        $this->load->view('lv_approval_v', $data);
    }

    public function search($sortby, $sorttype) {
        if($this->log_super_hr_staff()){
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
            $data['submission_date'] = $this->input->post('search_submission_date');
            $data['start_date'] = $this->input->post('search_start_date');
            $data['end_date'] = $this->input->post('search_end_date');
            $data['fullname'] = $this->input->post('search_fullname');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'submission_date'  => $data['submission_date'],
                'start_date'  => $data['start_date'],
                'end_date'  => $data['end_date'],
                'fullname'  => $data['fullname'],
                'description'  => $data['description'],

            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['submission_date'] != "") $kondisi[] = "submission_date LIKE '%".$data['submission_date']."%'";
            if($data['start_date'] != "") $kondisi[] = "start_date LIKE '%".$data['start_date']."%'";
            if($data['end_date'] != "") $kondisi[] = "end_date LIKE '%".$data['end_date']."%'";
            if($data['fullname'] != "") $kondisi[] = "fullname LIKE '%".$data['fullname']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";

            $data['detail_member'] = $this->member_m->select_detil_employee($id_current_user);
            $id_current_role = $data['detail_member'][0]['id_role'];
            if ($id_current_role == 1 || $id_current_role == 4) { //membatasi supervisor
                $kondisi[] = "em.supervisor = ".$id_current_user."";
            }

//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'leave/approval');
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);

                if ($id_current_role == 4) { //membatasi supervisor
                    $data['list_leave'] = $this->leave_m->select_allpaging_search_superior($new_kondisi, $orderby, $ordertype, $per_page, $page, $id_current_user);
                } else if ($id_current_role == 1 || $id_current_role == 2) { //membatasi hr
                    $data['list_leave'] = $this->leave_m->select_allpaging_search($new_kondisi, $orderby, $ordertype, $per_page, $page);
                }
//                $data['list_leave'] = $this->leave_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

            }
        }
        else {
            $new_kondisi = $this->session->userdata('new_kondisi');
            $data['submission_date'] = $this->session->userdata('submission_date');
            $data['start_date'] = $this->session->userdata('start_date');
            $data['end_date'] = $this->session->userdata('end_date');
            $data['fullname'] = $this->session->userdata('fullname');
            $data['description'] = $this->session->userdata('description');

            $data['detail_member'] = $this->member_m->select_detil_employee($id_current_user);
            $id_current_role = $data['detail_member'][0]['id_role'];

            if ($id_current_role == 4) { //membatasi supervisor
                $data['list_leave'] = $this->leave_m->select_allpaging_search_superior($new_kondisi, $orderby, $ordertype, $per_page, $page, $id_current_user);
            } else if ($id_current_role == 1 || $id_current_role == 2) { //membatasi hr
                $data['list_leave'] = $this->leave_m->select_allpaging_search($new_kondisi, $orderby, $ordertype, $per_page, $page);
            }
            $data['list_leave'] = $this->leave_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "leave/approval/search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
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
        $config['cur_tag_open'] = '<li class=" "><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;

        $data['tipe_print'] = 'search';//untuk button print
        $data['sortby'] = $sortby;//untuk button print
        $data['sorttype'] = $sorttype;//untuk button print

        $i = 0;
        foreach ($data['list_leave'] as $leave){
            $id_leave = $data['list_leave'][$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $data['list_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                    $data['list_leave'][$i]{'app_name_'.$j} = $app["name_role"];
					$data['list_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
            $i++;
        }

        $this->load->view('lv_approval_v', $data);

    }
    public function detil_data($id_leave){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['detail_member'] = $this->member_m->select_detil_employee($id_current_user);


        $data['list_leave_personal'] = $this->leave_m->select_personal_leave($id_current_user);
        $data['detail_leave'] = $this->leave_m->select_detil_leave($id_leave);
        $id_user_leave = $data['detail_leave'][0]['id_employee'];
        $data['detail_employee'] = $this->member_m->select_detil_employee($id_user_leave);
        $data['detail_employment'] = $this->employment_m->select_detil_employment_active($id_user_leave);

        $data['startnum'] = 1;
        $id_current_role = $data['detail_member'][0]['id_role'];
        if ($id_current_role == 1 || $id_current_role == 4) {
            $current_level_appr = "spv";
        } else if ($id_current_role == 2) {
            $current_level_appr = "hr";
        }
        $data['current_level_appr'] = $current_level_appr;

        $data['approved_by'] = 0;
        $data['cek_jum_approve'] = $this->approval_m->select_jum_approve_leave($id_leave);
        // select jumlah approve dalam leave
        // jika jumlah nya ada lebih dari 0, maka dicari apakah ada yg role nya sama seperti current_user
        // jika ada maka, tombol approval hilang
        // jika tidak ada maka tombol approval muncul

        if ($data['cek_jum_approve'] == 0) {

            if ($id_current_role == 2) { // HR
                $data['button_app'] = 0;//button off
            } else if ($id_current_role == 1 || $id_current_role == 4) { //SU / Spv
                $data['button_app'] = 1; //button on
            }
        } else if ($data['cek_jum_approve'] == 1) {
            $all_approval = $this->approval_m->select_leave_approval($id_leave);
            if ($id_current_role == 2) { // HR

                foreach($all_approval as $appr) {
                    if($appr['id_role'] == 2){
                        $data['button_app'] = 0;//button off
                        $data['approved_by'] = 1; //True

                        $get_data_approve = $this->approval_m->select_leave_approved_by_role($id_current_role, $id_leave);
                        $data['ap_status'] = $get_data_approve[0]['ap_status'];
                        $data['name_role'] = $get_data_approve[0]['name_role'];
                    } else if($appr['id_role'] == 1 || $appr['id_role'] == 4){
                    	if($appr['ap_status'] == 0){// pengecekan reject dari SU atau Spv
							$get_data_approve = $this->approval_m->select_leave_rejected_by_role($id_leave);
							$data['ap_status'] = $get_data_approve[0]['ap_status'];
							$data['name_role'] = $get_data_approve[0]['name_role'];
							$data['button_app'] = 0;//button off
							$data['approved_by'] = 1; //true
						} else {
							$data['button_app'] = 1;//button on
							$data['approved_by'] = 0; //false
						}

                    }
                }
            } else if ($id_current_role == 1 || $id_current_role == 4) { //SU / Spv
                foreach($all_approval as $appr) {
                    if($appr['id_role'] == 1 || $appr['id_role'] == 4){
                        $data['button_app'] = 0;//button off
                        $data['approved_by'] = 1; //True

                        $id_role_1 = 1;
                        $id_role_2 = 4;
                        $get_data_approve = $this->approval_m->select_leave_approved_by_role($id_role_1, $id_leave);
                        if (empty($get_data_approve)){
                            $get_data_approve = $this->approval_m->select_leave_approved_by_role($id_role_2, $id_leave);
                        }
                        $data['ap_status'] = $get_data_approve[0]['ap_status'];
                        $data['name_role'] = $get_data_approve[0]['name_role'];
                    } else if($appr['id_role'] == 2){
                        $data['button_app'] = 1;//button on
                        $data['approved_by'] = 0; //false
                    }
                }
            }
        } else if ($data['cek_jum_approve'] == 2) {
            $data['button_app'] = 0;//button off

            $id_leave = $data['detail_leave'][0]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $data['detail_leave'][0]{'app_status_'.$j} = $app["ap_status"];
                    $data['detail_leave'][0]{'app_name_'.$j} = $app["name_role"];
                    $j++;
                }
            }

        }

        $this->load->view('lv_approval_detail_v', $data);
    }

    public function approve_leave($id_leave, $dispensation_quota=0){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');
//        if ($dispensation_quota=="") $dispensation_quota = 0;

        $id_current_user = $this->session->userdata('id_employee');
        $detail_member = $this->member_m->select_detil_employee($id_current_user);
        $get_role = $detail_member[0]['id_role'];
        if($get_role == 1 || $get_role == 4) {
            $data_formap['level_user_approval'] = "spv";
        } else if ($get_role == 2) {
            $data_formap['level_user_approval'] = "hr";
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Maaf, anda tidak memiliki hak akses approval.');
            redirect(base_url().'leave/approval');
        }

        $current_leave = $this->leave_m->select_detil_leave($id_leave);

        $data_formap['id_leave'] = $id_leave;
        $data_formap['id_employee'] = $current_leave[0]['id_employee'];
        $data_formap['status'] = 1;
        $data_formap['reason_reject'] = "";
        $data_formap['id_user_approval'] = $id_current_user;
        $data_formap['approval_time'] = $this->today_datetime();
        $this->approval_m->insert_approval($data_formap);

        $data_formlv['dispensation_quota'] = $dispensation_quota;
        $this->update_leave_dispensation($id_leave, $data_formlv);

        $current_quota = $this->cek_jum_quota($data_formap['id_employee']);
        $current_quota_ext = $this->cek_jum_quota_ext($data_formap['id_employee']);

        $this->count_request_leave($id_leave, $dispensation_quota, $current_quota, $current_quota_ext);

        $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan <i>approve leave</i>.');
        redirect(base_url().'leave/approval');
    }

    public function reject_leave($id_leave){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $detail_member = $this->member_m->select_detil_employee($id_current_user);
        $get_role = $detail_member[0]['id_role'];
        if($get_role == 1 || $get_role == 4) {
            $data_formap['level_user_approval'] = "spv";
        } else if ($get_role == 2) {
            $data_formap['level_user_approval'] = "hr";
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Maaf, anda tidak memiliki hak akses approval.');
            redirect(base_url().'leave/approval');
        }

        $current_leave = $this->leave_m->select_detil_leave($id_leave);

        $data_formap['id_leave'] = $id_leave;
        $data_formap['id_employee'] = $current_leave[0]['id_employee'];
        $data_formap['status'] = 0;
        $data_formap['reason_reject'] = $this->input->post('description');
        $data_formap['id_user_approval'] = $id_current_user;
        $data_formap['approval_time'] = $this->today_datetime();

        $this->approval_m->insert_approval($data_formap);
        $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan <i>reject leave</i>.');
        redirect(base_url().'leave/approval');
    }

    private function count_request_leave($id_leave, $dispensation_quota, $current_quota, $current_quota_ext){
        $cek_jum_approve = $this->approval_m->select_jum_approve_leave($id_leave);
        $jum_app_final = 0;
        $list_approval_user = array();
        if ($cek_jum_approve == 2){
            $get_data_approve = $this->approval_m->select_leave_approval($id_leave);
            foreach($get_data_approve as $appr){
                if ($appr['ap_status'] = 1){
                    $list_approval_user[] = $appr['ap_status'];
                }
            }
            $jum_app_final = count($list_approval_user);
            if ($jum_app_final == 2){
                $this->update_leave_employment($get_data_approve[0]['id_employment'], $get_data_approve[0]['leave_quota'], $get_data_approve[0]['days'], $dispensation_quota, $current_quota, $current_quota_ext);
            }
        }
    }

    private function update_leave_dispensation($id_leave, $data){
        $this->leave_m->update_leave($id_leave, $data);
    }

    private function update_leave_employment($id_user, $leavequota, $reqleave, $dispensation_quota, $current_quota, $current_quota_ext){
        $reqleave = $reqleave - $dispensation_quota;//dikurangi di awal, sehingga kalkulasi dispensasi diambil dari jumlh request keseluruhan
        $cek_req_quota = $current_quota - $reqleave; //abs agar hasilnya selalu postif
        if($cek_req_quota < 0) {
            $leave_quota_updated = 0;
            $data_formprof['leave_quota'] = $leave_quota_updated;
            $cek_req_quota_ext = $current_quota_ext - abs($reqleave);
            $payroll_deduction = 0;
            $debt_leave_quota = 0;
            if ($cek_req_quota_ext >= 0) {
                //jika hasilnya kurang dari nol maka sisanya adalah dibuat sbg bilangan positif sebagai payrol_deduction
                $payroll_deduction = abs($cek_req_quota_ext);
            } else {
                //jika hasilnya 0 atau lebih dari nol maka tetap sebagai hasil positif dan mengupdate $debt_leave_quota
                $debt_leave_quota = $cek_req_quota_ext;
            }
            $leave_quota_ext_updated = 3 - $debt_leave_quota; //karena $debt_leave_quota adalah sisa hasil dikurangi $reqleave

//            //start calculating dispensation //tidak jadi dipakai karna dispensation_quota sudah dikurangi sejak awal
//            if ($payroll_deduction != 0) {
//                $payroll_deduction = $payroll_deduction - $dispensation_quota;
//            }
//            else $leave_quota_ext_updated = $leave_quota_ext_updated - $dispensation_quota;
//            //end calculating dispensation

            $data_formprof['leave_quota_ext'] = $leave_quota_ext_updated;

        } else if($cek_req_quota >= 0){
            //$data_formprof['leave_quota'] = $leavequota - $reqleave + $dispensation_quota; //tidak jadi dipakai karna dispensation_quota sudah dikurangi sejak awal
            $data_formprof['leave_quota'] = $leavequota - $reqleave;
        }

        $this->employment_m->update_employment($id_user, $data_formprof);
    }

    private function cek_jum_quota($id_employee){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $current_quota = $current_employment[0]['leave_quota'];

        return $current_quota;
    }

    private function cek_jum_quota_ext($id_employee){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $current_quota_ext = $current_employment[0]['leave_quota_ext'];

        return $current_quota_ext;
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
        if($this->session->userdata('id_role')==1 || $this->session->userdata('id_role')==2 || $this->session->userdata('id_role')==4){
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
