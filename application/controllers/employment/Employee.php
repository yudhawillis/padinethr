<?php
ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller{
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
        $this->load->model('employment_m', 'employment_m');
        $this->load->model('level_m', 'level_m');
        $this->load->model('city_m', 'city_m');
        $this->load->model('role_m', 'role_m');
        $this->load->model('approval_m', 'approval_m');
        $this->load->model('adjustment_m', 'adjustment_m');
        $this->load->model('dispensation_m', 'dispensation_m');
		$this->load->model('holiday_m', 'holiday_m');

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

        if($this->session->userdata('new_kondisi')) $this->session->unset_userdata('new_kondisi');
        if($this->session->userdata('nik_employee')) $this->session->unset_userdata('nik_employee');
        if($this->session->userdata('fullname')) $this->session->unset_userdata('fullname');
        if($this->session->userdata('level_name')) $this->session->unset_userdata('level_name');
        if($this->session->userdata('position')) $this->session->unset_userdata('position');
        if($this->session->userdata('name_role')) $this->session->unset_userdata('name_role');
        if($this->session->userdata('status')) $this->session->unset_userdata('status');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/index/";
        $config["total_rows"] = $this->member_m->jum_employee();
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
        $data['list_member'] = $this->member_m->select_allpaging($config["per_page"], $page);
        $i=0;
        foreach($data['list_member'] as $member){
        	$cek_reset = $this->leave_m->select_leave_history_employee($member['id_employee']);
        	if(!empty($cek_reset)) $data['list_member'][$i]['button_reset'] = FALSE;
        	else $data['list_member'][$i]['button_reset'] = TRUE;

        	$i++;
		}

        $this->load->view('em_employee_v', $data);

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
            $data['nik_employee'] = $this->input->post('search_nik_employee');
            $data['fullname'] = $this->input->post('search_fullname');
            $data['level_name'] = $this->input->post('search_level_name');
            $data['name_role'] = $this->input->post('search_name_role');
            $data['status'] = $this->input->post('search_status');
			$data['position'] = $this->input->post('search_position');

            $datasrc = array(
                'nik_employee'  => $data['nik_employee'],
                'fullname'  => $data['fullname'],
                'level_name'  => $data['level_name'],
                'position'  => $data['position'],
                'name_role'  => $data['name_role'],
                'status'  => $data['status']
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['nik_employee'] != "") $kondisi[] = "nik_employee LIKE '%".$data['nik_employee']."%'";
            if($data['fullname'] != "") $kondisi[] = "fullname LIKE '%".$data['fullname']."%'";
            if($data['level_name'] != "") $kondisi[] = "l.level_name LIKE '%".$data['level_name']."%'";
            if($data['position'] != "") $kondisi[] = "position LIKE '%".$data['position']."%'";
            if($data['name_role'] != "") $kondisi[] = "name_role LIKE '%".$data['name_role']."%'";
            if($data['status'] != "") $kondisi[] = "e.status LIKE '%".$data['status']."%'";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee');
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_member'] = $this->member_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

            }
        }
        else {
            $new_kondisi = $this->session->userdata('new_kondisi');
            $data['nik_employee'] = $this->session->userdata('nik_employee');
            $data['fullname'] = $this->session->userdata('fullname');
            $data['level_name'] = $this->session->userdata('level_name');
            $data['position'] = $this->session->userdata('position');
            $data['name_role'] = $this->session->userdata('name_role');
            $data['status'] = $this->session->userdata('status');
            $data['list_member'] = $this->member_m->select_allpaging_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "employment/employee/search/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->member_m->jum_employee_search($new_kondisi);
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

		$i=0;
		foreach($data['list_member'] as $member){
			$cek_reset = $this->leave_m->select_leave_history_employee($member['id_employee']);
			if(!empty($cek_reset)) $data['list_member'][$i]['button_reset'] = FALSE;
			else $data['list_member'][$i]['button_reset'] = TRUE;

			$i++;
		}

        $this->load->view('em_employee_v', $data);

    }

    public function tambah_data(){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "add";
        $data['list_role'] = $this->role_m->select_all();
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('fullname', 'fullname', 'required');
        $this->form_validation->set_rules('nickname', 'nickname', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('nik_employee', 'nik_employee', 'required');
        $this->form_validation->set_rules('id_role', 'role', 'required');
        if ($this->form_validation->run()==FALSE){
//            var_dump($data);
            $get_status = $this->input->post('status');
            if ($get_status==1) $data['status'] = "checked";

            $data['select_id_role'] = $this->input->post('select_id_role');
//			$data['select_employee_leave_type'] = $this->input->post('select_employee_leave_type');
            $this->load->view('em_addemployee_v', $data);
        }
        else {
            $data_formprof['fullname'] = $this->input->post('fullname');
            $data_formprof['nickname'] = $this->input->post('nickname');
            $data_formprof['email'] = $this->input->post('email');
            $data_formprof['birthdate'] = $this->input->post('birthdate');
            $data_formprof['address'] = $this->input->post('address');
			$data_formprof['address_2'] = $this->input->post('address_2');
            $data_formprof['nik_employee'] = $this->input->post('nik_employee');
            $data_formprof['npwp'] = $this->input->post('npwp');
            $data_formprof['identity_number'] = $this->input->post('identity_number');
            $data_formprof['phone_number'] = $this->input->post('phone_number');
            $data_formprof['id_role'] = $this->input->post('id_role');
//			$data_formprof['employee_leave_type'] = $this->input->post('employee_leave_type');

            $get_status = $this->input->post('status');
            if ($get_status==1) $data_formprof['status'] = 1;
            else $data_formprof['status'] = 0;

            $this->up_imgprofile();
            $upload_data=$this->upload->data();
            $data_formprof['photo'] = $upload_data['file_name'];
			echo $upload_data['file_name'];
			echo "asu";

//            $get_password = $this->generateRandomString();
//            $data_formprof['password'] = $this->generatePassword($get_password);
//
//            $data_formprof['password_asli'] = $get_password;
//
//            $this->sendpassmail($data_formprof['email'], $data_formprof['nickname'], $data_formprof['password']);
//
//
//            $this->member_m->insert_employee($data_formprof);
//            $this->session->set_flashdata('pesan', 'Anda telah berhasil menambah data.');
//            redirect(base_url().'employment/employee');
        }
    }

    public function detil_data($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');
//        $bulanke = 4;
//        $jatahcuti = 14;
//        $z = (9/12) * 15;
//        echo $z;
//        echo date('n');
        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        if ($data['list_employee'][0]['status'] == 1) $data['list_employee'][0]['status_aktif'] = "checked";
        else $data['list_employee'][0]['status_aktif'] = "";

        $data['list_employment'] = $this->employment_m->select_active_employment($id_employee);
        $data['list_level'] = $this->level_m->select_all();
        $data['list_city'] = $this->city_m->select_all();
        $data['list_all_employee'] = $this->member_m->select_all_active();
        $data['list_role'] = $this->role_m->select_all();
        $data['list_personal_leave'] = $this->list_personal_leave($id_employee);
        $data['list_adjustment'] = $this->adjustment_m->select_adjustment_employee($id_employee);

        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "employee_profile";


        //cek jumlah history, jika masih nol maka muncul hanya januari
        //cek jika lebih dari satu maka apakah ada yg sama dengan tahun ini, jika tidak maka muncul, jika ada maka tidak muncul
        $data['button_reset'] = FALSE;
        $this_year = date("Y");
        $this_month = date("m");
        $get_year_history = $this->leave_m->select_detil_leave_history($id_employee, $this_year);

        if($get_year_history == 0 && $this_month == 01){ //muncul hanya bulan januari
            $data['button_reset'] = TRUE;
        }


        $data['startnum'] = 1;
        $data['status_form'] = "edit";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('fullname', 'fullname', 'required');
        $this->form_validation->set_rules('nickname', 'nickname', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('nik_employee', 'nik_employee', 'required');
//		$this->form_validation->set_rules('employee_leave_type', 'employee_leave_type', 'required');
        $this->form_validation->set_rules('id_role', 'role', 'required');
        if ($this->form_validation->run()==FALSE){
            $get_status = $this->input->post('status');
            if ($get_status==1) $data['status'] = "checked";
            $data['select_id_role'] = $this->input->post('select_id_role');
            $data['select_adjustment_type'] = $this->input->post('select_adjustment_type');
//			$data['select_employee_leave_type'] = $this->input->post('select_employee_leave_type');

            $this->load->view('em_editemployee_v', $data);
        }
        else {
            $data_formprof['fullname'] = $this->input->post('fullname');
            $data_formprof['nickname'] = $this->input->post('nickname');
            $data_formprof['email'] = $this->input->post('email');
            $data_formprof['birthdate'] = $this->input->post('birthdate');
            $data_formprof['address'] = $this->input->post('address');
			$data_formprof['address_2'] = $this->input->post('address_2');
            $data_formprof['nik_employee'] = $this->input->post('nik_employee');
            $data_formprof['npwp'] = $this->input->post('npwp');
            $data_formprof['identity_number'] = $this->input->post('identity_number');
            $data_formprof['phone_number'] = $this->input->post('phone_number');
            $data_formprof['id_role'] = $this->input->post('id_role');
//			$data_formprof['employee_leave_type'] = $this->input->post('employee_leave_type');

            $get_status = $this->input->post('status');
            if ($get_status==1) $data_formprof['status'] = 1;
            else $data_formprof['status'] = 0;

            $this->up_imgprofile();
            $upload_data=$this->upload->data();
            $data_formprof['photo'] = $upload_data['file_name'];

            $this->member_m->update_employee($id_employee, $data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data.');
            redirect(base_url().'employment/employee');
        }
    }

    public function personal_leave_search($id_employee, $sortby, $sorttype) {
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_leave";

        if ($data['current_user'][0]['id_city'] == 1 || $data['current_user'][0]['id_city'] == 2) $weekendtype = "satsun";
        else $weekendtype = "sun";
        $data['active'] = "personal_leave";

        if($this->uri->segment(8)) $page = ($this->uri->segment(8));
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
//            $data['days'] = $this->input->post('search_days');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'submission_date'  => $data['submission_date'],
                'start_date'  => $data['start_date'],
                'end_date'  => $data['end_date'],
//                'days'  => $data['days'],
                'description'  => $data['description'],
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['submission_date'] != "") $kondisi[] = "submission_date LIKE '%".$data['submission_date']."%'";
            if($data['start_date'] != "") $kondisi[] = "start_date LIKE '%".$data['start_date']."%'";
            if($data['end_date'] != "") $kondisi[] = "end_date LIKE '%".$data['end_date']."%'";
//            if($data['days'] != "") $kondisi[] = "days LIKE '%".$data['days']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";
            $kondisi[] = "id_employee = ".$id_employee."";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee/personal_leave/'.$id_employee);
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);

                $i = 0;
                foreach ($data['list_personal_leave'] as $leave){
                    $id_leave = $data['list_personal_leave'][$i]['id_leave'];
                    $approval = $this->approval_m->select_leave_approval($id_leave);
                    if ($approval != "") {
                        $j = 1;
                        foreach ($approval as $app) {
                            $data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                            $data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
                            $data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                            $j++;
                        }
                    }
                    $data['list_personal_leave'][$i]['day'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype, $data['list_personal_leave'][$i]['dispensation_quota_days']);
                    $data['list_personal_leave'][$i]['dispensation_quota_days'] = $this->get_jum_dispensation_leave($id_leave);
                    $i++;
                }


            }
        }
        else {
            //$new_kondisi = $this->session->userdata('new_kondisi');

            $data['submission_date'] = $this->session->userdata('submission_date');
            $data['start_date'] = $this->session->userdata('start_date');
            $data['end_date'] = $this->session->userdata('end_date');
//            $data['days'] = $this->session->userdata('days');
            $data['description'] = $this->session->userdata('description');

            $kondisi = "id_employee = ".$id_employee."";
            $new_kondisi = "WHERE ". $kondisi;

            $data['list_personal_leave'] = $this->leave_m->select_allpaging_personal_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
            $i = 0;
            foreach ($data['list_personal_leave'] as $leave){
                $id_leave = $data['list_personal_leave'][$i]['id_leave'];
                $approval = $this->approval_m->select_leave_approval($id_leave);
                if ($approval != "") {
                    $j = 1;
                    foreach ($approval as $app) {
                        $data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
                        $data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
                        $data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                        $j++;
                    }
                }
                $data['list_personal_leave'][$i]['day'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype);
                $i++;
            }
        }

        $config["base_url"] = base_url() . "employment/employee/personal_leave_search/".$id_employee."/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->leave_m->jum_leave_personal_search($new_kondisi);
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

        $this->load->view('em_personal_leave_v', $data);
    }

    public function personal_leave($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
		$data['current_employee'] = $this->member_m->select_detil_employee($id_employee);
		if ($data['current_employee'][0]['id_city'] == 1 || $data['current_employee'][0]['id_city'] == 2) $weekendtype = "satsun";
		else $weekendtype = "sun";
        $data['active'] = "personal_leave";

        if($this->session->userdata('submission_date')) $this->session->unset_userdata('submission_date');
        if($this->session->userdata('start_date')) $this->session->unset_userdata('start_date');
        if($this->session->userdata('end_date')) $this->session->unset_userdata('end_date');
        if($this->session->userdata('days')) $this->session->unset_userdata('days');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/personal_leave/".$id_employee."/";
        $config["total_rows"] = $this->leave_m->jum_leave_personal($id_employee);
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
        if($this->uri->segment(6)) $page = ($this->uri->segment(6));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_leave'] = $this->list_personal_leave($id_employee);

		$i = 0;
		foreach ($data['list_personal_leave'] as $leave){
			$id_leave = $data['list_personal_leave'][$i]['id_leave'];
			$approval = $this->approval_m->select_leave_approval($id_leave);
			if ($approval != "") {
				$j = 1;
				foreach ($approval as $app) {
					$data['list_personal_leave'][$i]{'app_status_'.$j} = $app["ap_status"];
					$data['list_personal_leave'][$i]{'app_name_'.$j} = $app["name_role"];
					$data['list_personal_leave'][$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
					$j++;
				}
			}
            $data['list_personal_leave'][$i]['dispensation_quota_days'] = $this->get_jum_dispensation_leave($id_leave);
			$data['list_personal_leave'][$i]['day'] = $this->count_days($data['list_personal_leave'][$i]['start_date'], $data['list_personal_leave'][$i]['end_date'], $weekendtype, $data['list_personal_leave'][$i]['dispensation_quota_days']);

			$i++;
		}
        $this->load->view('em_personal_leave_v', $data);
    }

    public function personal_adjustment_search($id_employee, $sortby, $sorttype) {
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_adjustment";

        if($this->uri->segment(8)) $page = ($this->uri->segment(8));
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
            $data['adjustment_type'] = $this->input->post('search_adjustment_type');
            $data['quota'] = $this->input->post('search_quota');
            $data['description'] = $this->input->post('search_description');

            $datasrc = array(
                'adjustment_type'  => $data['adjustment_type'],
                'quota'  => $data['quota'],
                'description'  => $data['description'],
            );
            $this->session->set_userdata($datasrc);
            $kondisi = array();
            if($data['adjustment_type'] != "") $kondisi[] = "adjustment_type LIKE '%".$data['adjustment_type']."%'";
            if($data['quota'] != "") $kondisi[] = "quota LIKE '%".$data['quota']."%'";
            if($data['description'] != "") $kondisi[] = "description LIKE '%".$data['description']."%'";
            if($data['datetime'] != "") $kondisi[] = "datetime LIKE '%".$data['datetime']."%'";
            $kondisi[] = "id_employee = ".$id_employee."";
//            var_dump($kondisi);
            if(empty($kondisi)){
                redirect(base_url().'employment/employee/personal_adjustment/'.$id_employee);
            }
            else {
                if (count($kondisi)>1) $new_kondisi = implode(" AND ",$kondisi);
                else if (count($kondisi)==1) $new_kondisi = implode($kondisi);

                //echo $new_kondisi;
                $new_kondisi = "WHERE ". $new_kondisi;
                $this->session->set_userdata('new_kondisi',$new_kondisi);
                $data['list_personal_adjustment'] = $this->adjustment_m->select_allpaging_adjustment_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);


            }
        }
        else {
            //$new_kondisi = $this->session->userdata('new_kondisi');

            $data['adjustment_type'] = $this->session->userdata('adjustment_type');
            $data['quota'] = $this->session->userdata('quota');
            $data['description'] = $this->session->userdata('description');
            $data['datetime'] = $this->session->userdata('datetime');

            $kondisi = "id_employee = ".$id_employee."";
            $new_kondisi = "WHERE ". $kondisi;

            $data['list_personal_adjustment'] = $this->adjustment_m->select_allpaging_adjustment_search ($new_kondisi, $orderby, $ordertype, $per_page, $page);
        }

        $config["base_url"] = base_url() . "employment/employee/personal_adjustment_search/".$id_employee."/".$sortby."/".$sorttype."/"; //ngambil var url tanpa diproses, karna jika pindah halaman tidak perlu ganti flag
        $config["total_rows"] = $this->adjustment_m->jum_adjustment_personal_search($new_kondisi);
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

        $this->load->view('em_personal_adjustment_v', $data);
    }

    public function personal_adjustment($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['selected_employee_id'] = $id_employee;
        $data['active'] = "personal_adjustment";

        if($this->session->userdata('adjustment_type')) $this->session->unset_userdata('adjustment_type');
        if($this->session->userdata('quota')) $this->session->unset_userdata('quota');
        if($this->session->userdata('description')) $this->session->unset_userdata('description');
        $data['typeorder'] = 0;
        $data['select_adjustment_type'] = $this->input->post('select_adjustment_type');
        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        if ($data['list_employee'][0]['status'] == 1) $data['list_employee'][0]['status_aktif'] = "checked";
        else $data['list_employee'][0]['status_aktif'] = "";

        $config["base_url"] = base_url() . "employment/employee/personal_adjustment/".$id_employee."/";
        $config["total_rows"] = $this->adjustment_m->jum_adjustment_personal($id_employee);
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
        if($this->uri->segment(5)) $page = ($this->uri->segment(5));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_adjustment'] = $this->adjustment_m->select_adjustment_employee($id_employee);
        $this->load->view('em_personal_adjustment_v', $data);
    }

    public function personal_employment($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');
		$data['list_employment_all'] = $this->employment_m->select_all_employee($id_employee);
		$data['startnum'] = 1;

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);
        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        $data['selected_employee_id'] = $id_employee;
        $data['list_level'] = $this->level_m->select_all();
        $data['list_city'] = $this->city_m->select_all();
        $data['list_all_employee'] = $this->member_m->select_all_active();
        $data['list_role'] = $this->role_m->select_all();
        $data['active'] = "personal_employment";
		$data['list_employment'] = $this->employment_m->select_active_employment($id_employee);
		if(!empty($data['list_employment'])){
			$data['status_form'] = "edit";
		}
        else $data['status_form'] = "add";
		$data['select_id_level'] = $this->input->post('select_id_level');
		$data['select_id_city'] = $this->input->post('select_id_city');
		$data['select_id_employee'] = $this->input->post('select_id_employee');

        //cek jumlah history, jika masih nol maka muncul hanya januari
        //cek jika lebih dari satu maka apakah ada yg sama dengan tahun ini, jika tidak maka muncul, jika ada maka tidak muncul
        $data['button_reset'] = FALSE;
        $this_year = date("Y");
        $this_month = date("m");
        $get_year_history = $this->leave_m->select_detil_leave_history($id_employee, $this_year);

        if($get_year_history == 0 && $this_month == 01){ //muncul hanya bulan januari
            $data['button_reset'] = TRUE;
        }

        if($this->session->userdata('nik_employee')) $this->session->unset_userdata('nik_employee');
        if($this->session->userdata('fullname')) $this->session->unset_userdata('fullname');
        if($this->session->userdata('level_name')) $this->session->unset_userdata('level_name');
        if($this->session->userdata('position')) $this->session->unset_userdata('position');
        if($this->session->userdata('name_role')) $this->session->unset_userdata('name_role');
        if($this->session->userdata('status')) $this->session->unset_userdata('status');
        $data['typeorder'] = 0;

        $config["base_url"] = base_url() . "employment/employee/";
        $config["total_rows"] = $this->member_m->jum_employee();
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
        if($this->uri->segment(5)) $page = ($this->uri->segment(5));
        else $page = 0;
        $data['halaman'] = $this->pagination->create_links();
        $data['startnum'] = $page + 1;
        $data['list_personal_employment'] = $this->employment_m->select_active_employment($id_employee);


        $year_select = date('Y');
        $leave_quota_employment = $this->get_employment_quota_leave($id_employee, $year_select);
        $jum_quota_adjustment = $this->get_adjustment_quota($id_employee, $year_select);
        $current_leave_quota = $this->get_final_leave_quota($id_employee, $year_select, $leave_quota_employment, $jum_quota_adjustment);

        $quota_origin = $this->cek_employee_leave_extend($id_employee, $year_select, $current_leave_quota['quota_origin']);

        $minus_quota_prev_year = $this->get_minus_quota_prev_year($id_employee, $year_select);

        $leave_quota_remaining = $quota_origin - $minus_quota_prev_year;
        $leave_quota_debt_remaining = $current_leave_quota['debt_quota'];

        $data['leave_quota_remaining'] = $leave_quota_remaining;
        $data['leave_quota_debt_remaining'] = $leave_quota_debt_remaining;

        $this->load->view('em_personal_employment_v', $data);
    }

    public function employment_history($id_employee){
        if($this->log_super_hr_staff()){
        }
        else redirect(base_url().'dashboard');

        $data['list_employee'] = $this->member_m->select_detil_employee($id_employee);
        $data['list_employment'] = $this->employment_m->select_all_employee($id_employee);
        $data['startnum'] = 1;
        $this->load->view('em_employment_v', $data);
    }

    private function list_personal_leave($id_member){
        $list_personal_leave = $this->leave_m->select_personal_leave($id_member);

        $i = 0;
        foreach ($list_personal_leave as $leave){
            $id_leave = $list_personal_leave[$i]['id_leave'];
            $approval = $this->approval_m->select_leave_approval($id_leave);
            if ($approval != "") {
                $j = 1;
                foreach ($approval as $app) {
                    $list_personal_leave[$i]{'app_status_'.$j} = $app["ap_status"];
                    $list_personal_leave[$i]{'app_name_'.$j} = $app["name_role"];
					$list_personal_leave[$i]{'app_reason_reject_'.$j} = $app["reason_reject"];
                    $j++;
                }
            }
            $i++;
        }
        return $list_personal_leave;
    }



    public function tambah_data_employment($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $data['status_form'] = "add";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('id_level', 'level', 'required');
        $this->form_validation->set_rules('id_city', 'city', 'required');
        $this->form_validation->set_rules('position', 'position', 'required');
        $this->form_validation->set_rules('tgl_mulai', 'tgl_mulai', 'required');

        if ($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('pesan_gagal', 'Anda gagal mengubah data employment.');
            redirect(base_url().'employment/employee/detil_data/'.$id_employee);
        }
        else {

            $data_formprof['id_level'] = $this->input->post('id_level');
            $data_formprof['id_city'] = $this->input->post('id_city');
            $data_formprof['supervisor'] = $this->input->post('id_employee');
            $data_formprof['position'] = $this->input->post('position');
            $data_formprof['tgl_mulai'] = $this->input->post('tgl_mulai');
            $data_formprof['description'] = $this->input->post('description');
			$data_formprof['employee_leave_type'] = $this->input->post('employee_leave_type');
            $data_formprof['status'] = 1;


            $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
            if(empty($current_employment)){
                $leave_quota_ext_set = 3;
                $data_formprof['leave_quota'] = $this->count_level_quota_prorate($data_formprof['id_level'], $data_formprof['tgl_mulai']);
            } else {
                $leave_quota_ext_set = $current_employment[0]['leave_quota_ext'];
                $data_formprof['leave_quota'] = $this->count_level_quota($id_employee, $this->input->post('id_level'), $data_formprof['tgl_mulai']);
            }

            $data_formprof['leave_quota_ext'] = $leave_quota_ext_set;
            $data_formprof['id_employee'] = $id_employee;

			$data_formup['status'] = 0;
			$data_formup['tgl_berakhir'] = $data_formprof['tgl_mulai'];
          	$this->employment_m->update_employment_active($id_employee, $data_formup);

            $this->employment_m->insert_employment($data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil mengubah data employment.');
            redirect(base_url().'employment/employee/personal_employment/'.$id_employee);

        }
    }

    public function tambah_data_adjustment($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $id_current_user = $this->session->userdata('id_employee');
        $data['current_user'] = $this->member_m->select_detil_employee($id_current_user);

        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);

        $data['status_form'] = "add";
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', ' ');
        $this->form_validation->set_rules('adjustment_type', 'adjustment_type', 'required');
        $this->form_validation->set_rules('quota', 'quota', 'required');

        if ($this->form_validation->run()==FALSE){
            $this->session->set_flashdata('pesan_gagal', 'Anda gagal mengubah data adjustment.');
            redirect(base_url().'employment/employee/detil_data/'.$id_employee);
        }
        else {
            $data_formprof['id_employee'] = $id_employee;
            $data_formprof['adjustment_type'] = $this->input->post('adjustment_type');
            $data_formprof['quota'] = $this->input->post('quota');
            $data_formprof['description'] = $this->input->post('description');
            $data_formprof['datetime'] = $this->today_datetime();

            $this->count_new_quota($id_employee, $data_formprof['adjustment_type'], $data_formprof['quota']);//update employment

            $this->adjustment_m->insert_adjustment($data_formprof);
            $this->session->set_flashdata('pesan', 'Anda telah berhasil menanmbahkan data leave adjustment.');
            redirect(base_url().'employment/employee/personal_adjustment/'.$id_employee);
        }
    }

    private function count_new_quota($id_employee, $type_adjustment, $quota_adjustment){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $id_employment = $current_employment[0]['id_employment'];

        if($type_adjustment == 'Adjustment'){
            $data_ad['leave_quota'] = $current_employment[0]['leave_quota'] + $quota_adjustment;
        }
        else if($type_adjustment == 'Deduction'){
            $data_ad['leave_quota'] = $current_employment[0]['leave_quota'] - $quota_adjustment;
        }
        $this->employment_m->update_employment($id_employment, $data_ad);
    }

    private function count_level_quota_prorate($id_level_employee, $tgl_mulai){
        $get_cur_quota = $this->level_m->select_detil_level($id_level_employee);
        $cur_level_quota = $get_cur_quota[0]['level_quota']; //level lama/skrg

        $get_month_cur = date('m', strtotime($tgl_mulai));
        $number_date = date('j', strtotime($tgl_mulai));
        if ($number_date > 14) $month_cur = $get_month_cur;
        else $month_cur = $get_month_cur - 1;
        $month_remaining = 12 - $month_cur;

        $count_current_month = round(($month_remaining / 12),2);
        $result_current = $count_current_month * $cur_level_quota;

        $result = round($result_current, 0);
        return $result;
    }

    private function count_level_quota($id_employee, $id_level, $tgl_mulai){
        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $jum_employment = $this->employment_m->jum_employment_staff($id_employee);

        if($jum_employment == 0 ) { //jika input employment pertama kali
            $get_level = $this->level_m->select_detil_level($id_level);
            $leave_quota = $get_level[0]['level_quota'];
        } else {
            if ( $current_employment[0]['id_level'] == $id_level){ // jika level sama dengan input, maka tidak akan merubah jumlah quota sebelumnya
                $leave_quota = $current_employment[0]['leave_quota'];
            }
            else {
                //jika level berubah maka menghitung total bulan cuti level sebelumnya dan bulan cuti yg baru
                //algoritma :
                // get1 = (bulan kuota / kuota level saat ini) * kuota level saat ini
                // get2 = (bulan kuota / kuota level baru) * kuota level baru
                // kuota terbaru = get1 + get2 - kuota yg sudah dipakai

                $current_quota = $current_employment[0]['leave_quota']; //jumlah skrg
                $current_id_level = $current_employment[0]['id_level'];

                $get_cur_quota = $this->level_m->select_detil_level($current_id_level);
                $cur_level_quota = $get_cur_quota[0]['level_quota']; //level lama/skrg

                $get_post_quota = $this->level_m->select_detil_level($id_level);
                $post_level_quota = $get_post_quota[0]['level_quota']; //level terbaru

                $get_month_cur = date('m');
                $number_date = date('j', strtotime($tgl_mulai));
                if ($number_date > 14) $month_cur = $get_month_cur;
                else $month_cur = $get_month_cur - 1;
                $month_remaining = 12 - $month_cur;

                $used_quota = $cur_level_quota - $current_quota;

                $count_current_month = round(($month_cur / 12),2);
                $count_remaining_month = round(($month_remaining / 12),2);
                $result_current = $count_current_month * $cur_level_quota;
                $result_post = $count_remaining_month * $post_level_quota;

                $leave_quota = round(($result_current + $result_post),0) - $used_quota;
            }
        }
        return $leave_quota;
    }

    public function reset_leave($id_employee){
        if($this->log_super_hr()){
        }
        else redirect(base_url().'dashboard');

        $current_employment = $this->employment_m->select_detil_employment_active($id_employee);
        $id_employment = $current_employment[0]['id_employment'];
        $id_employee = $current_employment[0]['id_employee'];
        $current_level = $current_employment[0]['id_level'];
        $debt_leave_quota = 3 - $current_employment[0]['leave_quota_ext'];

        $detil_level = $this->level_m->select_detil_level($current_level);
        $level_quota = $detil_level[0]['level_quota'];

        $data_formem['leave_quota'] = $level_quota - $debt_leave_quota;
        $this->employment_m->update_employment($id_employment, $data_formem);

        $data_formhs['year_leave_history'] = date("Y");
        $data_formhs['id_employee'] = $id_employee;
        $this->leave_m->insert_leave_history($data_formhs);//belum dibuat di halaman view, reset tidak muncul jika reset tahun ini sudah masuk history

        $this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan reset <i>leave quota</i> pada karyawan ini.');
        redirect(base_url().'employment/employee/detil_data/'.$id_employee);
    }

	public function multiple_reset_leave(){
		if($this->log_super_hr()){
		}
		else redirect(base_url().'dashboard');

		if ($this->input->post('chkbox_reset')){
			$data_id = $this->input->post('chkbox_reset');
			//var_dump($data_id);
			foreach ($data_id as $id_employee){
				$current_employment = $this->employment_m->select_detil_employment_active($id_employee);
				$id_employment = $current_employment[0]['id_employment'];
				$id_employee = $current_employment[0]['id_employee'];
				$current_level = $current_employment[0]['id_level'];
				$debt_leave_quota = 3 - $current_employment[0]['leave_quota_ext'];

				$detil_level = $this->level_m->select_detil_level($current_level);
				$level_quota = $detil_level[0]['level_quota'];

				$data_formem['leave_quota'] = $level_quota - $debt_leave_quota;
				$this->employment_m->update_employment($id_employment, $data_formem);

				$data_formhs['year_leave_history'] = date("Y");
				$data_formhs['id_employee'] = $id_employee;
				$this->leave_m->insert_leave_history($data_formhs);
			}

			$this->session->set_flashdata('pesan', 'Anda telah berhasil melakukan reset <i>leave quota</i> pada beberapa karyawan.');
			redirect(base_url().'employment/employee/');
		} else redirect(base_url().'employment/employee/');


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

    private function count_days($start_date, $end_date, $weekendtype, $dispensation_quota) {
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
        //$holidays = array('2018-07-18'); // harilibur nasional
        $holidays = $this->joint_holiday();

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
        $days = $days - $dispensation_quota;
        return $days;
    }

    private function get_jum_dispensation_leave($id_leave){
        $jum_all_dispensation_quota = 0;
        $data_dispensation = $this->dispensation_m->select_dispensation_leave($id_leave);
        if(!empty($data_dispensation)){
            foreach($data_dispensation as $disp){
                $list_dispensation[] = $disp['dispensation_quota'];
            }
            $jum_all_dispensation_quota = array_sum($list_dispensation);
        }

        return $jum_all_dispensation_quota;
    }

	private function joint_holiday() {
		$list_holiday = $this->holiday_m->select_all();
		$all_holiday = array();

		foreach($list_holiday as $holiday){
			$all_holiday[] = $holiday['date_holiday'];
		}

		return $all_holiday;
	}

    private function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generatePassword($randomString){
        $pengacak = "1S4I0V2I1A9A9Z7IZAH";
        $resPassword = password_hash($randomString.$pengacak, PASSWORD_BCRYPT);

        return $resPassword;
    }

    private function sendpassmail($to, $nickname, $password){
        $from_email = "yudha.will.stay@gmail.com";
        $name = "HRD Padinet";
        $subject = "Akses Aplikasi Cuti PadiNET";
        $message = "Dear ".$nickname.", berikut passwordmu :". $password;

        $this->load->library('email');
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);

        $this->email->from($from_email);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
    }

    private function today_datetime(){
        $datestring = '%Y-%m-%d %h:%i:%s';
        $time = time();
        $tanggal = mdate($datestring, $time);

        return $tanggal;
    }

    //////////////////////////////////////
    ///  start new algoritma
    //////////////////////////////////////

    private function get_employment_quota_leave($id_current_user, $year_select){
        $first_employment = $this->employment_m->select_first_row_employment($id_current_user);
        if (!empty($first_employment)){
            $first_employment = json_decode(json_encode($first_employment),true); //konversi stdclass to array

            //$date_start_string = "2013-03-15";
            $date_start_string = $first_employment['tgl_mulai'];
            $date_start = date_create($date_start_string);
            date_add($date_start,date_interval_create_from_date_string("1 year"));
            $date_one_year = date_format($date_start,"Y-m-d");

            if( strtotime('now') >= strtotime($date_one_year) ) {

                //cek tahun -> if tahun selisih dua tahun maka januari prorate
                //jika kurang dari satu tahun maka prorate current month
                $year_current = $year_select;
                $start_date_select_current_year = $year_current."-01-01";
                $end_date_select_current_year = $year_current."-12-31";

                $get_year_date_one_year = date('Y', strtotime($date_one_year));

                $personal_employment_year = $this->employment_m->employment_staff_year($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
                $list_emp_level = array();
                $list_jum_leave = array();
                $bulan_akhir = 12;
                $list_month_employment_full_quota[0] = "1"; //sebagai bulan januari untuk array full quota


                foreach($personal_employment_year as $emp){
                    $list_emp_level[] = $emp['id_level'];
                    $data_level = $this->level_m->select_detil_level($emp['id_level']);
                    $list_emp_quota[] = $data_level[0]['level_quota'];

                    $number_month = date('n', strtotime($emp['tgl_mulai']));
                    $number_date = date('i', strtotime($emp['tgl_mulai']));
                    if ($number_date > 14) $month_number_new = $number_month + 1;
                    else $month_number_new = $number_month;

                    $list_month_employment_full_quota[] = $month_number_new;
                    $list_month_employment_prorate[] =  $month_number_new;
                }

                if ($year_current - $get_year_date_one_year == 0) {
                    // harus dibedakan antara employment tahun pertama
                    // karena tahun pertama adalah dihitung tgl start kebawah dari employment pertama
                    // sedangkan tahun kedua dihitung per januari, dan dihitung dari employmen kedua dst
                    $i = 0;
                    foreach ($list_month_employment_prorate as $month) {
                        $j = $i + 1;

                        if (isset($list_month_employment_prorate[$j])) {
                            $jarak_bulan = $list_month_employment_prorate[$j] - $list_month_employment_prorate[$i];
                            $list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
                        } else {
                            $jarak_bulan = $bulan_akhir - $list_month_employment_prorate[$i] + 1 ;
                            $list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
                        }
                        $i++;
                    }
                    $total_jum_leave = array_sum($list_jum_leave);

                } else if ($year_current - $get_year_date_one_year >= 1) {//jika tahun kedua
                    // pengecekan next array untuk menghitung jarak antar bulan
                    // berlaku jika ada 3 array / 3 bulan dalam satu tahun
                    // array pertama pasti januari. array bulan kedua menghitung jumlah bulan sampai bulan array ke tiga

                    $i = 0;
                    foreach ($list_month_employment_full_quota as $month) {
                        $j = $i + 1;
                        if (isset($list_month_employment_full_quota[$j])) {
                            $jarak_bulan = $list_month_employment_full_quota[$j] - $list_month_employment_full_quota[$i];
                            $list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
                        } else {
                            $jarak_bulan = $bulan_akhir - $list_month_employment_full_quota[$i] + 1;
                            $list_jum_leave[] = ($jarak_bulan / 12) * $list_emp_quota[$i];
                        }
                        $i++;
                    }
                    $total_jum_leave = array_sum($list_jum_leave);
                } else {
                    $total_jum_leave = 0;
                    //tanpa cuti
                }
            } else {
                $total_jum_leave = 0;
                //tanpa cuti
            }


        } else {
            $total_jum_leave = 0;
        }

        return round($total_jum_leave);
    }

    private function get_adjustment_quota($id_current_user, $year_select){
        $year_current = $year_select;
        $start_date_select_current_year = $year_current."-01-01";
        $end_date_select_current_year = $year_current."-12-31";

        $list_adjusment_personal = $this->adjustment_m->select_adjustment_employee_thisyear($id_current_user, $start_date_select_current_year, $end_date_select_current_year);
        $list_adjustment_quota = array();
        $list_deduction_quota = array();
        if(!empty($list_adjusment_personal)){

            $i=0;
            foreach ($list_adjusment_personal as $adj){
                if($list_adjusment_personal[$i]['adjustment_type'] == 'Adjustment'){
                    $list_adjustment_quota[] = $list_adjusment_personal[$i]['quota'];
                }
                else if ($list_adjusment_personal[$i]['adjustment_type'] == 'Deduction'){
                    $list_deduction_quota[] = $list_adjusment_personal[$i]['quota'];
                }
                $i++;
            }
            $jum_adjustment_quota = array_sum($list_adjustment_quota);
            $jum_deduction_quota = array_sum($list_deduction_quota);

            $result =  $jum_adjustment_quota - $jum_deduction_quota;
        }
        else $result = 0;

        return $result;
    }

    private function final_quota_minus_personal($id_current_user, $year_select, $user_city){
        $leave_quota_employment = $this->get_employment_quota_leave($id_current_user, $year_select);
        $jum_leave_personal = $this->get_leave_with_dispensation($id_current_user, $user_city, $year_select);
        $jum_quota_adjustment = $this->get_adjustment_quota($id_current_user, $year_select);

        $minus_quota = ($leave_quota_employment + $jum_quota_adjustment) - $jum_leave_personal;
        return $minus_quota;
    }

    private function get_minus_quota_leave_last_year($id_current_user, $year_select, $user_city){
        $first_employment = $this->employment_m->select_first_row_employment($id_current_user);
        $debt_quota_last_year = 0;
        if (!empty($first_employment)) {
            $first_employment = json_decode(json_encode($first_employment), true); //konversi stdclass to array
            $tgl_pertama = $first_employment['tgl_mulai'];
            $get_year_date = date('Y', strtotime($tgl_pertama));
            if ($get_year_date < $year_select){
                $list_leave_debt = $this->leavedebt_m->select_leave_debt_personal_year($id_current_user, $get_year_date);
                if(!empty($list_leave_debt)){
                    foreach($list_leave_debt as $debt){
                        $debt_quota_last_year = $debt['quota_debt'];
                    }
                }
                else if(empty($list_leave_debt)){
                    //melakukan perhitungan tahun kemarin
                    //(employment+ adjustment) - leave request approved
                    $year_now = date('Y');
                    $year_select = $year_now - 1;
                    $minus_quota_last_year = $this->final_quota_minus_personal($id_current_user, $year_select, $user_city);
                    if($minus_quota_last_year >= 3) $debt_quota_last_year = 3;
                    else $debt_quota_last_year = $minus_quota_last_year;

                    $data_formdbt['id_employee'] = $id_current_user;
                    $data_formdbt['quota_debt'] = $minus_quota_last_year;
                    $data_formdbt['year'] =  $year_select;
                    $this->leavedebt_m->insert_leave_debt($data_formdbt);
                }
            }
        }
        return $debt_quota_last_year;
    }

    private function get_range_date($start_date, $end_date){
        $starting_date = date($start_date);
        $ending_date = date($end_date);
        $starting_date1 = date('Y-m-d', strtotime($starting_date.'-1 day'));
        while (strtotime($starting_date1) < strtotime($ending_date))
        {
            $starting_date1 = date('Y-m-d',strtotime($starting_date1.'+1 day'));
            $dates[] = $starting_date1;
        }

        return $dates;
    }

    private function get_list_date_leave_personal($id_employee){
        $leave_personal = array();
        $all_leave_date_personal = array();
        $data_leave = $this->leave_m->select_personal_leave($id_employee);
        if(!empty($data_leave)) {
            foreach ($data_leave as $leave) {
                $get_range_leave = $this->get_range_date($leave['start_date'], $leave['end_date']);
                $all_leave_date_personal = array_merge($leave_personal, $get_range_leave);
            }
        }
        return $all_leave_date_personal;
    }



    private function get_jum_dispensation_personal($id_employee, $year_select){
        $jum_all_dispensation_quota = 0;
        $data_dispensation = $this->dispensation_m->select_dispensation_employee_year($id_employee, $year_select);
        if(!empty($data_dispensation)){
            foreach($data_dispensation as $disp){
                $list_dispensation[] = $disp['dispensation_quota'];
            }
            $jum_all_dispensation_quota = array_sum($list_dispensation);
        }

        return $jum_all_dispensation_quota;
    }

    private function get_date_leave_personal_select_year($all_leave_date_personal, $year_select){
        $list_leave_current_year = array();
        foreach ($all_leave_date_personal as $date_leave){
            $get_year = date('Y', strtotime($date_leave));
            if ($get_year == $year_select){
                $list_leave_current_year[] = $date_leave;
            }

        }

        return $list_leave_current_year;
    }


    private function get_jum_leave_after_holiday($get_date_leave_personal_select_year, $data_joint_holiday){
        $jum_day_all_leave_filter = count($get_date_leave_personal_select_year);
        foreach ($data_joint_holiday as $holiday){
            if (in_array($holiday, $get_date_leave_personal_select_year)){
                $jum_day_all_leave_filter--;
            }

        }
        return $jum_day_all_leave_filter;
    }

    private function get_final_leave_quota($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
        $data_quota = array();
        $data_joint_holiday = $this->joint_holiday();
        $jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

        $all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
        if(!empty($all_leave_date_personal)){
            $get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
            if(!empty($get_date_leave_personal_current_year)){
                $get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);

                $quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);

                if (($quota_now >= -3 && $quota_now < 0)){
                    $debt_quota = abs($quota_now);
                    $quota_origin = 0;
                }
                if($quota_now < -3) {
                    $debt_quota = 0; //debt quota saat ini, sudah terpakai oleh jatah leave
                    $quota_origin = 0;
                }
                else if($quota_now > 0){
                    $debt_quota = 3; //debt quota saat ini, sudah terpakai oleh jatah leave
                    $quota_origin = $quota_now;
                }
            } else {
                $debt_quota = 3;
                $quota_origin = $leave_quota_employment;
            }

        } else {
            $debt_quota = 3;
            $quota_origin = $leave_quota_employment;
        }

        $data_quota['debt_quota'] = $debt_quota;
        $data_quota['quota_origin'] = $quota_origin;

        return $data_quota;
    }


    private function get_final_leave_quota_last_year($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
        $debt_quota = 3;
        $data_joint_holiday = $this->joint_holiday();
        $jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

        $all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
        if(!empty($all_leave_date_personal)){
            $get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
            if(!empty($get_date_leave_personal_current_year)){
                $get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);

                $quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);

                if (($quota_now >= -3 && $quota_now < 0)){
                    $debt_quota = abs($quota_now);
                }
                if($quota_now < -3) {
                    $debt_quota = 0; //debt quota saat ini, sudah terpakai oleh jatah leave
                }
                else if($quota_now > 0){
                    $debt_quota = 3; //debt quota saat ini, sudah terpakai oleh jatah leave
                }
            }
        }
        return $debt_quota;
    }

    private function get_quota_left($id_current_user, $year_select, $leave_quota_employment, $jum_quota_adjustment){
        $quota_now = $leave_quota_employment;
        $data_joint_holiday = $this->joint_holiday();
        $jum_dispensation = $this->get_jum_dispensation_personal($id_current_user, $year_select);

        $all_leave_date_personal = $this->get_list_date_leave_personal($id_current_user);
        if(!empty($all_leave_date_personal)){
            $get_date_leave_personal_current_year = $this->get_date_leave_personal_select_year($all_leave_date_personal, $year_select);
            if(!empty($get_date_leave_personal_current_year)){
                $get_jum_leave_after_holiday = $this->get_jum_leave_after_holiday($get_date_leave_personal_current_year, $data_joint_holiday);
                $quota_now = $leave_quota_employment - ($get_jum_leave_after_holiday + $jum_quota_adjustment + $jum_dispensation);
            }
        }
        return $quota_now;
    }

    //start algoritma extend utk employee dgn masa cuti +15hari
    private function get_list_date_leave_personal_extend($id_employee, $year_select){
        $year_next = $year_select + 1;
        $date_start_next = $year_select."-12-31";
        $date_end_next = $year_next."-01-16";

        $leave_personal = array();
        $all_leave_date_personal_extend = array();
        $data_leave = $this->leave_m->select_personal_leave_extend($id_employee, $date_start_next, $date_end_next);
        if(!empty($data_leave)) {
            foreach ($data_leave as $leave) {
                $get_range_leave = $this->get_range_date($leave['start_date'], $leave['end_date']);
                $all_leave_date_personal_extend = array_merge($leave_personal, $get_range_leave);
            }
        }
        return $all_leave_date_personal_extend;
    }

    private function get_final_jum_leave_personal_extend($all_leave_date_personal_extend, $year_select, $id_employee){
        $data_joint_holiday = $this->joint_holiday();
        $jum_leave_extend_after_holiday = 0;
        if(!empty($all_leave_date_personal_extend)){
            $jum_leave_extend_after_holiday = $this->get_jum_leave_after_holiday($all_leave_date_personal_extend, $data_joint_holiday);
        }
        $jum_dispensation_personal_extend = $this->get_jum_dispensation_personal_extend($id_employee, $year_select);

        $final_jum_leave_extend = $jum_leave_extend_after_holiday - $jum_dispensation_personal_extend;
        return $final_jum_leave_extend;

    }

    private function get_jum_dispensation_personal_extend($id_employee, $year_select){
        $year_next = $year_select + 1;
        $date_start_next = $year_select."-12-31";
        $date_end_next = $year_next."-01-16";

        $jum_dispensation_quota = 0;
        $list_id_leave = array();
        $list_quota_dispensation = array();
        $data_leave = $this->leave_m->select_personal_leave_extend($id_employee, $date_start_next, $date_end_next);
        if(!empty($data_leave)) {
            foreach ($data_leave as $leave) {
                $list_id_leave[] = $leave['id_leave'];
            }

            foreach($list_id_leave as $id_leave){
                $data_quota_dispensation = $this->dispensation_m->select_dispensation_leave($id_leave);
                if(!empty($data_quota_dispensation)){
                    foreach ($data_quota_dispensation as $disp){
                        $list_quota_dispensation[] = $disp['dispensation_quota'];
                    }
                }
            }
        }
        $jum_dispensation_quota = array_sum($list_quota_dispensation);
        return $jum_dispensation_quota;
    }

    private function get_final_quota_leave_year_extend($id_employeer, $year_select){
        $all_leave_date_personal_extend = $this->get_list_date_leave_personal_extend($id_employeer, $year_select);
        $final_quota_leave_year_extend = $this->get_final_jum_leave_personal_extend($all_leave_date_personal_extend, $year_select,$id_employeer);

        return $final_quota_leave_year_extend;
    }
    //end algoritma extend utk employee dgn masa cuti +15hari

    private function get_two_weeks_this_year($id_current_user, $quota_origin){
        //posisi tahun sekarang misalkan 2019
        //start menghitung sisa kuota tahun lalu tanpa utang cuti
        $year_select = date('Y');
        $year_select_previous = $year_select - 1;
        $leave_quota_employment_last_year = $this->get_employment_quota_leave($id_current_user, $year_select_previous);
        $jum_quota_adjustment_last_year = $this->get_adjustment_quota($id_current_user, $year_select_previous);
        $leave_quota_last_year = $this->get_quota_left($id_current_user, $year_select_previous, $leave_quota_employment_last_year, $jum_quota_adjustment_last_year);
        $leave_quota_remaining = $quota_origin; //quota tahun ini setelah dipotong berbagai adjustment
        if ($leave_quota_last_year > 0 ){
            //perhitungan extend 15 hari masa pengambilan cuti - tahun ini mengambil kuota tahun lalu
            $final_quota_leave_year_extend = $this->get_final_quota_leave_year_extend($id_current_user, $year_select);
            if ($final_quota_leave_year_extend == $leave_quota_remaining){
                $leave_quota_remaining = $quota_origin + $leave_quota_last_year;
            }
            else if ($final_quota_leave_year_extend < $leave_quota_last_year){
                $sisa_pengurangan = $leave_quota_last_year - $final_quota_leave_year_extend;
                $leave_quota_remaining =  $quota_origin + $sisa_pengurangan;
            }
            else if ($final_quota_leave_year_extend > $leave_quota_last_year){
                $sisa_kelebihan = $final_quota_leave_year_extend - $leave_quota_last_year;
                $leave_quota_remaining =  $quota_origin - $sisa_kelebihan;
            }
        }
        return $leave_quota_remaining;
        //end menghitung sisa kuota tahun lalu tanpa utang cuti
    }

    private function get_minus_quota_prev_year($id_current_user, $year_select){
        //start perhitungan tahun lalu untuk pengecekan minus
        $year_select_previous = $year_select - 1;
        $leave_quota_employment_last_year = $this->get_employment_quota_leave($id_current_user, $year_select_previous);
        $jum_quota_adjustment_last_year = $this->get_adjustment_quota($id_current_user, $year_select_previous);
        $debt_quota_last_year = $this->get_final_leave_quota_last_year($id_current_user, $year_select_previous, $leave_quota_employment_last_year, $jum_quota_adjustment_last_year);
        $minus_quota_prev_year = abs ($debt_quota_last_year - 3);

        return $minus_quota_prev_year;
        //end perhitungan tahun lalu untuk pengecekan minus
    }

    private function cek_employee_leave_extend($id_current_user, $year_select, $current_leave_quota_origin){
        $dates = array();
        $list_employment_extend = $this->employment_m->employment_staff_extend($id_current_user);
        foreach($list_employment_extend as $emp){
            if($emp['tgl_berakhir'] == ""){
                $emp['tgl_berakhir'] = $year_select."-12-31";
            }
            while (strtotime($emp['tgl_mulai']) < strtotime($emp['tgl_berakhir']))
            {
                $emp['tgl_mulai'] = date('Y-m-d',strtotime($emp['tgl_mulai'].'+1 day'));
                $dates[] = date('Y',strtotime($emp['tgl_mulai']));
            }
        }
        $year_prev = $year_select - 1;
        if (in_array($year_prev, $dates)){
            $quota_origin = $this->get_two_weeks_this_year($id_current_user, $current_leave_quota_origin);
        }
        else {
            $quota_origin = $current_leave_quota_origin;
        }

        return $quota_origin;
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
